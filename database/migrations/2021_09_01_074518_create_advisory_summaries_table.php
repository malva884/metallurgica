<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvisorySummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advisory_summaries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order');
            $table->uuid('head');
            $table->foreign('head')->references('id')->on('advisory_heads')->onDelete('cascade');
            $table->decimal('total_real', $precision = 10, $scale = 2)->default(0.00);
            $table->decimal('total_theoretical', $precision = 10, $scale = 2)->default(0.00);
            $table->string('note');
            $table->decimal('cost_material', $precision = 10, $scale = 2)->default(0.00);
            $table->string('quantity_produced')->nullable();
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
        Schema::dropIfExists('advisory_summaries');
    }
}
