<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RevenueTransaksi extends Model
{
    protected $guarded = ['id'];
    protected $table  = 'trRevenueTransaksi';
    
    public function bulan(){
    	return $this->belongsTo('App\Bulan', 'idBulan');
    }

    public function revenue(){
    	return $this->belongsTo('App\Revenue', 'idTrRevenue');
    }

    public function revenueTransaksiDetail(){
    	return $this->hasMany('App\RevenueTransaksiDetail','idTrRevenueTransaksi');
    }
}
