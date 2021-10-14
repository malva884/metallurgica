<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('Workflow');
            $table->foreign('Workflow')->references('id')->on('Workflows')->onDelete('cascade');
            $table->integer('user');
            $table->dateTime('data_view')->nullable();
            $table->boolean('aprovato')->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workflow_users');
    }
}
