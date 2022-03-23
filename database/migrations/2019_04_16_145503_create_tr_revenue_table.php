<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrRevenueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trRevenue', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('idTahun')->unsigned();
            $table->decimal('minBatasMerah',14,2);
            $table->decimal('maxBatasMerah',14,2);
            $table->decimal('minBatasKuning',14,2);
            $table->decimal('maxBatasKuning',14,2);
            $table->decimal('minBatasHijau',14,2);
            $table->decimal('maxBatasHijau',14,2);
            $table->decimal('targetRevenue',14,2);
            $table->decimal('budgetJanuari',14,2)->default(0);
            $table->decimal('budgetFebruari',14,2)->default(0);
            $table->decimal('budgetMaret',14,2)->default(0);
            $table->decimal('budgetApril',14,2)->default(0);
            $table->decimal('budgetMei',14,2)->default(0);
            $table->decimal('budgetJuni',14,2)->default(0);
            $table->decimal('budgetJuli',14,2)->default(0);
            $table->decimal('budgetAgustus',14,2)->default(0);
            $table->decimal('budgetSeptember',14,2)->default(0);
            $table->decimal('budgetOktober',14,2)->default(0);
            $table->decimal('budgetNovember',14,2)->default(0);
            $table->decimal('budgetDesember',14,2)->default(0);
            $table->timestamps();

            $table->foreign('idTahun')->references('id')->on('mTahun');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trRevenue');
    }
}
