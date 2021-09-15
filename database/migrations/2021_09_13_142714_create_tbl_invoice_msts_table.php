<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblInvoiceMstsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_invoice_msts', function (Blueprint $table) {
            $table->unsignedBigInteger('invoice_mst_id');
            $table->foreign('invoice_mst_id')->references('challan_mst_id')->on('tbl_challan_msts');
            $table->date('invoice_date');
            $table->float('rate')->default(0.00);
            $table->integer('gst_percentage')->default(0);
            $table->timestamps();
            $table->boolean('invoice_mst_status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_invoice_msts');
    }
}
