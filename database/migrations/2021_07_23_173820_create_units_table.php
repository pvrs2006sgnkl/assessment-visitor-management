<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('block_no')->default('NA');
            $table->string('unit_no', 10);
            $table->string('type', 50)->default('2R')->comments('Define the unit type');
            $table->string('contact_number', 10);
            $table->timestamps();

            $table->unique(["block_no", "unit_no"], 'Unique unit values');
            $table->string('deleted_by', 255)->nullable();
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
        Schema::dropIfExists('units');
    }
}
