<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePondAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebi_pond_alerts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pond_id');
            $table->date('alert_date');
            $table->time('alert_time');
            $table->string('first_criterion', 10);
            $table->integer('criterion_total');
            $table->text('alert_detail');
            $table->tinyInteger('disable_flag');
            $table->integer('created_user')->default(1);
            $table->integer('updated_user')->nullable();
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
        Schema::dropIfExists('ebi_pond_alerts');
    }
}
