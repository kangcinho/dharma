<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Revenue;
use App\Tahun;
use App\RevenueTransaksi;
use App\Bulan;
use App\Kategori;
use App\RevenueTransaksiDetail;
use App\KangCinHo\HelperUang;

class RevenueController extends Controller
{
    public function indexRevenue(){
        $this->cekTahunRevenue();
        $helperUang = new HelperUang();
    	$datas = Revenue::with('tahun')->get();
        foreach($datas as $data){
            $data->targetRevenue = $helperUang->tambahkanTitik($data->targetRevenue);
        }
    	return view('revenue.indexRevenue', compact('datas'));
    }

    public function formRevenue($id = null){
        $helperUang = new HelperUang();
        $tahuns = Tahun::all();
    	if($id == null){
    		//ADD FORM
    		return view('revenue.formRevenue', compact('tahuns'));
    	}else{
    		//EDIT FORM
    		$revenue = Revenue::whereId($id)->firstOrFail();
    		$revenue->minBatasMerah = $helperUang->tambahkanTitik($revenue->minBatasMerah);
			$revenue->maxBatasMerah = $helperUang->tambahkanTitik($revenue->maxBatasMerah);
			$revenue->minBatasKuning = $helperUang->tambahkanTitik($revenue->minBatasKuning);
			$revenue->maxBatasKuning = $helperUang->tambahkanTitik($revenue->maxBatasKuning);
			$revenue->minBatasHijau = $helperUang->tambahkanTitik($revenue->minBatasHijau);
			$revenue->maxBatasHijau = $helperUang->tambahkanTitik($revenue->maxBatasHijau);
            $revenue->targetRevenue = $helperUang->tambahkanTitik($revenue->targetRevenue);
            $revenue->budgetJanuari = $helperUang->tambahkanTitik($revenue->budgetJanuari);
            $revenue->budgetFebruari = $helperUang->tambahkanTitik($revenue->budgetFebruari);
            $revenue->budgetMaret = $helperUang->tambahkanTitik($revenue->budgetMaret);
            $revenue->budgetApril = $helperUang->tambahkanTitik($revenue->budgetApril);
            $revenue->budgetMei = $helperUang->tambahkanTitik($revenue->budgetMei);
            $revenue->budgetJuni = $helperUang->tambahkanTitik($revenue->budgetJuni);
            $revenue->budgetJuli = $helperUang->tambahkanTitik($revenue->budgetJuli);
            $revenue->budgetAgustus = $helperUang->tambahkanTitik($revenue->budgetAgustus);
            $revenue->budgetSeptember = $helperUang->tambahkanTitik($revenue->budgetSeptember);
            $revenue->budgetOktober = $helperUang->tambahkanTitik($revenue->budgetOktober);
            $revenue->budgetNovember = $helperUang->tambahkanTitik($revenue->budgetNovember);
            $revenue->budgetDesember = $helperUang->tambahkanTitik($revenue->budgetDesember);
    		return view('revenue.formRevenue', compact('revenue','tahuns'));
    	}
    }

    public function createRevenue(Request $request){
        $helperUang = new HelperUang();
    	$revenue = new Revenue([
    		'idTahun' => $request->idTahun,
    		'minBatasMerah' => $helperUang->hilangkanTitik($request->minBatasMerah),
    		'maxBatasMerah' => $helperUang->hilangkanTitik($request->maxBatasMerah),
    		'minBatasKuning' => $helperUang->hilangkanTitik($request->minBatasKuning),
    		'maxBatasKuning' => $helperUang->hilangkanTitik($request->maxBatasKuning),
    		'minBatasHijau' => $helperUang->hilangkanTitik($request->minBatasHijau),
    		'maxBatasHijau' => $helperUang->hilangkanTitik($request->maxBatasHijau),
            'targetRevenue' => $helperUang->hilangkanTitik($request->targetRevenue),
            'budgetJanuari' => $helperUang->hilangkanTitik($request->budgetJanuari),
            'budgetFebruari' => $helperUang->hilangkanTitik($request->budgetFebruari),
            'budgetMaret' => $helperUang->hilangkanTitik($request->budgetMaret),
            'budgetApril' => $helperUang->hilangkanTitik($request->budgetApril),
            'budgetMei' => $helperUang->hilangkanTitik($request->budgetMei),
            'budgetJuni' => $helperUang->hilangkanTitik($request->budgetJuni),
            'budgetJuli' => $helperUang->hilangkanTitik($request->budgetJuli),
            'budgetAgustus' => $helperUang->hilangkanTitik($request->budgetAgustus),
            'budgetSeptember' => $helperUang->hilangkanTitik($request->budgetSeptember),
            'budgetOktober' => $helperUang->hilangkanTitik($request->budgetOktober),
            'budgetNovember' => $helperUang->hilangkanTitik($request->budgetNovember),
    		'budgetDesember' => $helperUang->hilangkanTitik($request->budgetDesember),
    	]);
    	$revenue->save();

    	$this->createDetailRevenueTransaksi($revenue);

    	$status = "Data Revenu Tahun ".$revenue->idTahun." Berhasil Disimpan!";
    	return redirect()->route('revenue')->with('status', $status);
    }

    public function createDetailRevenueTransaksi($revenue){
        $bulans = Bulan::all();
        $kategoris = Kategori::where('addBySystem',1)->get();
        //harus diperhemat!!!!
        foreach($bulans as $bulan){
            $revenueTransaksi = new RevenueTransaksi([
                'idTrRevenue' => $revenue->id,
                'idBulan' => $bulan->id
            ]);
            $revenueTransaksi->save();
            foreach($kategoris as $kategori){
                $this->createDetailKategoriRevenueTransaksi($revenueTransaksi->id, $kategori);
            }
        }

        // foreach($bulans as $bulan){

        // }
        // $revenueTransaksi = new RevenueTransaksi([
        //     'idTrRevenue' => $revenue->id,
        //     'idBulan' => $bulan->id
        // ]);
        // $revenueTransaksi->save();

    }

    public function createDetailKategoriRevenueTransaksi($revenueTransaksiId, $kategori){
        $revenueDetailTransaksi = new RevenueTransaksiDetail([
            'idTrRevenueTransaksi' => $revenueTransaksiId,
            'idKategori' => $kategori->id,
            'idSyncToSanata' => $kategori->idSyncToSanata,
            'isKamar' =>$kategori->isKamar,
            'isPasien' =>$kategori->isPasien,
            'isPasienRepeater' =>$kategori->isPasienRepeater,
            'isPaket' =>$kategori->isPaket,
            'isSection' =>$kategori->isSection
        ]);
        $revenueDetailTransaksi->save();
    }

    public function editRevenue(Request $request, $id){
        $helperUang = new HelperUang();
    	$revenue = Revenue::whereId($id)->firstOrFail();
    	$revenue->idTahun = $request->idTahun;
  		$revenue->minBatasMerah = $helperUang->hilangkanTitik($request->minBatasMerah);
  		$revenue->maxBatasMerah = $helperUang->hilangkanTitik($request->maxBatasMerah);
  		$revenue->minBatasKuning = $helperUang->hilangkanTitik($request->minBatasKuning);
  		$revenue->maxBatasKuning = $helperUang->hilangkanTitik($request->maxBatasKuning);
  		$revenue->minBatasHijau = $helperUang->hilangkanTitik($request->minBatasHijau);
  		$revenue->maxBatasHijau = $helperUang->hilangkanTitik($request->maxBatasHijau);
  		$revenue->targetRevenue = $helperUang->hilangkanTitik($request->targetRevenue);
        $revenue->budgetJanuari = $helperUang->hilangkanTitik($request->budgetJanuari);
        $revenue->budgetFebruari = $helperUang->hilangkanTitik($request->budgetFebruari);
        $revenue->budgetMaret = $helperUang->hilangkanTitik($request->budgetMaret);
        $revenue->budgetApril = $helperUang->hilangkanTitik($request->budgetApril);
        $revenue->budgetMei = $helperUang->hilangkanTitik($request->budgetMei);
        $revenue->budgetJuni = $helperUang->hilangkanTitik($request->budgetJuni);
        $revenue->budgetJuli = $helperUang->hilangkanTitik($request->budgetJuli);
        $revenue->budgetAgustus = $helperUang->hilangkanTitik($request->budgetAgustus);
        $revenue->budgetSeptember = $helperUang->hilangkanTitik($request->budgetSeptember);
        $revenue->budgetOktober = $helperUang->hilangkanTitik($request->budgetOktober);
        $revenue->budgetNovember = $helperUang->hilangkanTitik($request->budgetNovember);
        $revenue->budgetDesember = $helperUang->hilangkanTitik($request->budgetDesember);
  		$revenue->save();

  		$status = "Data Revenu Tahun ".$revenue->idTahun." Berhasil Disimpan!";
    	return redirect()->route('revenue')->with('status', $status);
    }

    public function deleteRevenue(){
    	$revenue = Revenue::whereId($id)->firstOrFail();
    	$status = "Data Revenu Tahun ".$revenue->idTahun." Berhasil Disimpan!";
    	$revenue->delete();
    	return redirect()->route('revenue')->with('status', $status);
    }

    public function cekTahunRevenue(){
      $tahunNow = \Carbon\Carbon::now()->year;
      $tahunDb = Tahun::where('tahun',$tahunNow)->first();
      if($tahunDb == null){
        $this->setStatusTahunKeNol();
        for($i=($tahunNow-2); $i<=$tahunNow; $i++){
            $tahunDb = Tahun::where('tahun',$i)->first();
            if($tahunDb == null){
                if($i == $tahunNow){
                    $this->createTahun($i,1);
                }else{
                    $this->createTahun($i,0);
                }
            }
        }
      }
    }

    public function createTahun($tahun, $status){
      $createTahun = new Tahun([
          'tahun' => $tahun,
          'status' => $status
      ]);
      $createTahun->save();
    }

    public function setStatusTahunKeNol(){
      $tahuns = Tahun::all();
      foreach($tahuns as $tahun){
        $tahun->status = 0;
        $tahun->save();
      }
    }
}
