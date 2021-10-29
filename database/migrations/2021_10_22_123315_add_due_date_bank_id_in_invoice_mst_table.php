<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDueDateBankIdInInvoiceMstTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_invoice_msts', function (Blueprint $table) {
            $table->unsignedInteger('bank_details_id')->after('gst_percentage');
            $table->foreign('bank_details_id')->references('bank_details_id')->on('tbl_bank_details');
            $table->date('due_date')->after('bank_details_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_mst', function (Blueprint $table) {
            //
        });
    }
}
