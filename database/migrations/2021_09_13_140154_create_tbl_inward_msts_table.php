<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblInwardMstsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_inward_msts', function (Blueprint $table) {
            $table->unsignedBigInteger('inward_mst_id')->autoIncrement();
            $table->date('inward_mst_date');
            $table->string('inward_mst_invoice_no', 20);
            $table->unsignedBigInteger('inward_mst_vendor_id');
            $table->foreign('inward_mst_vendor_id')->references('vendor_id')->on('tbl_vendors');
            $table->unsignedBigInteger('inward_mst_broker_id');
            $table->foreign('inward_mst_broker_id')->references('broker_id')->on('tbl_brokers');
            $table->integer('inward_mst_gst_percentage')->default(0);
            $table->timestamps();
            $table->boolean('inward_mst_status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_inward_msts');
    }
}
