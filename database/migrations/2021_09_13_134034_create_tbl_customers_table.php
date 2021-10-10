<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_customers', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->autoIncrement();
            $table->string('customer_company_name', 50);
            $table->string('customer_contact_no', 10);
            $table->string('customer_email', 255)->nullable();
            $table->string('customer_gst_no', 15);
            $table->string('customer_gst_code', 2);
            $table->string('customer_address', 255);
            $table->timestamps();
            $table->boolean('customer_status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_customers');
    }
}
