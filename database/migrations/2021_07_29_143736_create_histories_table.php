<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriesTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('user_id');
            $table->string('meet_person_name', 255);
            $table->timestamp('entered_at')->useCurrent();
            $table->timestamp('exited_at')->nullable()->useCurrentOnUpdate();
            $table->timestamp('expired_at')->nullable();

            $table->foreign('unit_id')->references('unit_id')->on('unit_users');
            $table->foreign('user_id')->references('user_id')->on('unit_users');

            $table->timestamps();
            
            $table->string('created_by', 255)->default('system');
            $table->string('updated_by', 255)->nullable();

            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histories');
    }
}
