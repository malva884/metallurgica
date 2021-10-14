<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('firstname')->nullable();
                $table->string('lastname')->nullable();
                $table->string('sex')->nullable();
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('extension')->nullable();
                $table->integer('region')->nullable();
                $table->integer('status')->nullable();
                $table->integer('workflow')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->integer('acl')->default(0);
                $table->text('image')->nullable();;
                $table->string('password');
                $table->boolean('_deleted')->default(false);
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
