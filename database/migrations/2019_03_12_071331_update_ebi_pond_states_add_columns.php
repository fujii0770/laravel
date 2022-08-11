<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateEbiPondStatesAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ebi_pond_states', function (Blueprint $table) {
            $table->string('sigma_t_values', 100)->nullable();
            $table->dropColumn('sigma_t');
            $table->string('location', 100)->nullable();
            $table->string('tag_id', 100)->nullable();
            $table->string('remarks', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ebi_pond_states', function (Blueprint $table) {
            //
        });
    }
}
