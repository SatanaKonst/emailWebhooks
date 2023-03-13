<?php

namespace App\Http\Controllers;

use App\Models\JobRules;
use App\Models\Jobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebhookEmail extends Controller
{
    /** ДОбавить задачу
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addJob(Request $request)
    {
        $newJob = new Jobs();
        $newJob->title = $request->get('title');
        $newJob->user_id = Auth::id();
        $newJob->save();

        return redirect()->intended(
            !empty($request->get('redirect')) ?
                $request->get('redirect') :
                '/dashboard'
        );
    }

    /** Добавить правило
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function addRule(Request $request)
    {
        $newRule = new JobRules();
        if (empty($request->get('jobs_id'))) {
            throw new \Exception('empty jobs_id');
        }
        $newRule->jobs_id = (int)$request->get('jobs_id');
        $newRule->theme = !empty($request->get('theme')) ? $this->filterData($request->get('theme')) : '';
        $newRule->sender = !empty($request->get('sender')) ? $this->filterData($request->get('sender')) : '';
        $newRule->webhook_method = !empty($request->get('webhook_method')) ? $this->filterData($request->get('webhook_method')) : 'GET';
        $newRule->webhook_url = !empty($request->get('webhook_url')) ? $this->filterData($request->get('webhook_url')) : '';
        $newRule->webhook_data = !empty($request->get('webhook_data')) ? $request->get('webhook_data') : '';
        $newRule->save();

        return redirect()->intended(
            !empty($request->get('redirect')) ?
                $request->get('redirect') :
                '/dashboard'
        );
    }

    /** Фильтрация входящих данных
     * @param string $data
     * @return string
     */
    private function filterData(string $data)
    {
        return htmlspecialchars(
            strip_tags(
                $data
            )
        );
    }
}
