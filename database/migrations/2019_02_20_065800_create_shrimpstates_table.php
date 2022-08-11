<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShrimpstatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebi_shrimp_states', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date_target');
            $table->integer('pond_id');
            $table->decimal('size', 8, 2)->nullable();
            $table->decimal('weight',8, 2)->nullable();
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
        Schema::dropIfExists('ebi_shrimp_states');
    }
}