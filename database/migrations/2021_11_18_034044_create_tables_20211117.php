<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables20211117 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //冷凍在庫売上テーブル
        Schema::create('cold_sell', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->Integer('sell')->unsigned();
            $table->Integer('amount')->unsigned();
            $table->Integer('ebi_weight');
            $table->Integer('trade_flg')->default(1)->comment('1 海外 : 0 国内');
            $table->date('trade_date');
            $table->datetime('create_at')->useCurrent()->comment('作成日時');
			$table->string('create_user', 128)->nullable()->comment('作成者');
			$table->dateTime('update_at')->nullable()->comment('更新日時');
			$table->string('update_user', 128)->nullable()->comment('更新者');
        });

        //冷凍在庫テーブル
        Schema::create('cold_stock', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->Integer('amount')->unsigned();
            $table->Integer('ebi_weight')->unsigned();
            $table->datetime('create_at')->useCurrent()->comment('作成日時');
			$table->string('create_user', 128)->nullable()->comment('作成者');
			$table->dateTime('update_at')->nullable()->comment('更新日時');
			$table->string('update_user', 128)->nullable()->comment('更新者');
            $table->timestamps();
        });

        Schema::table('ebi_baits', function (Blueprint $table) {
            $table->Integer('bait_cost');  //カラム追加 金額履歴
        });

        Schema::table('shipment', function (Blueprint $table) {
            $table->Integer('cold_flg')->after('amount')->default(1)->comment('1 冷凍: 0 通常');
            $table->Integer('trade_flg')->after('cold_flg')->default(1)->comment('1 海外 : 0 国内');
            $table->date('harvest_date')->after('trade_flg')->nullable();  //ハーベスト日時
        });

        Schema::table('ponds_aquacultures', function (Blueprint $table) {
            $table->datetime('start_date')->after('id')->nullable();  //開始日時 データ移行後 null不可に変更
            
        });

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tables_20211117');
    }
}
