<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblInwardDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_inward_details', function (Blueprint $table) {
            $table->unsignedBigInteger('inward_details_id')->autoIncrement();
            $table->unsignedInteger('inward_quality_id');
            $table->foreign('inward_quality_id')->references('inward_quality_id')->on('tbl_inward_qualities');
            $table->float('qty')->default(0.0);
            $table->string('qty_unit', 10);
            $table->float('rate')->default(0.0);
            $table->unsignedBigInteger('inward_mst_id');
            $table->foreign('inward_mst_id')->references('inward_mst_id')->on('tbl_inward_msts');
            $table->timestamps();
            $table->boolean('inward_details_status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_inward_details');
    }
}
