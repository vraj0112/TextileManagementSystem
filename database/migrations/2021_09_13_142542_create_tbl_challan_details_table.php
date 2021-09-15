<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblChallanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_challan_details', function (Blueprint $table) {
            $table->bigInteger('challan_details_id')->unsigned()->autoIncrement();
            $table->integer('no');
            $table->float('qty')->default(0.00);
            $table->bigInteger('challan_mst_id')->unsigned();
            $table->foreign('challan_mst_id')->references('challan_mst_id')->on('tbl_challan_msts');
            $table->smallInteger('challan_type');
            $table->timestamps();
            $table->boolean('challan_details_status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_challan_details');
    }
}
