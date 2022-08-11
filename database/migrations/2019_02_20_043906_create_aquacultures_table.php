<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAquaculturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebi_aquacultures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pond_id');
            $table->date('start_date')->nullable();
            $table->date('estimate_shipping_date')->nullable();
            $table->decimal('bait_qty_day',8,2)->nullable();
            $table->string('bait_feed',255)->nullable();
            $table->decimal('power_consumption_day',8,2)->nullable();
            $table->integer('amount_juvenile_shrimp')->nullable();
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
        Schema::dropIfExists('ebi_aquacultures');
    }
}
