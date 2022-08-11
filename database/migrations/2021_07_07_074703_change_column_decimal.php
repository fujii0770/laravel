<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnDecimal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('years_report', function (Blueprint $table) {
            $table->decimal('feed_cumulative',20, 4)->nullable()->change();
            $table->decimal('medicine_cumulative',20, 4)->nullable()->change();
            $table->decimal('ebi_price',20, 4)->nullable()->change();
            $table->decimal('income_and_expenditure',20, 4)->nullable()->change();
            $table->decimal('target_syusi',20, 4)->nullable()->change();
        });

        Schema::table('threshold_grow', function (Blueprint $table) {
            $table->decimal('weight',7, 4)->nullable()->change();
        });

        Schema::table('threshold_feed', function (Blueprint $table) {
            $table->decimal('weight',7, 4)->nullable()->change();
        });

        Schema::table('threshold_fcr', function (Blueprint $table) {
            $table->decimal('fcr',7, 4)->nullable()->change();
        });

        Schema::table('ponds_aquacultures', function (Blueprint $table) {
            $table->decimal('feed_cumulative',20, 4)->nullable()->change();
            $table->decimal('medicine_cumulative',20, 4)->nullable()->change();
            $table->decimal('average_price',12, 4)->nullable()->change();
            $table->decimal('income_and_expenditure',20, 4)->nullable()->change();
        });

        Schema::table('medicine_cumulative', function (Blueprint $table) {
            $table->decimal('cumulative',20, 4)->nullable()->change();
            $table->decimal('cost',20, 4)->nullable()->change();
        });


        Schema::table('feed_cumulative', function (Blueprint $table) {
            $table->decimal('cumulative',20, 4)->nullable()->change();
            $table->decimal('cost',20, 4)->nullable()->change();
        });

        Schema::table('fcr', function (Blueprint $table) {
            $table->decimal('fcr',8, 4)->nullable()->change();
        });

        Schema::table('ebi_aquacultures', function (Blueprint $table) {
            $table->decimal('feed_cumulative',20, 4)->nullable()->change();
            $table->decimal('medicine_cumulative',20, 4)->nullable()->change();
            $table->decimal('ebi_price',20, 4)->nullable()->change();
            $table->decimal('average_price',12, 4)->nullable()->change();
            $table->decimal('income_and_expenditure',20, 4)->nullable()->change();
        });


        Schema::table('cost', function (Blueprint $table) {
            $table->decimal('cost',20, 4)->nullable()->change();
        });

        Schema::table('adg', function (Blueprint $table) {
            $table->decimal('adg',8, 4)->nullable()->change();
        });





    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
