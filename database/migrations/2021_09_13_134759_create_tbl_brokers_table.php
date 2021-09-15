<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblBrokersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_brokers', function (Blueprint $table) {
            $table->unsignedBigInteger('broker_id')->autoIncrement();
            $table->string('broker_name', 70);
            $table->string('broker_contact_no', 10);
            $table->timestamps();
            $table->boolean('broker_status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_brokers');
    }
}
