<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('Workflow');
            $table->foreign('Workflow')->references('id')->on('Workflows')->onDelete('cascade');
            $table->integer('user');
            $table->string('nomeFile')->nullable();
            $table->text('path')->nullable();
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
        Schema::dropIfExists('workflow_files');
    }
}
