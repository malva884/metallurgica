<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkStatusHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    public function up()
    {
        Schema::create('work_status_heads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('status');
            $table->decimal('total_final_mp', $precision = 10, $scale = 2)->default(0.00);
            $table->decimal('total_mp', $precision = 10, $scale = 2)->default(0.00);
            $table->decimal('total_old', $precision = 10, $scale = 2)->default(0.00);
            $table->decimal('total_final', $precision = 10, $scale = 2)->default(0.00);
            $table->string('total_raw_material', )->nullable();
            $table->string('total_raw_material2')->nullable();
            $table->decimal('total_machine_manpower_cost', $precision = 10, $scale = 2)->default(0.00);
            $table->decimal('total_machine_manpower_cost2', $precision = 10, $scale = 2)->default(0.00);
            $table->string('raw_material_machine_manpower_cost')->nullable();
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
        Schema::dropIfExists('work_status_heads');
    }
}
