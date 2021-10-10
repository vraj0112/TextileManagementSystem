<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_vendors', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_id')->autoIncrement();
            $table->string('vendor_company_name', 50);
            $table->string('vendor_contact_no', 10);
            $table->string('vendor_email', 255)->nullable();
            $table->string('vendor_gst_no', 15);
            $table->string('vendor_gst_code', 2);
            $table->string('vendor_address', 255);
            $table->timestamps();
            $table->boolean('vendor_status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_vendors');
    }
}
