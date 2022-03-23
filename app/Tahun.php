<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tahun extends Model
{
    protected $guarded = ['id'];
    protected $table  = 'mTahun';

    public function revenue(){
    	return $this->hasMany('App\Revenue','idTahun');
    }
}
