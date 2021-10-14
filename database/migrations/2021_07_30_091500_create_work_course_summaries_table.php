<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkCourseSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_course_summaries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order');
            $table->uuid('head');
            $table->foreign('head')->references('id')->on('work_status_heads')->onDelete('cascade');
            $table->string('total_material');
            $table->string('total_consuntivo');
            $table->string('note');
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
        Schema::dropIfExists('work_course_summaries');
    }
}
