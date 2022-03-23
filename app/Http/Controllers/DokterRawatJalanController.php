<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Kategori;
use App\KangCinHo\HelperTanggal;

class DokterRawatJalanController extends Controller
{
    public function dashboard($sectionID){
    	$helperTanggal = new HelperTanggal();
    	$kategori = Kategori::where('idSyncToSanata', $sectionID)->first();
    	$dataDokterRawatJalans = $this->getDokterRawatJalan($sectionID);
    	$tanggalSekarang = explode(' ',\Carbon\Carbon::now()->toDateTimeString())[0];
		$tanggalSekarang = $helperTanggal->tanggalRangeWithBulanTahun($tanggalSekarang);

    	return view('menu.rawatJalan.dokterRawatJalan.indexDokterRawatJalan', compact('kategori', 'dataDokterRawatJalans','tanggalSekarang'));
    }

    public function getDokterRawatJalan($sectionID){
		// select DokterID, mSupplier.Nama_Supplier, COUNT(dokterID) as jumlahPasien from SIMtrKasir
		// inner join mSupplier on  SIMtrKasir.DokterID = mSupplier.Kode_Supplier
		// where Batal = 0 
		// and YEAR(tanggal) = 2019
		// and MONTH(tanggal)  = 7
		// and SectionPerawatanID = 'SEC009'
		// group by DokterID, mSupplier.Nama_Supplier
		// order by jumlahPasien desc
    	$bulanSekarang = \Carbon\Carbon::now()->month;
        $tahunSekarang = \Carbon\Carbon::now()->year;
		$dataDokterRawatJalan = \DB::connection('sqlsrv')->table('SIMtrKasir')
				->selectRaw('DokterID, mSupplier.Nama_Supplier as namaDokter, COUNT(dokterID) as jumlahPasien')
				->join('mSupplier', 'SIMtrKasir.DokterID', 'mSupplier.Kode_Supplier')
				->where('batal', 0)
				->whereYear('Tanggal', $tahunSekarang)
				->whereMonth('Tanggal', $bulanSekarang)
				->where('SectionPerawatanID', $sectionID)
				->groupBy(['DokterID', 'mSupplier.Nama_Supplier'])
				->orderBy('jumlahPasien', 'desc')
				->get();

		return $dataDokterRawatJalan;
    }
}
