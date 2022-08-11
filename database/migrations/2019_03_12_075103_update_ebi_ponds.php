<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEbiPonds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ebi_ponds', function (Blueprint $table) {
            $table->string('lat_long_se', 50)->nullable()->change();
            $table->string('lat_long_sw', 50)->nullable()->change();
            $table->string('lat_long_ne', 50)->nullable()->change();
            $table->string('lat_long_nw', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ebi_ponds', function (Blueprint $table) {
            //
        });
    }
}
