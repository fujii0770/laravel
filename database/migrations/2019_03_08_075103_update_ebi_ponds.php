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
            $table->string('lat_long_se', 10)->nullable();
            $table->string('lat_long_sw', 10)->nullable();
            $table->string('lat_long_ne', 10)->nullable();
            $table->string('lat_long_nw', 10)->nullable();
            $table->string('delta_measure', 10)->nullable();
            $table->string('pond_image_area', 50)->nullable()->change();
            $table->dropColumn('pond_lonlat');
            $table->tinyInteger('pond_method')->default(0);
            $table->string('tag1', 255)->nullable();
            $table->string('tag2', 255)->nullable();
            $table->string('tag3', 255)->nullable();
            $table->string('tag4', 255)->nullable();
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
