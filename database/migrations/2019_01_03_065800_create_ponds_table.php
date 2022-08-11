<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePondsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebi_ponds', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pond_name', 50);
            $table->text('pond_lonlat')->nullable();
            $table->text('pond_image_area')->nullable();
            $table->decimal('pond_vertical_width', 10, 2);
            $table->decimal('pond_width', 10, 2);
            $table->decimal('water_amount', 10, 2);
            $table->decimal('water_dept', 10, 2);
            $table->integer('farm_id');
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
        Schema::dropIfExists('ebi_ponds');
    }
}
