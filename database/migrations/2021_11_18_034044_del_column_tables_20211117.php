<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DelColumnTables20211117 extends Migration

{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){

        Schema::table('years_report', function (Blueprint $table) {
            $table->dropForeign('years_report_ibfk_1');
            $table->dropColumn('farm_id');
            
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
