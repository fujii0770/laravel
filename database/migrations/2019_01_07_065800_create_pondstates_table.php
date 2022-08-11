<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePondstatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebi_pond_states', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date_target');
            $table->time('time_target');
            $table->integer('pond_id');
            $table->string('ph_values',100)->nullable();
            $table->string('orp_values',100)->nullable();
            $table->string('tmp_values',100)->nullable();
            $table->string('ec_values',100)->nullable();
            $table->string('ec_abs_values',100)->nullable();
            $table->string('res_values',100)->nullable();
            $table->string('tds_values',100)->nullable();
            $table->string('sal_values',100)->nullable();
            $table->string('press_values',100)->nullable();
            $table->string('do_values',100)->nullable();
            $table->string('do_ppm_values',100)->nullable();
            $table->string('mv_values',100)->nullable();
            $table->string('turb_fnu_values',100)->nullable();
            $table->string('gps_lat_values', 10);
            $table->string('gps_long_values', 10);
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
        Schema::dropIfExists('ebi_pond_states');
    }
}