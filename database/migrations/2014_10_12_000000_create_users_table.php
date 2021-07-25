<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email', 255)->nullable();
            $table->string('password')->nullable();
            $table->string('mobile_number', 10);
            $table->enum('user_type', ['admin', 'tenant', 'visitor']);
            $table->string('nric', 5);
            // $table->unsignedBigInteger('unit_id');
            $table->text('remember_token')->nullable();

            $table->timestamps();
            $table->string('deleted_by', 255)->nullable();
            $table->softDeletes($column = 'deleted_at', $precision = 0);

            // $table->foreign('unit_id')->references('id')->on('units');
            // $table->foreign('user_id')->references('id')->on('users');
        });
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
