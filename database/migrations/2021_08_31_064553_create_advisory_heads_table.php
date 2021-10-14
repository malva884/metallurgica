<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvisoryHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advisory_heads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type')->nullable();
            $table->integer('status');
            $table->string('ol')->nullable();
            $table->string('code_product')->nullable();
            $table->decimal('total_real', $precision = 10, $scale = 2)->default(0.00);
            $table->decimal('total_theoretical', $precision = 10, $scale = 2)->default(0.00);
            $table->date('date_material')->nullable();
            $table->date('date_macchine')->nullable();
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
        Schema::dropIfExists('advisory_heads');
    }
}
