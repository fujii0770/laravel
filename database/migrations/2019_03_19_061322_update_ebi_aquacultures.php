<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEbiAquacultures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ebi_aquacultures', function (Blueprint $table) {
            $table->integer('shipment_count')->nullable();
            $table->integer('shipment_standard_20')->nullable();
            $table->integer('shipment_standard_30')->nullable();
            $table->integer('shipment_standard_40')->nullable();
            $table->integer('shipment_standard_50')->nullable();
            $table->integer('shipment_standard_60')->nullable();
            $table->integer('shipment_standard_70')->nullable();
            $table->integer('shipment_standard_80')->nullable();
            $table->integer('shipment_standard_90')->nullable();
            $table->integer('shipment_standard_100')->nullable();
            $table->integer('shipment_standard_110')->nullable();
            $table->integer('shipment_standard_120')->nullable();
            $table->integer('shipment_standard_130')->nullable();
            $table->integer('shipment_standard_140')->nullable();
            $table->integer('shipment_standard_150')->nullable();
            $table->integer('shipment_standard_160')->nullable();
            $table->integer('shipment_standard_170')->nullable();
            $table->integer('shipment_standard_180')->nullable();
            $table->integer('shipment_standard_190')->nullable();
            $table->integer('shipment_standard_200')->nullable();
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
