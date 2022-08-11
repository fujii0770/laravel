<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CubicMeterNumFoam extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ebi_ponds', function (Blueprint $table) {
            $table->integer("nano_bubble")->default(0)->comment("ナノバブルあり1:なし0");
        });

        Schema::table('ponds_aquacultures', function (Blueprint $table) {
            $table->integer("cubic_meter_num")->nullable()->comment("1立法メートルあたり何匹いるか(初期)");
            $table->integer("nano_bubble")->default(0)->comment("ナノバブルあり1:なし0");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
