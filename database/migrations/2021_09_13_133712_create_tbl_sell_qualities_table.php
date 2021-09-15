<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblSellQualitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_sell_qualities', function (Blueprint $table) {
            $table->unsignedInteger('sell_quality_id')->autoIncrement();
            $table->string('quality_name',50);
            $table->unsignedInteger('sell_quality_category_id');
            $table->foreign('sell_quality_category_id')->references('sell_quality_category_id')->on('tbl_sell_quality_categories');
            $table->timestamps();
            $table->boolean('sell_quality_status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_sell_qualities');
    }
}
