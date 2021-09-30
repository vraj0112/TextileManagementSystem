<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblPaymentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payment_details', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_id')->autoIncrement();
            $table->unsignedBigInteger('expense_id');
            $table->foreign('expense_id')->references('expense_id')->on('tbl_expenses');
            $table->date('payment_date');
            $table->string('payment_type',50);
            $table->text('payment_description');
            $table->float('payment_amount',15)->default(0.00);
            $table->timestamps();
            $table->boolean('payment_details_status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_payment_details');
    }
}
