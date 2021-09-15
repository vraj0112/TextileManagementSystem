<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblInwardQualityCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_inward_quality_categories', function (Blueprint $table) {
            $table->unsignedInteger('inward_quality_category_id')->autoIncrement();
            $table->string('inward_category_name',50);
            $table->timestamps();
            $table->boolean('inward_quality_category_status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_inward_quality_categories');
    }
}
