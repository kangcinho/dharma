<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrRevenueTransaksiDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trRevenueTransaksiDetail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('idTrRevenueTransaksi')->unsigned();
            $table->integer('idKategori')->unsigned();
            $table->decimal('totalRevenue', 14,2)->default(0);
            $table->bigInteger('jumlahPasienTotal')->default(0);
            $table->bigInteger('jumlahPasienBaru')->default(0);
            $table->bigInteger('jumlahPasienRepeater')->default(0);
            $table->string('idSyncToSanata')->nullable();
            $table->boolean('isKamar')->default(0);
            $table->boolean('isSection')->default(0);
            $table->boolean('isPasien')->default(0);
            $table->boolean('isPasienRepeater')->default(0);
            $table->boolean('isPaket')->default(0);
            $table->date('lastSyncToSanata')->nullable();
            $table->boolean('isSync')->default(0);
            $table->boolean('finishSync')->default(0);
            $table->timestamps();

            $table->foreign('idTrRevenueTransaksi')->references('id')->on('trRevenueTransaksi');
            $table->foreign('idKategori')->references('id')->on('mKategori');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trRevenueTransaksiDetail');
    }
}
