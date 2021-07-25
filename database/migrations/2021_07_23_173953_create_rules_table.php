<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_name', 255)->comments('Title of the rule');
            $table->string('user_type', 10)->comments('user type reference from users');
            $table->string('unit_type', 10)->comments('unit type reference from units');
            $table->bigInteger('max_limit')->comments('Max allowed users/visitors');
            $table->timestamp('starts_on')->useCurrent();
            $table->timestamp('ends_on')->nullable();

            $table->string('created_by', 255)->default('system');
            $table->string('updated_by', 255)->nullable();
            $table->timestamps();

            $table->string('deleted_by', 255)->nullable();
            $table->softDeletes($column = 'deleted_at', $precision = 0);

            // $table->unsignedBigInteger('user_type');
            // $table->unsignedBigInteger('unit_type');

            // $table->foreign('user_type')->references('user_type')->on('users');
            // $table->foreign('unit_type')->references('type')->on('units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rules');
    }
}
