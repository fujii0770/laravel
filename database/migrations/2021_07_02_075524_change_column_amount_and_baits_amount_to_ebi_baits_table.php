<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnAmountAndBaitsAmountToEbiBaitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ebi_baits', function (Blueprint $table) {
            $table->decimal('amount', 8, 2)->change();
            $table->decimal('baits_amount', 8, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ebi_baits', function (Blueprint $table) {

        });
    }
}
