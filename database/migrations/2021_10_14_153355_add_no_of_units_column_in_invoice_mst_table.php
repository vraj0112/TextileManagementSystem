<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoOfUnitsColumnInInvoiceMstTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_invoice_msts', function (Blueprint $table) {
            //
            $table->smallInteger('no_of_units')->after('invoice_date')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_invoice_msts', function (Blueprint $table) {
            //
            $table->dropColumn('no_of_units');
        });
    }
}
