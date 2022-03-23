<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    protected $guarded = ['id'];
    protected $table  = 'trRevenue';

    public function tahun(){
    	return $this->belongsTo('App\Tahun','idTahun');
    }

    public function revenueTransaksi(){
    	return $this->hasMany('App\RevenueTransaksi','idTrRevenue');
    }
}
