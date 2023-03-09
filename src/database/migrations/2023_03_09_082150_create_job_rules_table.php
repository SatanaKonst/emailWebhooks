<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_rules', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->timestamps();
            $table->integer('jobs_id');
            $table->string('regex')->nullable();
            $table->string('webhook_method')->nullable();
            $table->string('webhook_url')->nullable();
            $table->string('webhook_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_rules');
    }
}
