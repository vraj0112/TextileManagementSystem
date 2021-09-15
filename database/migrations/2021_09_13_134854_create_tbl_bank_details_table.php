<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_bank_details', function (Blueprint $table) {
            $table->unsignedInteger('bank_details_id')->autoIncrement();
            $table->string("bank_name", 100);
            $table->string("branch_name",100);
            $table->string("account_no", 18);
            $table->string("ifsc_code", 11);
            $table->timestamps();
            $table->boolean('bank_details_status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_bank_details');
    }
}
