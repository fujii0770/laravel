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
            $table->date('completed_date')->after('estimate_shipping_date')->nullable();
            $table->decimal('shipping_total', 8, 2)->nullable();
            $table->decimal('target_size', 8, 2)->nullable();
            $table->decimal('target_weight', 8, 2)->nullable();
            $table->dropColumn('water_temp');
            $table->dropColumn('pressure');
            $table->dropColumn('aquaculture_method');
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
