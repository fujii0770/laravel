<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEbiPondStatesAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ebi_pond_states', function (Blueprint $table) {
            $table->string('ammonia_values', 100)->nullable();
            $table->string('copper_ion_values', 100)->nullable();
            $table->string('out_temp_values', 100)->nullable();
        });

        Schema::table('ebi_minmaxs', function (Blueprint $table) {
            $table->decimal('out_temp_values_min', 8, 3)->nullable();
            $table->decimal('out_temp_values_max', 8, 3)->nullable();
            $table->decimal('ammonia_values_min', 8, 3)->nullable();
            $table->decimal('copper_ion_values_min', 8, 3)->nullable();
            $table->decimal('ammonia_values_max', 8, 3)->nullable();
            $table->decimal('copper_ion_values_max', 8, 3)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ebi_pond_states', function (Blueprint $table) {
            //
        });
    }
}
