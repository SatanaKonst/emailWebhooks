<?php

namespace Database\Seeders;

use App\Models\Jobs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class addTestJob extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $newJob = new Jobs();
        $newJob->user_id = 1;
        $newJob->title = 'Test Job';
        $newJob->save();
    }
}
