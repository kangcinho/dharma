<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GroupKategori;

class GroupKategoriController extends Controller
{
	public function indexGroupKategori(){
    	$groupKategoris = GroupKategori::all();
    	return view('groupKategori.indexGroupKategori',compact('groupKategoris'));
    }

    public function formGroupKategori($id = null){
    	if($id == null){
    		return view('groupKategori.formGroupKategori');
    	}else{
    		$groupKategori = GroupKategori::whereId($id)->firstOrFail();
    		return view('groupKategori.formGroupKategori',compact('groupKategori'));
    	}
    }

    public function createGroupKategori(Request $request){
    	$groupKategori = new GroupKategori([
    		'namaGroupKategori' => $request->namaGroupKategori,
    	]);
    	$groupKategori->save();
    	$status = "Group Kategori: ".$groupKategori->namaGroupKategori." Berhasil disimpan!";
    	return redirect()->route('list group kategori')->with('status',$status);
    }

    public function editGroupKategori(Request $request, $id){
    	$groupKategori = GroupKategori::whereId($id)->firstOrFail();
    	$groupKategori->namaGroupKategori = $request->namaGroupKategori;
    	$groupKategori->save();
    	$status = "Group Kategori: ".$groupKategori->namaGroupKategori." Berhasil disimpan!";
    	return redirect()->route('list group kategori')->with('status',$status);
    }

    public function deleteGroupKategori($id){
    	$groupKategori = GroupKategori::whereId($id)->firstOrFail();
    	$status = "Group Kategori: ".$groupKategori->namaGroupKategori." Berhasil dihapus!";
    	$groupKategori->delete();
    	return redirect()->route('list group kategori')->with('status',$status);
    }
}
