<?php

namespace App\Http\Controllers;

use App\Models\JobRules;
use App\Models\Jobs;
use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\Job;
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

    /** Обновить задачу
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateJob(Request $request)
    {
        $job = Jobs::query()->where('id', '=', (int)$request->get('job_id'))->first();
        $job->title = $request->get('title');
        $job->save();

        return redirect()->intended(
            !empty($request->get('redirect')) ?
                $request->get('redirect') :
                '/dashboard'
        );
    }

    /** Удалить задание
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeJob(Request $request)
    {
        /**
         * @var Jobs $job
         */
        $job = Jobs::query()->where('id', '=', (int)$request->get('job_id'))->first();
        if (!empty($job->rules)) {
            foreach ($job->rules as $rule) {
                $rule->delete();
            }
        }
        $job->delete();
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
        $newRule->theme = $request->get('theme');
        $newRule->sender = $request->get('sender');
        $newRule->regex = $request->get('regex');
        $newRule->webhook_method = !empty($request->get('webhook_method')) ? $request->get('webhook_method') : 'GET';
        $newRule->webhook_url = $request->get('webhook_url');
        $newRule->webhook_data = $request->get('webhook_data');
        $newRule->save();

        return redirect()->intended(
            !empty($request->get('redirect')) ?
                $request->get('redirect') :
                '/dashboard'
        );
    }

    /** Обновить правило
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateRule(Request $request)
    {
        /**
         * @var JobRules $rule
         */
        $rule = JobRules::query()->where('id', '=', (int)$request->get('rule_id'))->first();
        $rule->theme = $request->get('theme');
        $rule->sender = $request->get('sender');
        $rule->regex = $request->get('regex');
        $rule->webhook_method = $request->get('webhook_method');
        $rule->webhook_url = $request->get('webhook_url');
        $rule->webhook_data = $request->get('webhook_data');

        $rule->save();

        return redirect()->intended(
            !empty($request->get('redirect')) ?
                $request->get('redirect') :
                '/dashboard'
        );
    }


    public function removeRule(Request $request)
    {
        $rule = JobRules::query()->where('id', '=', (int)$request->get('rule_id'))->first();
        $rule->delete();
        return redirect()->intended(
            !empty($request->get('redirect')) ?
                $request->get('redirect') :
                '/dashboard'
        );
    }


}
