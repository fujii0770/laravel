<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEbiMinmaxsAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ebi_minmaxs', function (Blueprint $table) {
            $table->decimal('sigma_t_values_min', 30,20)->nullable();

            $table->decimal('sigma_t_values_max', 30,20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ebi_minmaxs', function (Blueprint $table) {
            //
        });
    }
}
