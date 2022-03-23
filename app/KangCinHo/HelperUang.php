<?php

namespace App\KangCinHo;

class HelperUang{
 	public function tambahkanTitik($nilai){
		return number_format($nilai,0,',','.');
  }

  public function tambahkanKoma($nilai){
		return number_format($nilai,0,'.',',');
  }

  public function hilangkanTitik($nilai){
    return str_replace('.', '', $nilai);
  }
}
