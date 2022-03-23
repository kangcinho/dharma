<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrRevenueTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trRevenueTransaksi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('idTrRevenue')->unsigned();
            $table->integer('idBulan')->unsigned();
            $table->decimal('totalRevenue',14,2)->default(0);
            $table->timestamps();
            $table->foreign('idTrRevenue')->references('id')->on('trRevenue');
            $table->foreign('idBulan')->references('id')->on('mBulan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trRevenueTransaksi');
    }
}
