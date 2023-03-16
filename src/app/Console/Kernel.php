<?php

namespace App\Console;

use App\Http\Controllers\Imap;
use App\Models\JobRules;
use App\Models\Jobs;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            try {
                /**
                 * Получим список задач
                 * @var Jobs $job
                 */
                $job = Jobs::query()->orderBy('last_run', 'desc')->first();

                if (empty($job)) {
                    echo "Empty Job \n";
                    return true;
                }

                /**
                 * Бежим по правилам, фильтруем сообщения если надо и отправляем запросы
                 */
                $client = new Client();
                $imap = new Imap();
                $imap->get();

                foreach ($job->rules as $rule) {
                    $messages = $imap->getMessages();

                    //Фильтр по отправителю
                    if (!empty($rule->sender)) {
                        $imap->filterEmailByFrom(
                            $rule->sender,
                            $messages
                        );
                    }

                    //Фильтр по заголовку
                    if (!empty($rule->theme)) {
                        $imap->filterEmailByTitle(
                            $rule->theme,
                            $messages
                        );
                    }

                    // Получим данные из текста сообщений
                    if (!empty($rule->regex)) {
                        foreach ($messages as $message) {
                            $data = [
                                mb_strtolower($message->getSubject()->toString()),
                                mb_strtolower($message->getFrom()->toString()),
                                mb_strtolower($message->getTextBody()),
                            ];
                            $data = implode(' ', $data);

                            preg_match_all($rule->regex, $data, $matches, PREG_SET_ORDER, 0);
                            if (is_array($matches) && !empty($matches)) {
                                //Отправляем запрос
                                $method = !empty($rule->webhook_method) ? $rule->webhook_method : 'GET';
                                $url = $rule->webhook_url;
                                $webhookData = $rule->webhook_data;
                                if (!empty($webhookData)) {
                                    $webhookData = str_replace('#REGEX_MATCHES#', json_encode($matches), $data);
                                }

                                switch ($method) {
                                    case 'GET':
                                        $webhookData = mb_split("\n", $webhookData);
                                        $url .= '?' . http_build_query($webhookData);
                                        $client->getAsync($url);
                                        break;

                                    case 'POST':
                                        $client->postAsync(
                                            $url,
                                            [
                                                'json' => $webhookData
                                            ]
                                        );
                                        break;
                                }
                            }
                        }
                    }
                }

                $job->last_run = Carbon::now();
                $job->save();
            } catch (\Throwable $exception) {
                echo '<pre>';
                print_r($exception->getMessage());
                echo '</pre>';
            }

        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
