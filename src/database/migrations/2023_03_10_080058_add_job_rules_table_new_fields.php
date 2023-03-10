<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJobRulesTableNewFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_rules', function (Blueprint $table){
            $table->string('sender')->nullable();
            $table->string('theme')->nullable();
            $table->string('sub_string_search')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropColumns('job_rules', array(
           'sender',
           'theme',
           'sub_string_search'
       ));
    }
}
