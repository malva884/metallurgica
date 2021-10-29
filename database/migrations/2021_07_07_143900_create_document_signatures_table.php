<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_signatures', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('document');
            $table->foreign('document')->references('id')->on('documents');
            $table->string('document_father')->nullable();
            $table->integer('user');
            $table->boolean('signed')->default(false);
            $table->date('signed_date')->nullable();
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
        Schema::dropIfExists('document_signatures');
    }
}
