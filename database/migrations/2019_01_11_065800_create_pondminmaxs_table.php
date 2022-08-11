<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePondminmaxsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebi_minmaxs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pond_id');
            $table->integer('user_id');
            $table->decimal('ph_values_min',30,20)->nullable();
            $table->decimal('orp_values_min',30,20)->nullable();
            $table->decimal('tmp_values_min',30,20)->nullable();
            $table->decimal('ec_values_min',30,20)->nullable();
            $table->decimal('ec_abs_values_min',30,20)->nullable();
            $table->decimal('res_values_min',30,20)->nullable();
            $table->decimal('tds_values_min',30,20)->nullable();
            $table->decimal('sal_values_min',30,20)->nullable();
            $table->decimal('press_values_min',30,20)->nullable();
            $table->decimal('do_values_min',30,20)->nullable();
            $table->decimal('do_ppm_values_min',30,20)->nullable();
            $table->decimal('mv_values_min',30,20)->nullable();
            $table->decimal('turb_fnu_values_min',30,20)->nullable();

            $table->decimal('ph_values_max',30,20)->nullable();
            $table->decimal('orp_values_max',30,20)->nullable();
            $table->decimal('tmp_values_max',30,20)->nullable();
            $table->decimal('ec_values_max',30,20)->nullable();
            $table->decimal('ec_abs_values_max',30,20)->nullable();
            $table->decimal('res_values_max',30,20)->nullable();
            $table->decimal('tds_values_max',30,20)->nullable();
            $table->decimal('sal_values_max',30,20)->nullable();
            $table->decimal('press_values_max',30,20)->nullable();
            $table->decimal('do_values_max',30,20)->nullable();
            $table->decimal('do_ppm_values_max',30,20)->nullable();
            $table->decimal('mv_values_max',30,20)->nullable();
            $table->decimal('turb_fnu_values_max',30,20)->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->tinyInteger('disable_flag');
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
        Schema::dropIfExists('ebi_minmaxs');
    }
}