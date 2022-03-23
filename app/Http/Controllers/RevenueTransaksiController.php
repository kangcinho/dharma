<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RevenueTransaksi;
use App\RevenueTransaksiDetail;
use App\Revenue;
use App\KangCinHo\HelperUang;

class RevenueTransaksiController extends Controller
{
	public function indexRevenueList($id){
		$helperUang = new HelperUang();

		$revenueListPerTahuns = RevenueTransaksi::with('bulan')->where('idTrRevenue',$id)->get();
		foreach($revenueListPerTahuns as $revenueListPerTahun){
			$revenueListPerTahun->totalRevenue = $helperUang->tambahkanTitik($revenueListPerTahun->totalRevenue);
		}
		$revenueTahunBerjalan = Revenue::with('tahun')->whereId($id)->first();
		return view('revenueTransaksi.indexRevenueTransaksi', compact('revenueListPerTahuns','revenueTahunBerjalan'));
	}
}
