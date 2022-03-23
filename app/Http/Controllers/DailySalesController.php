<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\KangCinHo\HelperTanggal;
use App\KangCinHo\HelperUang;
class DailySalesController extends Controller
{
    public function dashboard(){
    	return view('menu.dailySales.indexDailySales');
    }

    public function calculate(Request $request){
        $uang = new HelperUang();
        $tanggal = new HelperTanggal();
        $tglAkhirActualPeriode = $request->lastPeriode;
        $tglAkhirActualPeriodePecah = explode('-', $tglAkhirActualPeriode);
        
        $tglAwalActualPeriode = $tglAkhirActualPeriodePecah[0].'-'.$tglAkhirActualPeriodePecah[1].'-1';
        $tglAwalLastMonthPeriode = $tglAkhirActualPeriodePecah[0].'-'.($tglAkhirActualPeriodePecah[1]-1).'-1';
        $tglAkhirLastMonthPeriode = $tglAkhirActualPeriodePecah[0].'-'.($tglAkhirActualPeriodePecah[1]-1).'-'.$tglAkhirActualPeriodePecah[2];
        $tglAwalLastYear = ($tglAkhirActualPeriodePecah[0]-1).'-'.$tglAkhirActualPeriodePecah[1].'-1';
        $tglAkhirLastYear = ($tglAkhirActualPeriodePecah[0]-1).'-'.$tglAkhirActualPeriodePecah[1].'-'.$tglAkhirActualPeriodePecah[2];

        $akunRawatDarurat = '40101';
        $akunRawatJalan = '40102';
        $akunRawatInap = '40103';
        $akunVoucher = '40100';
        $akunCafetaria = '4010403';
        $akunLainLain = '40199';
        $akunDiskonPenjualan = '40105';
        $akunNCC = '403';
        $jasaSC = ['JAS00011', 'JAS00012','JAS01231','JAS01232', 'JAS01484'];
        $jasaNormal = ['JAS00008'];
        $jasaPatalogi = ['JAS00009','JAS00010','JAS01233', 'JAS01234', 'JAS01235', 'JAS01236'];
        $jasaVoucher = ['JAS01547', 'JAS01548', 'JAS01549', 'JAS01550','JAS01551','JAS01552','JAS01553','JAS01554','JAS01555','JAS01556','JAS01557','JAS01558','JAS01559','JAS01560','JAS01561','JAS01562','JAS01563','JAS01564','JAS01565','JAS01566','JAS01567','JAS01568','JAS01569','JAS01570','JAS01578','JAS01579','JAS01580','JAS01581','JAS01582','JAS01583','JAS01584','JAS01585','JAS01586','JAS01587'];
        $jasaIUI = ['JAS00025'];
        $jasaGynekologi = ['JAS00019', 'JAS00020', 'JAS00021', 'JAS00022', 'JAS01147', 'JAS01148', 'JAS01149', 'JAS01150'];
        $jasaLaparascopyAnak = ['JAS01097'];
        $jasaLaparascopyOperatif = ['JAS00015','JAS00016','JAS00017'];
        $jasaTindakanOperasi = ['JAS00013'];
        $jasaHisterectomi = ['JAS00192'];
        $jasaTHTAnak = ['JAS00193'];
        $jasaET = ['JAS01312', 'JAS01372', 'JAS01459', 'JAS01452'];
        $jasaAminioInFusion = ['JAS01400'];
        $jasaBedahAnak = ['JAS00028'];

        $sectionObgyn = ['SEC008'];
        $sectionWIN = ['SECT0019'];
        $sectionVidastana = ['SECT0022'];
        $sectionAnak = ['SEC009'];
        $sectionIGD = ['SEC002'];
        $sectionUmum = ['SEC007'];

        $revenueWIN = $this->getDataRevenueSection($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $sectionWIN);
        $revenueVidastana = $this->getDataRevenueSection($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $sectionVidastana);

        $revenueRawatDarurat = $this->getDataDailySales($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $akunRawatDarurat);
        $revenueRawatJalan =  $this->getDataDailySales($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $akunRawatJalan);
        $revenueRawatInap =  $this->getDataDailySales($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $akunRawatInap);
        $revenueVoucher =  $this->getDataDailySales($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $akunVoucher);
        $revenueCafe =  $this->getDataDailySales($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $akunCafetaria);
        $revenueLainLain = $this->getDataDailySales($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $akunLainLain);
        $revenueDiskonPenjualan =  $this->getDataDailySales($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $akunDiskonPenjualan);
        $revenueNCC = $this->getDataDailySales($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $akunNCC);
        
        //Merger Data Diskon + NCC
        $revenueDiskonPenjualan = $this->mergerDataRevenue($revenueDiskonPenjualan, $revenueNCC);

        $revenuePersalinanSC = $this->getDataRevenuePersalinan($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaSC);
        $revenuePersalinanNormal = $this->getDataRevenuePersalinan($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaNormal);
        $revenuePersalinanPatalogi = $this->getDataRevenuePersalinan($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaPatalogi);

        $jumlahPasienSC =$this->getDataJumlahJasaTindakan($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaSC);
        $jumlahPasienNormal =$this->getDataJumlahJasaTindakan($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaNormal);
        $jumlahPasienPatalogi =$this->getDataJumlahJasaTindakan($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaPatalogi);
        $jumlahPasienVoucher = $this->getDataVoucher($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaVoucher);
        $jumlahPasienIUI =$this->getDataJumlahJasaTindakan($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaIUI);
        $jumlahPasienGynekologi =$this->getDataJumlahJasaTindakan($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaGynekologi);
        $jumlahPasienLaparascopyAnak =$this->getDataJumlahJasaTindakan($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaLaparascopyAnak);
        $jumlahPasienLaparascopyOperatif =$this->getDataJumlahJasaTindakan($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaLaparascopyOperatif);
        $jumlahPasienTindakanOperasi =$this->getDataJumlahJasaTindakan($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaTindakanOperasi);
        $jumlahPasienHisterectomi =$this->getDataJumlahJasaTindakan($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaHisterectomi);
        $jumlahPasienTHTAnak =$this->getDataJumlahJasaTindakan($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaTHTAnak);
        $jumlahPasienET =$this->getDataJumlahJasaTindakan($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaET);
        $jumlahPasienAminioInFusion =$this->getDataJumlahJasaTindakan($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaAminioInFusion);
        $jumlahPasienBedahAnak =$this->getDataJumlahJasaTindakan($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaBedahAnak);

        $jumlahPasienPoliObgyn =$this->getDataRevenueSectionPoli($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $sectionObgyn);
        $jumlahPasienPoliWIN =$this->getDataRevenueSectionPoli($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $sectionWIN);
        $jumlahPasienPoliVidastana =$this->getDataRevenueSectionPoli($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $sectionVidastana);
        $jumlahPasienPoliAnak =$this->getDataRevenueSectionPoli($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $sectionAnak);
        $jumlahPasienPoliUGD =$this->getDataRevenueSectionPoli($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $sectionIGD);
        $jumlahPasienPoliUmum =$this->getDataRevenueSectionPoli($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $sectionUmum);

        $revenueTotal = collect();
        $revenueTotal->toDayActual = $uang->tambahkanKoma($revenueRawatDarurat->toDayActual[0]->totalRevenue + $revenueRawatJalan->toDayActual[0]->totalRevenue + $revenueRawatInap->toDayActual[0]->totalRevenue + $revenueVoucher->toDayActual[0]->totalRevenue + $revenueCafe->toDayActual[0]->totalRevenue + $revenueLainLain->toDayActual[0]->totalRevenue + $revenueDiskonPenjualan->toDayActual[0]->totalRevenue);
        $revenueTotal->toDateActual = $uang->tambahkanKoma($revenueRawatDarurat->toDateActual[0]->totalRevenue + $revenueRawatJalan->toDateActual[0]->totalRevenue + $revenueRawatInap->toDateActual[0]->totalRevenue + $revenueVoucher->toDateActual[0]->totalRevenue + $revenueCafe->toDateActual[0]->totalRevenue + $revenueLainLain->toDateActual[0]->totalRevenue + $revenueDiskonPenjualan->toDateActual[0]->totalRevenue);
        $revenueTotal->toDayLastMonth = $uang->tambahkanKoma($revenueRawatDarurat->toDayLastMonth[0]->totalRevenue + $revenueRawatJalan->toDayLastMonth[0]->totalRevenue + $revenueRawatInap->toDayLastMonth[0]->totalRevenue + $revenueVoucher->toDayLastMonth[0]->totalRevenue + $revenueCafe->toDayLastMonth[0]->totalRevenue + $revenueLainLain->toDayLastMonth[0]->totalRevenue + $revenueDiskonPenjualan->toDayLastMonth[0]->totalRevenue);
        $revenueTotal->toDateLastMonth = $uang->tambahkanKoma($revenueRawatDarurat->toDateLastMonth[0]->totalRevenue + $revenueRawatJalan->toDateLastMonth[0]->totalRevenue + $revenueRawatInap->toDateLastMonth[0]->totalRevenue + $revenueVoucher->toDateLastMonth[0]->totalRevenue + $revenueCafe->toDateLastMonth[0]->totalRevenue + $revenueLainLain->toDateLastMonth[0]->totalRevenue + $revenueDiskonPenjualan->toDateLastMonth[0]->totalRevenue);
        $revenueTotal->toDateLastYear = $uang->tambahkanKoma($revenueRawatDarurat->toDateLastYear[0]->totalRevenue + $revenueRawatJalan->toDateLastYear[0]->totalRevenue + $revenueRawatInap->toDateLastYear[0]->totalRevenue + $revenueVoucher->toDateLastYear[0]->totalRevenue + $revenueCafe->toDateLastYear[0]->totalRevenue + $revenueLainLain->toDateLastYear[0]->totalRevenue + $revenueDiskonPenjualan->toDateLastYear[0]->totalRevenue);
        
        $revenuePersalinanTotal = collect();
        $revenuePersalinanTotal->toDayActual = $uang->tambahkanKoma($revenuePersalinanSC->toDayActual[0]->totalRevenue + $revenuePersalinanNormal->toDayActual[0]->totalRevenue + $revenuePersalinanPatalogi->toDayActual[0]->totalRevenue + $revenueVoucher->toDayActual[0]->totalRevenue );
        $revenuePersalinanTotal->toDateActual = $uang->tambahkanKoma($revenuePersalinanSC->toDateActual[0]->totalRevenue + $revenuePersalinanNormal->toDateActual[0]->totalRevenue + $revenuePersalinanPatalogi->toDateActual[0]->totalRevenue + $revenueVoucher->toDateActual[0]->totalRevenue );
        $revenuePersalinanTotal->toDayLastMonth = $uang->tambahkanKoma($revenuePersalinanSC->toDayLastMonth[0]->totalRevenue + $revenuePersalinanNormal->toDayLastMonth[0]->totalRevenue + $revenuePersalinanPatalogi->toDayLastMonth[0]->totalRevenue + $revenueVoucher->toDayLastMonth[0]->totalRevenue );
        $revenuePersalinanTotal->toDateLastMonth = $uang->tambahkanKoma($revenuePersalinanSC->toDateLastMonth[0]->totalRevenue + $revenuePersalinanNormal->toDateLastMonth[0]->totalRevenue + $revenuePersalinanPatalogi->toDateLastMonth[0]->totalRevenue + $revenueVoucher->toDateLastMonth[0]->totalRevenue );
        $revenuePersalinanTotal->toDateLastYear = $uang->tambahkanKoma($revenuePersalinanSC->toDateLastYear[0]->totalRevenue + $revenuePersalinanNormal->toDateLastYear[0]->totalRevenue + $revenuePersalinanPatalogi->toDateLastYear[0]->totalRevenue + $revenueVoucher->toDateLastYear[0]->totalRevenue );
     
        $tglToDayActual = $tanggal->tanggalBaca($tglAkhirActualPeriode);
        $tglToDateActual = $tanggal->tanggalRangeWithBulanTahun($tglAkhirActualPeriode);
        $tglToDayLastMonth = $tanggal->tanggalBaca($tglAkhirLastMonthPeriode);
        $tglToDateLastMonth = $tanggal->tanggalRangeWithBulanTahun($tglAkhirLastMonthPeriode);
        $tglToDateLastYear = $tanggal->tanggalRangeWithBulanTahun($tglAkhirLastYear);

        $revenueRawatDarurat = $this->tambahkanTitik($revenueRawatDarurat);
        $revenueRawatJalan = $this->tambahkanTitik($revenueRawatJalan);
        $revenueRawatInap = $this->tambahkanTitik($revenueRawatInap);
        $revenueVoucher = $this->tambahkanTitik($revenueVoucher);
        $revenueCafe = $this->tambahkanTitik($revenueCafe);
        $revenueLainLain = $this->tambahkanTitik($revenueLainLain);
        $revenueDiskonPenjualan = $this->tambahkanTitik($revenueDiskonPenjualan);
        $revenuePersalinanSC = $this->tambahkanTitik($revenuePersalinanSC);
        $revenuePersalinanNormal = $this->tambahkanTitik($revenuePersalinanNormal);
        $revenuePersalinanPatalogi =$this->tambahkanTitik($revenuePersalinanPatalogi);
        $revenueWIN = $this->tambahkanTitik($revenueWIN);
        $revenueVidastana =$this->tambahkanTitik($revenueVidastana);

        $jumlahPasienSC = $this->tambahkanTitikJumlahPasien($jumlahPasienSC);
        $jumlahPasienNormal = $this->tambahkanTitikJumlahPasien($jumlahPasienNormal);
        $jumlahPasienPatalogi = $this->tambahkanTitikJumlahPasien($jumlahPasienPatalogi);
        $jumlahPasienVoucher =$this->tambahkanTitikJumlahPasien($jumlahPasienVoucher);
        $jumlahPasienIUI =$this->tambahkanTitikJumlahPasien($jumlahPasienIUI);
        $jumlahPasienGynekologi =$this->tambahkanTitikJumlahPasien($jumlahPasienGynekologi);
        $jumlahPasienLaparascopyAnak =$this->tambahkanTitikJumlahPasien($jumlahPasienLaparascopyAnak);
        $jumlahPasienLaparascopyOperatif =$this->tambahkanTitikJumlahPasien($jumlahPasienLaparascopyOperatif);
        $jumlahPasienTindakanOperasi =$this->tambahkanTitikJumlahPasien($jumlahPasienTindakanOperasi);
        $jumlahPasienHisterectomi =$this->tambahkanTitikJumlahPasien($jumlahPasienHisterectomi);
        $jumlahPasienTHTAnak =$this->tambahkanTitikJumlahPasien($jumlahPasienTHTAnak);
        $jumlahPasienET =$this->tambahkanTitikJumlahPasien($jumlahPasienET);
        $jumlahPasienAminioInFusion =$this->tambahkanTitikJumlahPasien($jumlahPasienAminioInFusion);
        $jumlahPasienBedahAnak =$this->tambahkanTitikJumlahPasien($jumlahPasienBedahAnak);
        $jumlahPasienPoliObgyn =$this->tambahkanTitikJumlahPasien($jumlahPasienPoliObgyn);
        $jumlahPasienPoliWIN =$this->tambahkanTitikJumlahPasien($jumlahPasienPoliWIN);
        $jumlahPasienPoliVidastana =$this->tambahkanTitikJumlahPasien($jumlahPasienPoliVidastana);
        $jumlahPasienPoliAnak =$this->tambahkanTitikJumlahPasien($jumlahPasienPoliAnak);
        $jumlahPasienPoliUGD =$this->tambahkanTitikJumlahPasien($jumlahPasienPoliUGD);
        $jumlahPasienPoliUmum =$this->tambahkanTitikJumlahPasien($jumlahPasienPoliUmum);

        return view('menu.dailySales.indexDailySales', compact('tglAkhirActualPeriode',
            'revenueRawatDarurat', 
            'revenueRawatJalan', 
            'revenueRawatInap', 
            'revenueVoucher', 
            'revenueCafe', 
            'revenueLainLain', 
            'revenueDiskonPenjualan', 
            'revenueTotal', 
            'tglToDayActual', 
            'tglToDateActual', 
            'tglToDayLastMonth',
            'tglToDateLastMonth',
            'tglToDateLastYear',
            'revenuePersalinanSC',
            'revenuePersalinanNormal',
            'revenuePersalinanPatalogi',
            'revenuePersalinanTotal',
            'revenueWIN',
            'revenueVidastana',
            'jumlahPasienSC',
            'jumlahPasienNormal',
            'jumlahPasienPatalogi',
            'jumlahPasienVoucher',
            'jumlahPasienIUI',
            'jumlahPasienGynekologi',
            'jumlahPasienLaparascopyAnak',
            'jumlahPasienLaparascopyOperatif',
            'jumlahPasienTindakanOperasi',
            'jumlahPasienHisterectomi',
            'jumlahPasienTHTAnak',
            'jumlahPasienET',
            'jumlahPasienAminioInFusion',
            'jumlahPasienBedahAnak',
            'jumlahPasienPoliObgyn',
            'jumlahPasienPoliWIN',
            'jumlahPasienPoliVidastana',
            'jumlahPasienPoliAnak',
            'jumlahPasienPoliUGD',
            'jumlahPasienPoliUmum'
        ));
    }

    public function getDataDailySales($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $akun){
        $revenue = collect();
        $revenue->toDayActual = $this->getDataDailySalesToSanata($tglAkhirActualPeriode, $tglAkhirActualPeriode, $akun);
        $revenue->toDateActual = $this->getDataDailySalesToSanata($tglAwalActualPeriode, $tglAkhirActualPeriode, $akun);
        $revenue->toDayLastMonth = $this->getDataDailySalesToSanata($tglAkhirLastMonthPeriode, $tglAkhirLastMonthPeriode, $akun);
        $revenue->toDateLastMonth = $this->getDataDailySalesToSanata($tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $akun);
        $revenue->toDateLastYear = $this->getDataDailySalesToSanata($tglAwalLastYear, $tglAkhirLastYear, $akun);
        return $revenue;
    }

    public function getDataDailySalesToSanata($tglAwalPeriode, $tglAkhirPeriode, $akun){
        $dataRevenues = \DB::connection('sqlsrvbo')
        ->table('TBJ_Transaksi_Detail')
        ->selectRaw('(SUM(TBJ_Transaksi_Detail.Kredit) - SUM(TBJ_Transaksi_Detail.Debit)) as totalRevenue')
        ->join('TBJ_Transaksi', 'TBJ_Transaksi.No_Bukti','TBJ_Transaksi_Detail.No_Bukti')
        ->whereIn('Akun_ID', function($query) use ($akun) {
          $query->select('Akun_ID')->from('Mst_Akun')->where('Group_ID', 4)->where('Aktif',1)->where('Akun_No', 'like', "%$akun%");
        })
        ->where('Transaksi_Date','>=',$tglAwalPeriode)
        ->where('Transaksi_Date','<=',$tglAkhirPeriode)
        ->get();
        return $dataRevenues;
    }

    public function getDataJumlahJasaTindakan($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaID){
        $revenue = collect();
        $revenue->toDayActual = $this->getDataJumlahJasaTindakanToSanata($tglAkhirActualPeriode, $tglAkhirActualPeriode, $jasaID);
        $revenue->toDateActual = $this->getDataJumlahJasaTindakanToSanata($tglAwalActualPeriode, $tglAkhirActualPeriode, $jasaID);
        $revenue->toDayLastMonth = $this->getDataJumlahJasaTindakanToSanata($tglAkhirLastMonthPeriode, $tglAkhirLastMonthPeriode, $jasaID);
        $revenue->toDateLastMonth = $this->getDataJumlahJasaTindakanToSanata($tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $jasaID);
        $revenue->toDateLastYear = $this->getDataJumlahJasaTindakanToSanata($tglAwalLastYear, $tglAkhirLastYear, $jasaID);
        return $revenue;
    }
    
    public function getDataJumlahJasaTindakanToSanata($tglAwalPeriode, $tglAkhirPeriode, $jasaIDPersalinan){
        $dataRevenues = \DB::connection('sqlsrv')
        ->table('SIMtrKasir')
        ->selectRaw('COUNT(distinct SIMtrRJ.RegNo) as jumlahPasien')
        ->join('SIMtrRJ', 'SIMtrRJ.RegNo','SIMtrKasir.NoReg')
        ->join('SIMtrRJTransaksi', 'SIMtrRJTransaksi.NoBukti','SIMtrRJ.NoBukti')
        ->whereIn('SIMtrRJTransaksi.JasaID', $jasaIDPersalinan)
        ->where('SIMtrKasir.Tanggal','>=',$tglAwalPeriode)
        ->where('SIMtrKasir.Tanggal','<=',$tglAkhirPeriode)
        ->where('SIMtrKasir.Batal',0)
        ->where('SIMtrRJ.Batal',0)
        ->get();
        return $dataRevenues;
    }

    public function getDataRevenuePersalinan($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaID){
        $revenue = collect();
        $revenue->toDayActual = $this->getDataRevenuePersalinanToSanata($tglAkhirActualPeriode, $tglAkhirActualPeriode, $jasaID);
        $revenue->toDateActual = $this->getDataRevenuePersalinanToSanata($tglAwalActualPeriode, $tglAkhirActualPeriode, $jasaID);
        $revenue->toDayLastMonth = $this->getDataRevenuePersalinanToSanata($tglAkhirLastMonthPeriode, $tglAkhirLastMonthPeriode, $jasaID);
        $revenue->toDateLastMonth = $this->getDataRevenuePersalinanToSanata($tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $jasaID);
        $revenue->toDateLastYear = $this->getDataRevenuePersalinanToSanata($tglAwalLastYear, $tglAkhirLastYear, $jasaID);
        return $revenue;
    }
    
    public function getDataRevenuePersalinanToSanata($tglAwalPeriode, $tglAkhirPeriode, $jasaIDPersalinan){
        $dataRevenues = \DB::connection('sqlsrv')
        ->table('SIMtrKasir')
        ->selectRaw('SUM(coalesce(Nilai,0)+ coalesce(NilaiInvoiceGabung,0)+ coalesce(NilaiInvoiceGabung2,0)+ coalesce(NilaiInvoiceGabung3,0)) - sum(NilaiDiscount) as totalRevenue')
        ->join('SIMtrRJ', 'SIMtrRJ.RegNo','SIMtrKasir.NoReg')
        ->join('SIMtrRJTransaksi', 'SIMtrRJTransaksi.NoBukti','SIMtrRJ.NoBukti')
        ->whereIn('SIMtrRJTransaksi.JasaID', $jasaIDPersalinan)
        ->where('SIMtrKasir.Tanggal','>=',$tglAwalPeriode)
        ->where('SIMtrKasir.Tanggal','<=',$tglAkhirPeriode)
        ->where('SIMtrKasir.Batal',0)
        ->where('SIMtrRJ.Batal',0)
        ->get();
        return $dataRevenues;
    }

    public function getDataVoucher($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $jasaID){
        $revenue = collect();
        $revenue->toDayActual = $this->getDataVoucherToSanata($tglAkhirActualPeriode, $tglAkhirActualPeriode, $jasaID);
        $revenue->toDateActual = $this->getDataVoucherToSanata($tglAwalActualPeriode, $tglAkhirActualPeriode, $jasaID);
        $revenue->toDayLastMonth = $this->getDataVoucherToSanata($tglAkhirLastMonthPeriode, $tglAkhirLastMonthPeriode, $jasaID);
        $revenue->toDateLastMonth = $this->getDataVoucherToSanata($tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $jasaID);
        $revenue->toDateLastYear = $this->getDataVoucherToSanata($tglAwalLastYear, $tglAkhirLastYear, $jasaID);
        return $revenue;
    }
    
    public function getDataVoucherToSanata($tglAwalPeriode, $tglAkhirPeriode, $jasaIDVoucher){
        $dataRevenues = \DB::connection('sqlsrv')
        ->table('SIMtrKasir')
        ->selectRaw('COUNT(*) as jumlahPasien')
        ->join('SIMtrRegistrasi', 'SIMtrRegistrasi.NoReg','SIMtrKasir.NoReg')
        ->whereIn('SIMtrRegistrasi.PaketJasaID', $jasaIDVoucher)
        ->where('SIMtrKasir.Tanggal','>=',$tglAwalPeriode)
        ->where('SIMtrKasir.Tanggal','<=',$tglAkhirPeriode)
        ->where('SIMtrKasir.Batal',0)
        ->get();
        return $dataRevenues;
    }

    public function getDataRevenueSection($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $sectionID){
        $revenue = collect();
        $revenue->toDayActual = $this->getDataRevenueSectionToSanata($tglAkhirActualPeriode, $tglAkhirActualPeriode, $sectionID);
        $revenue->toDateActual = $this->getDataRevenueSectionToSanata($tglAwalActualPeriode, $tglAkhirActualPeriode, $sectionID);
        $revenue->toDayLastMonth = $this->getDataRevenueSectionToSanata($tglAkhirLastMonthPeriode, $tglAkhirLastMonthPeriode, $sectionID);
        $revenue->toDateLastMonth = $this->getDataRevenueSectionToSanata($tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $sectionID);
        $revenue->toDateLastYear = $this->getDataRevenueSectionToSanata($tglAwalLastYear, $tglAkhirLastYear, $sectionID);
        return $revenue;
    }

    public function getDataRevenueSectionPoli($tglAwalActualPeriode, $tglAkhirActualPeriode, $tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $tglAwalLastYear, $tglAkhirLastYear, $sectionID){
        $revenue = collect();
        $revenue->toDayActual = $this->getDataRevenueSectionPoliToSanata($tglAkhirActualPeriode, $tglAkhirActualPeriode, $sectionID);
        $revenue->toDateActual = $this->getDataRevenueSectionPoliToSanata($tglAwalActualPeriode, $tglAkhirActualPeriode, $sectionID);
        $revenue->toDayLastMonth = $this->getDataRevenueSectionPoliToSanata($tglAkhirLastMonthPeriode, $tglAkhirLastMonthPeriode, $sectionID);
        $revenue->toDateLastMonth = $this->getDataRevenueSectionPoliToSanata($tglAwalLastMonthPeriode, $tglAkhirLastMonthPeriode, $sectionID);
        $revenue->toDateLastYear = $this->getDataRevenueSectionPoliToSanata($tglAwalLastYear, $tglAkhirLastYear, $sectionID);
        return $revenue;
    }

    public function getDataRevenueSectionPoliToSanata($tglAwalPeriode, $tglAkhirPeriode, $sectionID){
        // $dataRevenues = \DB::connection('sqlsrv')
        // ->table('SIMtrKasir')
        // ->selectRaw('COUNT(*) as jumlahPasien')
        // ->join('SIMtrRJ', 'SIMtrRJ.RegNo','SIMtrKasir.NoReg')
        // ->whereIn('SIMtrRJ.SectionID', $sectionID)
        // ->where('SIMtrKasir.Tanggal','>=',$tglAwalPeriode)
        // ->where('SIMtrKasir.Tanggal','<=',$tglAkhirPeriode)
        // ->where('SIMtrKasir.Batal',0)
        // ->where('SIMtrRJ.Batal',0)
        // ->get();

        $dataRevenues = \DB::connection('sqlsrv')
        ->table('SIMtrKasir')
        ->selectRaw('COUNT(distinct simtrKasir.NoReg) as jumlahPasien')
        ->join('SIMtrRJ', 'SIMtrRJ.RegNo','SIMtrKasir.NoReg')
        ->where(function($q) use ($sectionID) {
            $q->where('SIMtrRJ.SectionID', $sectionID)->orWhere('SIMtrKasir.SectionPerawatanID', $sectionID);
        })
        ->where('SIMtrKasir.Tanggal','>=',$tglAwalPeriode)
        ->where('SIMtrKasir.Tanggal','<=',$tglAkhirPeriode)
        ->where('SIMtrKasir.Batal',0)
        ->where('SIMtrRJ.Batal',0)
        ->where('SIMtrKasir.Nilai','!=',0)
        ->get();
        return $dataRevenues;
    }

    public function getDataRevenueSectionToSanata($tglAwalPeriode, $tglAkhirPeriode, $sectionID){
        $dataRevenues = \DB::connection('sqlsrv')
        ->table('SIMtrKasir')
        ->selectRaw('SUM(coalesce(Nilai,0)+ coalesce(NilaiInvoiceGabung,0)+ coalesce(NilaiInvoiceGabung2,0)+ coalesce(NilaiInvoiceGabung3,0)) - sum(NilaiDiscount) as totalRevenue')
        ->join('SIMtrRJ', 'SIMtrRJ.RegNo','SIMtrKasir.NoReg')
        ->where(function($q) use ($sectionID) {
            $q->where('SIMtrRJ.SectionID', $sectionID)->orWhere('SIMtrKasir.SectionPerawatanID', $sectionID);
        })
        ->where('SIMtrKasir.Tanggal','>=',$tglAwalPeriode)
        ->where('SIMtrKasir.Tanggal','<=',$tglAkhirPeriode)
        ->where('SIMtrKasir.Batal',0)
        ->get();
        return $dataRevenues;
    }

    public function tambahkanTitik($objekRevenue){
        $uang = new HelperUang();
        $objekRevenue->toDayActual = $uang->tambahkanKoma($objekRevenue->toDayActual[0]->totalRevenue);
        $objekRevenue->toDateActual = $uang->tambahkanKoma($objekRevenue->toDateActual[0]->totalRevenue);
        $objekRevenue->toDayLastMonth = $uang->tambahkanKoma($objekRevenue->toDayLastMonth[0]->totalRevenue);
        $objekRevenue->toDateLastMonth = $uang->tambahkanKoma($objekRevenue->toDateLastMonth[0]->totalRevenue);
        $objekRevenue->toDateLastYear = $uang->tambahkanKoma($objekRevenue->toDateLastYear[0]->totalRevenue);
        return $objekRevenue;
    }
    public function tambahkanTitikJumlahPasien($objekRevenue){
        $uang = new HelperUang();
        $objekRevenue->toDayActual = $uang->tambahkanKoma($objekRevenue->toDayActual[0]->jumlahPasien);
        $objekRevenue->toDateActual = $uang->tambahkanKoma($objekRevenue->toDateActual[0]->jumlahPasien);
        $objekRevenue->toDayLastMonth = $uang->tambahkanKoma($objekRevenue->toDayLastMonth[0]->jumlahPasien);
        $objekRevenue->toDateLastMonth = $uang->tambahkanKoma($objekRevenue->toDateLastMonth[0]->jumlahPasien);
        $objekRevenue->toDateLastYear = $uang->tambahkanKoma($objekRevenue->toDateLastYear[0]->jumlahPasien);
        return $objekRevenue;
    }

    public function mergerDataRevenue($objekRevenueMaster, $objekRevenueSlave){
        $objekRevenueMaster->toDayActual[0]->totalRevenue = $objekRevenueMaster->toDayActual[0]->totalRevenue + $objekRevenueSlave->toDayActual[0]->totalRevenue;
        $objekRevenueMaster->toDateActual[0]->totalRevenue = $objekRevenueMaster->toDateActual[0]->totalRevenue + $objekRevenueSlave->toDateActual[0]->totalRevenue;
        $objekRevenueMaster->toDayLastMonth[0]->totalRevenue = $objekRevenueMaster->toDayLastMonth[0]->totalRevenue + $objekRevenueSlave->toDayLastMonth[0]->totalRevenue;
        $objekRevenueMaster->toDateLastMonth[0]->totalRevenue = $objekRevenueMaster->toDateLastMonth[0]->totalRevenue + $objekRevenueSlave->toDateLastMonth[0]->totalRevenue;
        $objekRevenueMaster->toDateLastYear[0]->totalRevenue = $objekRevenueMaster->toDateLastYear[0]->totalRevenue + $objekRevenueSlave->toDateLastYear[0]->totalRevenue;

        return $objekRevenueMaster;
    }
}
