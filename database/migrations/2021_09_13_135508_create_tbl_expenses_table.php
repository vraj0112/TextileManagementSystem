<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('expense_id')->autoIncrement();
            $table->date('expense_date');
            $table->unsignedInteger('expense_category_id');
            $table->foreign('expense_category_id')->references('expense_category_id')->on('tbl_expense_categories');
            $table->text("expense_description");
            $table->float('expense_amount')->default(0.00);
            $table->timestamps();
            $table->boolean('expense_status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_expenses');
    }
}
