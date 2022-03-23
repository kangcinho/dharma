<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RevenueTransaksiDetail extends Model
{
    protected $guarded = ['id'];
    protected $table  = 'trRevenueTransaksiDetail';

    public function kategori(){
    	return $this->belongsTo('App\Kategori','idKategori');
    }

    public function revenueTransaksi(){
    	return $this->belongsTo('App\RevenueTransaksi','idTrRevenueTransaksi');
    }
}
