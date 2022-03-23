<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMKategoriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mKategori', function (Blueprint $table) {
            $table->increments('id');
            $table->string('namaKategori');
            $table->boolean('status');
            $table->integer('idGroupKategori')->unsigned();
            $table->boolean('addBySystem')->default(0);
            $table->string('idSyncToSanata')->nullable();
            $table->boolean('isKamar')->default(0);
            $table->boolean('isSection')->default(0);
            $table->boolean('isPasien')->default(0);
            $table->boolean('isPasienRepeater')->default(0);
            $table->boolean('isPaket')->default(0);
            $table->timestamps();
            $table->foreign('idGroupKategori')->references('id')->on('mGroupKategori');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mKategori');
    }
}
