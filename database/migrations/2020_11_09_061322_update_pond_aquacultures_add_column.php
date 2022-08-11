<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePondAquaculturesAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ponds_aquacultures', function (Blueprint $table) {
            $table->integer('ebi_aquacultures_id');
            $table->foreign('ebi_aquacultures_id')->references('id')->on('ebi_aquacultures');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
