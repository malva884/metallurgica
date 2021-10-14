<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('head');
            $table->foreign('head')->references('id')->on('work_status_heads')->onDelete('cascade');
            $table->string('order');
            $table->string('father')->nullable();
            $table->bigInteger('order_row');
            $table->string('material_1');
            $table->string('material_description_1');
            $table->string('division');
            $table->string('total_order_quantity');
            $table->string('um');
            $table->string('quantity_1');
            $table->string('operation_activity');
            $table->string('work_center');
            $table->string('work_center_cost')->nullable();
            $table->string('confirmed_quantity')->nullable();
            $table->string('activity_machine_hours')->nullable();
            $table->string('machine_cost');
            $table->string('activity_manpower_hours');
            $table->string('manpower_cost');
            $table->string('machine_cost_manpower');
            $table->string('calculation_1');
            $table->string('material_2');
            $table->string('material_description_2');
            $table->string('requirement_quantity');
            $table->string('base_unit_measure');
            $table->string('quantity_2');
            $table->string('material_cost');
            $table->string('cost_material')->nullable();
            $table->string('calculation_2');
            $table->string('total_raw_material');
            $table->string('total_machine_cost_manpower');
            $table->string('final_total');
            $table->string('raw_material_machine_manpower_cost');
            $table->date('date_created');
            $table->string('result_1')->nullable();
            $table->string('result_2')->nullable();
            $table->string('qty_perv_fase')->nullable();
            $table->string('um_fase')->nullable();
            $table->boolean('check_row')->default(false);
            $table->boolean('view')->default(true);
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
        Schema::dropIfExists('work_statuses');
    }
}
