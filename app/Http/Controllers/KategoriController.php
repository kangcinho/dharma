<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kategori;
use App\GroupKategori;

class KategoriController extends Controller
{
    public function indexKategori(){
    	$kategoris = Kategori::with('groupKategori')->whereStatus('1')->get();
    	return view('kategori.indexKategori',compact('kategoris'));
    }

    public function formKategori($id = null){
    	$groupKategoris = GroupKategori::all();
    	if($id == null){
    		return view('kategori.formKategori',compact('groupKategoris'));
    	}else{
    		$kategori = Kategori::whereId($id)->firstOrFail();
    		return view('kategori.formKategori',compact('kategori','groupKategoris'));
    	}
    }

    public function createKategori(Request $request){
    	$kategori = new Kategori([
    		'namaKategori' => $request->namaKategori,
    		'status' => 1,
    		'idGroupKategori' => $request->idGroupKategori
    	]);
    	$kategori->save();
    	$status = "Kategori: ".$kategori->namaKategori." Berhasil disimpan!";
    	return redirect()->route('list kategori')->with('status',$status);
    }

    public function editKategori(Request $request, $id){
    	$kategori = Kategori::whereId($id)->firstOrFail();
    	$kategori->namaKategori = $request->namaKategori;
    	$kategori->status = $request->status;
    	$kategori->idGroupKategori = $request->idGroupKategori;
    	$kategori->save();
    	$status = "Kategori: ".$kategori->namaKategori." Berhasil disimpan!";
    	return redirect()->route('list kategori')->with('status',$status);
    }

    public function deleteKategori($id){
    	$kategori = Kategori::whereId($id)->firstOrFail();
    	$status = "Kategori: ".$kategori->namaKategori." Berhasil dihapus!";
    	$kategori->delete();
    	return redirect()->route('list kategori')->with('status',$status);
    }
}
