<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestSessionsCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_sessions_cases', function (Blueprint $table) {
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('case_id');
            $table->foreign('case_id')->references('id')->on('test_cases')->onDelete('cascade');
            $table->unsignedBigInteger('session_id');
            $table->foreign('session_id')->references('id')->on('test_sessions')->onDelete('cascade');
            $table->primary(['case_id', 'session_id']);
            $table->unsignedBigInteger('operation_id');
            $table->foreign('operation_id')->references('operation_id')->on('test_cases')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_sessions_test_cases');
    }
}
