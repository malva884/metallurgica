<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentStructuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_structures', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('document');
            $table->foreign('document')->references('id')->on('documents');
            $table->string('document_father')->nullable();
            $table->dateTime('document_date');
            $table->string('document_type');
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
        Schema::dropIfExists('document_structures');
    }
}
