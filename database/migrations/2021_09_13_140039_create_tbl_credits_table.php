<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_credits', function (Blueprint $table) {
            $table->unsignedBigInteger('credit_id')->autoIncrement();
            $table->date('credit_date');
            $table->text('credit_description');
            $table->float('credit_amount',15)->default(0.00);
            $table->timestamps();
            $table->boolean('credit_status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_credits');
    }
}
