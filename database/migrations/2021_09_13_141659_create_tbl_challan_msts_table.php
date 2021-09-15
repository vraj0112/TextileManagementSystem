<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblChallanMstsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_challan_msts', function (Blueprint $table) {
            $table->unsignedBigInteger('challan_mst_id')->autoIncrement();
            $table->integer('challan_no');
            $table->date('challan_date');
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('customer_id')->on('tbl_customers');
            $table->integer('sell_quality_id')->unsigned();
            $table->foreign('sell_quality_id')->references('sell_quality_id')->on('tbl_sell_qualities');
            $table->string('qty_unit', 10);
            $table->float('total_qty')->default(0.00);
            $table->bigInteger('broker_id')->unsigned();
            $table->foreign('broker_id')->references('broker_id')->on('tbl_brokers');
            $table->smallInteger('challan_type');
            $table->timestamps();
            $table->boolean('is_direct')->default(false);
            $table->boolean('challan_mst_status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_challan_msts');
    }
}
