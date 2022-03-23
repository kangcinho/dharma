<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $guarded = ['id'];
    protected $table  = 'mKategori';

    public function groupKategori(){
    	return $this->belongsTo('App\GroupKategori','idGroupKategori');
    }
}
