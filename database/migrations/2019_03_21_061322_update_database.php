<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('ebi_works');

        Schema::table('cms_users', function (Blueprint $table) {
            $table->integer('created_user')->default(1);
            $table->integer('updated_user')->nullable();
        });

        Schema::table('ebi_countries', function (Blueprint $table) {
            $table->integer('created_user')->default(1);
            $table->integer('updated_user')->nullable();
        });

        Schema::table('ebi_aquacultures', function (Blueprint $table) {
            $table->integer('created_user')->default(1);
            $table->integer('updated_user')->nullable();
        });

        Schema::table('ebi_farms', function (Blueprint $table) {
            $table->integer('created_user')->default(1);
            $table->integer('updated_user')->nullable();
        });

        Schema::table('ebi_import_state_logs', function (Blueprint $table) {
            $table->integer('updated_user')->nullable();
        });

        Schema::table('ebi_minmaxs', function (Blueprint $table) {
            $table->integer('created_user')->default(1);
            $table->integer('updated_user')->nullable();
        });

        Schema::table('ebi_pond_states', function (Blueprint $table) {
            $table->integer('created_user')->default(1);
            $table->integer('updated_user')->nullable();
        });

        Schema::table('ebi_ponds', function (Blueprint $table) {
            $table->integer('created_user')->default(1);
            $table->integer('updated_user')->nullable();
        });

        Schema::table('ebi_shrimp_states', function (Blueprint $table) {
            $table->integer('created_user')->default(1);
            $table->integer('updated_user')->nullable();
        });

        Schema::table('ebi_user_farms', function (Blueprint $table) {
            $table->integer('created_user')->default(1);
            $table->integer('updated_user')->nullable();
        });

        Schema::table('ebi_minmaxs', function (Blueprint $table) {
            $table->decimal('tmp_values_min', 8, 2)->change();
            $table->decimal('tmp_values_max', 8, 2)->change();
            $table->decimal('ph_values_min', 8, 2)->change();
            $table->decimal('ph_values_max', 8, 2)->change();
            $table->decimal('mv_values_min', 8, 2)->change();
            $table->decimal('mv_values_max', 8, 2)->change();
            $table->decimal('orp_values_min', 8, 2)->change();
            $table->decimal('orp_values_max', 8, 2)->change();
            $table->decimal('ec_values_min', 8, 2)->change();
            $table->decimal('ec_values_max', 8, 2)->change();
            $table->decimal('ec_abs_values_min', 8, 2)->change();
            $table->decimal('ec_abs_values_max', 8, 2)->change();
            $table->decimal('tds_values_min', 8, 2)->change();
            $table->decimal('tds_values_max', 8, 2)->change();
            $table->decimal('res_values_min', 8, 2)->change();
            $table->decimal('res_values_max', 8, 2)->change();
            $table->decimal('do_values_min', 8, 2)->change();
            $table->decimal('do_values_max', 8, 2)->change();
            $table->decimal('do_ppm_values_min', 8, 2)->change();
            $table->decimal('do_ppm_values_max', 8, 2)->change();
            $table->decimal('turb_fnu_values_min', 8, 2)->change();
            $table->decimal('turb_fnu_values_max', 8, 2)->change();
            $table->decimal('sigma_t_values_min', 8, 2)->change();
            $table->decimal('sigma_t_values_max', 8, 2)->change();
            $table->decimal('press_values_min', 8, 3)->change();
            $table->decimal('press_values_max', 8, 3)->change();
            $table->decimal('sal_values_min', 8, 5)->change();
            $table->decimal('sal_values_max', 8, 5)->change();
        });

        Schema::table('ebi_aquacultures', function (Blueprint $table) {
            $table->date('start_date')->nullable(false)->change();
            $table->date('estimate_shipping_date')->nullable(false)->change();
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
