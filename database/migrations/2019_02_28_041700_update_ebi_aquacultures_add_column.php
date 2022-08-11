<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEbiAquaculturesAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ebi_aquacultures', function (Blueprint $table) {
            $table->string('aquaculture_method', 100)->nullable();
            $table->renameColumn('power_consumption_day','power_consumption');
            $table->renameColumn('bait_qty_day','food_amount');
            $table->dropColumn('bait_feed');
            $table->dropColumn('amount_juvenile_shrimp');
            $table->integer('feeding_num')->nullable()->comment('(回 / 日)');
            $table->integer('shrimp_num')->nullable()->comment('(匹)');
            $table->decimal('water_temp')->nullable()->comment('(°C)');
            $table->decimal('pressure')->nullable()->comment('(psi)');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ebi_aquacultures', function (Blueprint $table) {
            //
        });
    }
}
