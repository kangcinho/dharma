<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RevenueTransaksiDetail;
use App\RevenueTransaksi;
use App\Kategori;
use App\KangCinHo\HelperUang;

class RevenueTransaksiDetailController extends Controller
{
	public function indexRevenueTransaksiDetail($id){
		$helperUang = new HelperUang();
		$revenueTransaksiDetailBulanans = RevenueTransaksiDetail::with('kategori','kategori.groupKategori')->where('idTrRevenueTransaksi',$id)->orderBy('jumlahPasien','desc')->get();
		// dd($revenueTransaksiDetailBulanans);
		foreach($revenueTransaksiDetailBulanans as $revenueTransaksiDetailBulanan){
			$revenueTransaksiDetailBulanan->totalRevenue = $helperUang->tambahkanTitik($revenueTransaksiDetailBulanan->totalRevenue);
			$revenueTransaksiDetailBulanan->jumlahPasien = $helperUang->tambahkanTitik($revenueTransaksiDetailBulanan->jumlahPasien);
		}
		$tahunRevenue = RevenueTransaksi::with('revenue.tahun')->where('id',$id)->first();
		$bulanRevenue = RevenueTransaksi::with('bulan')->where('id',$id)->first();
		$idRevenue = RevenueTransaksi::where('id',$id)->first();
		// dd($revenueTransaksiDetailBulanans);
		return view('revenueTransaksiDetail.indexRevenueTransaksiDetailList',compact('revenueTransaksiDetailBulanans','tahunRevenue', 'bulanRevenue','idRevenue', 'id'));
	}

	public function formCreateRevenueTransaksiDetail($id){
		$tahunRevenue = RevenueTransaksi::with('revenue.tahun')->where('id',$id)->first();
		$bulanRevenue = RevenueTransaksi::with('bulan')->where('id',$id)->first();
		$kategoris = Kategori::where('status', 1)->where('addBySystem',0)->get();
		return view('revenueTransaksiDetail.formRevenueTransaksiDetail', compact('kategoris','id','tahunRevenue','bulanRevenue'));
	}

	public function createRevenueTransaksiDetail($id, Request $request){
		$helperUang = new HelperUang();
		$kategori = Kategori::where('id', $request->idKategori)->firstOrFail();
		$revenueTransaskiDetail = new RevenueTransaksiDetail([
			'idTrRevenueTransaksi' => $id,
			'idKategori' => $request->idKategori,
			'totalRevenue' => $helperUang->hilangkanTitik($request->totalRevenue),
			'idSyncToSanata' => $kategori->idSyncToSanata,
			'isKamar' => $kategori->isKamar,
			'isSection' => $kategori->isSection,
			'isPasien' => $kategori->isPasien,
		]);
		$revenueTransaskiDetail->save();

		return redirect()->route('revenue list perbulan',$id);
	}

	public function formEditRevenueTransaksiDetail($id,$idx){
		$helperUang = new HelperUang();
		$tahunRevenue = '';
		$bulanRevenue = '';
		$kategoris = Kategori::where('status', 1)->get();
		$revenueTransaksiDetail = RevenueTransaksiDetail::where('id',$id)->firstOrFail();
		$revenueTransaksiDetail->totalRevenue = $helperUang->tambahkanTitik($revenueTransaksiDetail->totalRevenue);
		if($idx != null){
			$tahunRevenue = RevenueTransaksi::with('revenue.tahun')->where('id',$idx)->first();
			$bulanRevenue = RevenueTransaksi::with('bulan')->where('id',$idx)->first();
		}
		return view('revenueTransaksiDetail.formRevenueTransaksiDetailEdit', compact('kategoris','id','idx','tahunRevenue','bulanRevenue','revenueTransaksiDetail' ));
	}

	public function updateRevenueTransaksiDetail($id,$idx, Request $request){
		$helperUang = new HelperUang();
		$kategori = Kategori::where('id', $request->idKategori)->firstOrFail();
		$revenueTransaksiDetail = RevenueTransaksiDetail::where('id',$id)->firstOrFail();
		$revenueTransaksiDetail->idKategori = $request->idKategori;
		$revenueTransaksiDetail->idSyncToSanata = $kategori->idSyncToSanata;
		$revenueTransaksiDetail->isKamar = $kategori->isKamar;
		$revenueTransaksiDetail->isSection = $kategori->isSection;
		$revenueTransaksiDetail->isPasien = $kategori->isPasien;
		$revenueTransaksiDetail->totalRevenue = $helperUang->hilangkanTitik($request->totalRevenue);
		$revenueTransaksiDetail->save();

		return redirect()->route('revenue list perbulan',$idx);
	}

	public function deleteRevenueTransaksiDetail($id){
		$revenueTransaksiDetail = RevenueTransaksiDetail::where('id',$id)->firstOrFail();
		$revenueTransaksiDetail->delete();
		return redirect()->back();
	}
}
