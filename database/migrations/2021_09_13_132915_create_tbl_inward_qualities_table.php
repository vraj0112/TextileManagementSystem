<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblInwardQualitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_inward_qualities', function (Blueprint $table) {
            $table->unsignedInteger('inward_quality_id')->autoIncrement();
            $table->string('quality_name',50);
            $table->unsignedInteger('inward_quality_category_id');
            $table->foreign('inward_quality_category_id')->references('inward_quality_category_id')->on('tbl_inward_quality_categories');
            $table->timestamps();
            $table->boolean('inward_quality_status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_inward_qualities');
    }
}
