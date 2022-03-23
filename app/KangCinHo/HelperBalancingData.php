<?php

namespace App\KangCinHo;
use Carbon\Carbon;

class HelperBalancingData{
	public function balancingDataRevenue($dataDailyRJ){
		$dataDailyTemp = collect();
		//Bulan Lalu
		// $endMonth =Carbon::now()->subMonth()->endOfMonth()->toDateString();

		//Bulan Sekarang
		 $endMonth = intval(explode('-', Carbon::now()->endOfMonth()->toDateString())[2]);

        for($i = 0 ; $i < $endMonth ; $i++){
           $dataDailyTemp[$i] = collect();
           $dataDailyTemp[$i]->tanggal = $i+1;
           $dataDailyTemp[$i]->totalRevenue = 0;
        }

        for($i = 0 ; $i < $endMonth ; $i++){
            for($j = 0 ; $j < $endMonth ; $j++){
                if(isset($dataDailyRJ[$j])){
                    if($dataDailyTemp[$i]->tanggal == $dataDailyRJ[$j]->tanggal){
                        $dataDailyTemp[$i]->totalRevenue = $dataDailyRJ[$j]->totalRevenue;
                        break;
                    }
                }
            }
        }
        return $dataDailyTemp;
	}

	public function balancingDataJumlahPasien($dataDailyRJ){
		$dataDailyTemp = collect();
		//Bulan Lalu
		// $endMonth =Carbon::now()->subMonth()->endOfMonth()->toDateString();
		
		//Bulan Sekarang
		 $endMonth = intval(explode('-', Carbon::now()->endOfMonth()->toDateString())[2]);

        for($i = 0 ; $i < $endMonth ; $i++){
           $dataDailyTemp[$i] = collect();
           $dataDailyTemp[$i]->tanggal = $i+1;
           $dataDailyTemp[$i]->jumlahPasien = 0;
        }

        for($i = 0 ; $i < $endMonth ; $i++){
            for($j = 0 ; $j < $endMonth ; $j++){
                if(isset($dataDailyRJ[$j])){
                    if($dataDailyTemp[$i]->tanggal == $dataDailyRJ[$j]->tanggal){
                        $dataDailyTemp[$i]->jumlahPasien = $dataDailyRJ[$j]->jumlahPasien;
                        break;
                    }
                }
            }
        }
        return $dataDailyTemp;
	}
}
