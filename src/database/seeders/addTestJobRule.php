<?php

namespace Database\Seeders;

use App\Models\JobRules;
use App\Models\Jobs;
use Illuminate\Database\Seeder;

class addTestJobRule extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $job = Jobs::query()->where('title', 'Test Job')->first();
        $newJobRule = new JobRules();
        $newJobRule->job_id = $job->id;
        $newJobRule->regex = '/test match/m';
        $newJobRule->webhook_method = 'GET';
        $newJobRule->webhook_url = 'https://google.com';
        $newJobRule->webhook_data = '';
        $newJobRule->save();
    }
}
