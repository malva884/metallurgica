<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvisoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advisories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('head');
            $table->foreign('head')->references('id')->on('advisory_heads')->onDelete('cascade');
            $table->string('order');
            $table->string('father')->nullable();
            $table->bigInteger('order_row');
            $table->bigInteger('order_row2')->nullable();
            $table->string('material_1');
            $table->string('material_description_1');
            $table->string('division');
            $table->string('total_order_quantity');
            $table->string('um');
            $table->string('quantity_1');
            $table->string('operation_activity');
            $table->string('operation_activity_row')->nullable();
            $table->string('work_center');
            $table->string('work_center_cost')->nullable();
            $table->string('confirmed_quantity');
            $table->string('activity_machine_minutes')->nullable();
            $table->string('activity_machine_hours')->nullable();
            $table->string('activity_machine_theoretic_minutes')->nullable();
            $table->string('activity_machine_theoretic_hours')->nullable();
            $table->string('machine_cost')->nullable();
            $table->string('activity_manpower_minutes')->nullable();
            $table->string('activity_manpower_hours')->nullable();
            $table->string('activity_manpower_theoretic_minutes')->nullable();
            $table->string('activity_manpower_theoretic_hours')->nullable();
            $table->string('manpower_cost')->nullable();
            $table->string('machine_cost_manpower')->nullable();
            $table->string('material_2');
            $table->string('material_description_2');
            $table->string('requirement_quantity');
            $table->string('base_unit_measure');
            $table->string('quantity_2');
            $table->decimal('material_cost', $precision = 10, $scale = 2)->default(0.00);
            $table->decimal('material_theoretic_cost', $precision = 10, $scale = 2)->default(0.00);
            $table->decimal('material_real_cost', $precision = 10, $scale = 2)->default(0.00);
            $table->date('date_created');
            $table->string('qty_perv_fase')->nullable();
            $table->string('um_fase')->nullable();
            $table->boolean('view')->default(true);
            $table->boolean('check_row')->default(false);
            $table->boolean('calculated')->default(false);
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
        Schema::dropIfExists('advisories');
    }
}
