<?php

//==================================================
//||         SECTION CHART DATA                   ||   										
//==================================================

//DASHBOARD MENU

Route::group(['middleware' => 'auth'],function(){
	Route::get('/', [
		'uses' => 'DashboardController@dashboard',
	]);
	
	Route::get('dashboard',[
		'uses' => 'DashboardController@dashboard',
	])->name('dashboard');
	
	//DASHBOARD PASIEN
	Route::get('dashboard/pasien', [
		'uses' => 'PasienController@dashboardPasien'
	])->name('dashboard pasien');
	
	Route::get('dashboard/pasienBaru',[
		'uses' => 'PasienBaruController@dashboard',
	])->name('dashboard pasien baru');
	
	Route::get('dashboard/pasienBaru/{idSectionSanata}',[
		'uses' => 'PasienBaruDetailController@dashboard',
	])->name('dashboard detail pasien baru');
	
	Route::get('dashboard/pasienBaru/daily/{idSectionSanata}',[
		'uses' => 'PasienBaruDetailController@dashboardDaily',
	])->name('dashboard detail daily pasien baru');
	
	Route::get('dashboard/pasienBaru/section/{idSectionSanata}',[
		'uses' => 'PasienBaruDetailSectionController@dashboardSectionPasien',
	])->name('dashboard detail section pasien baru');
	
	Route::get('dashboard/pasienBaru/section/{idSectionSanata}/grafikYTD/{sectionID}',[
		'uses' => 'PasienBaruDetailSectionController@dashboardSectionPasienGrafikYTD',
	])->name('dashboard detail section pasien baru grafikytd');
	
	Route::get('dashboard/pasienBaru/section/{idSectionSanata}/grafikMTD/{sectionID}',[
		'uses' => 'PasienBaruDetailSectionController@dashboardSectionPasienGrafikMTD',
	])->name('dashboard detail section pasien baru grafikmtd');
	
	Route::get('dashboard/pasienRepeater',[
		'uses' => 'PasienRepeaterController@dashboard',
	])->name('dashboard pasien repeater');
	
	Route::get('dashboard/pasienRepeater/{idSectionSanata}',[
		'uses' => 'PasienRepeaterDetailController@dashboard',
	])->name('dashboard detail pasien repeater');
	
	Route::get('dashboard/pasienRepeater/daily/{idSectionSanata}',[
		'uses' => 'PasienRepeaterDetailController@dashboardDaily',
	])->name('dashboard detail daily pasien repeater');
	
	Route::get('dashboard/pasienRepeater/section/{idSectionSanata}',[
		'uses' => 'PasienRepeaterDetailSectionController@dashboardSectionPasien',
	])->name('dashboard detail section pasien repeater');
	
	Route::get('dashboard/pasienRepeater/section/{idSectionSanata}/grafikYTD/{sectionID}',[
		'uses' => 'PasienRepeaterDetailSectionController@dashboardSectionPasienGrafikYTD',
	])->name('dashboard detail section pasien repeater grafikytd');
	
	Route::get('dashboard/pasienRepeater/section/{idSectionSanata}/grafikMTD/{sectionID}',[
		'uses' => 'PasienRepeaterDetailSectionController@dashboardSectionPasienGrafikMTD',
	])->name('dashboard detail section pasien repeater grafikmtd');
	
	
	//DASHBOARD RAWAT JALAN
	Route::get('dashboard/rawatJalan',[
		'uses' => 'RawatJalanController@dashboard',
	])->name('dashboard rawat jalan');
	
	Route::get('dashboard/rawatJalan/{idSectionSanata}',[
		'uses' => 'RawatJalanDetailController@dashboard'
	])->name('dashboard detail rawat jalan');
	
	Route::get('dashboard/rawatJalan/daily/{idSectionSanata}',[
		'uses' => 'RawatJalanDetailController@dashboardDaily'
	])->name('dashboard detail daily rawat jalan');
	
	Route::get('dashboard/rawatJalan/dokter/{idSectionSanata}', [
		'uses' => 'DokterRawatJalanController@dashboard'
	])->name('dashboard dokter rawat jalan');
	
	
	//DASHBOARD RAWAT INAP
	Route::get('dashboard/rawatInap', [
		'uses' => 'RawatInapController@dashboard'
	])->name('dashboard rawat inap');
	
	Route::get('dashboard/rawatInap/{idSectionSanata}', [
		'uses' => 'RawatInapDetailController@dashboard'
	])->name('dashboard detail rawat inap');
	
	Route::get('dashboard/rawatInap/daily/{idSectionSanata}',[
		'uses' => 'RawatInapDetailController@dashboardDaily'
	])->name('dashboard detail daily rawat inap');
	
	Route::get('dashboard/paketPersalinan', [
		'uses' => 'PaketPersalinanController@dashboard'
	])->name('dashboard paket persalinan detail');
	
	Route::get('dashboard/paketPersalinan/{sectionID}', [
		'uses' => 'PaketPersalinanController@paketPersalinanYTD'
	])->name('dashboard paket persalinan detail persection');
	
	Route::get('dashboard/paketPersalinan/daily/{sectionID}', [
		'uses' => 'PaketPersalinanController@dashboardPaketPersalinanMTD'
	])->name('dashboard paket persalinan detail persection daily');
	
	
	//DASHBOARD KAMAR
	Route::get('dashboard/kamar',[
		'uses' => 'KamarController@dashboard',
	])->name('dashboard kamar');
	
	Route::get('dashboard/kamar/{idSectionSanata}',[
		'uses' => 'KamarDetailController@dashboard',
	])->name('dashboard detail kamar');
	
	Route::get('dashboard/kamar/daily/{idSectionSanata}',[
		'uses' => 'KamarDetailController@dashboardDaily',
	])->name('dashboard detail kamar daily');
	
	
	//DASHBOARD DAILY REVENUE
	Route::get('dashboard/dailyRevenue', [
		'uses' => 'DailyRevenueController@dashboard',
	])->name('dashboard daily revenue');
	
	
	//HALAMAN BAYI RECHECKUP
	Route::get('bayiCheckUp',[
		'uses' => 'BayiCheckUpController@dashboard',
	])->name('dashboard bayi check up');
	
	Route::post('bayiCheckUp/getDataBayi',[
		'uses' => 'BayiCheckUpController@getDataBayi',
	])->name('get data bayi check up');
		
	//HALAMAN PAKET PERSALINAN
	Route::get('paketPersalinan', [
		'uses' => 'PaketPerawatanController@dashboardPaket'
	])->name('dashboard paket persalinan');
	
	Route::post('paketPersalinan/getDataPaket',[
		'uses' => 'PaketPerawatanController@getPaketPersalinan'
	])->name('get data paket persalinan');
	
	//HALAMAN DAILY SALES
	Route::get('dailySales',[
		'uses' => 'DailySalesController@dashboard',
	])->name('dashboard daily sales');
	Route::post('dailySales', [
		'uses' => 'DailySalesController@calculate'
	])->name('get data daily sales');
	
	//==================================================
	//||         SECTION DATA MASTER                  ||   										
	//==================================================
	//Revenue Data
	Route::get('revenue',[
		'uses' => 'RevenueController@indexRevenue'
	])->name('revenue');
	Route::get('revenueForm/{id?}',[
		'uses' => 'RevenueController@formRevenue'
	])->name('tambah edit tahun revenue');
	Route::post('revenueForm',[
		'uses' => 'RevenueController@createRevenue'
	])->name('create revenue');
	Route::post('revenueForm/{id}',[
		'uses' => 'RevenueController@editRevenue'
	])->name('edit revenue');
	
	//Revenue Transaksi
	Route::get('revenue/revenueList/{id}',[
		'uses' => 'RevenueTransaksiController@indexRevenueList'
	])->name("revenue list pertahun");
	
	//Revenue Transaksi Detail Data
	Route::get('revenue/revenueList/revenueTransaksiDetailList/{id}',[
		'uses' => 'RevenueTransaksiDetailController@indexRevenueTransaksiDetail'
	])->name('revenue list perbulan');
	
	Route::get('revenue/revenueList/revenueTransaksiDetailList/revenueTransaksiDetailForm/{id}',[
		'uses' => 'RevenueTransaksiDetailController@formCreateRevenueTransaksiDetail'
	])->name('tambah save revenue transaksi detail');
	Route::post('revenue/revenueList/revenueTransaksiDetailList/revenueTransaksiDetailForm/{id}',[
		'uses' => 'RevenueTransaksiDetailController@createRevenueTransaksiDetail'
	])->name('tambah save revenue transaksi detail');
	Route::get('revenue/revenueList/revenueTransaksiDetailList/revenueTransaksiDetailFormEdit/{id}/{idx?}',[
		'uses' => 'RevenueTransaksiDetailController@formEditRevenueTransaksiDetail'
	])->name('edit save revenue transaksi detail');
	Route::post('revenue/revenueList/revenueTransaksiDetailList/revenueTransaksiDetailFormEdit/{id}/{idx?}',[
		'uses' => 'RevenueTransaksiDetailController@updateRevenueTransaksiDetail'
	])->name('edit save revenue transaksi detail');
	Route::get('revenue/revenueList/revenueTransaksiDetailList/revenueTransaksiDetailFormDelete/{id}',[
		'uses' => 'RevenueTransaksiDetailController@deleteRevenueTransaksiDetail'
	])->name('edit delete revenue transaksi detail');
	
	
	//Kategori
	Route::get('kategori',[
		'uses' => 'KategoriController@indexKategori',
	])->name('list kategori');
	Route::get('kategori/kategoriForm/{id?}', [
		'uses' => 'KategoriController@formKategori',
	])->name('tambah edit kategori');
	Route::post('kategori/kategoriForm',[
		'uses' => 'KategoriController@createKategori',
	])->name('create kategori');
	Route::post('kategori/kategoriForm/{id}', [
		'uses' => 'KategoriController@editKategori',
	])->name('update kategori');
	Route::get('kategori/deleteForm/{id}', [
		'uses' => 'KategoriController@deleteKategori',
	])->name('delete kategori');
	
	//Group Kategori
	Route::get('groupKategori',[
		'uses' => 'GroupKategoriController@indexGroupKategori',
	])->name('list group kategori');
	Route::get('groupKategori/groupKategoriForm/{id?}', [
		'uses' => 'GroupKategoriController@formGroupKategori',
	])->name('tambah edit group kategori');
	Route::post('groupKategori/groupKategoriForm',[
		'uses' => 'GroupKategoriController@createGroupKategori',
	])->name('create group kategori');
	Route::post('groupKategori/groupKategoriForm/{id}', [
		'uses' => 'GroupKategoriController@editGroupKategori',
	])->name('update group kategori');
	Route::get('groupKategori/deleteForm/{id}', [
		'uses' => 'GroupKategoriController@deleteGroupKategori',
	])->name('delete group kategori');
	
	Route::get('syncData', [
	'uses' => 'SyncDataController@syncData',
	])->name('sync data');

	Route::get('logoutAkun',[
		'uses' => 'Auth\LoginController@logout'
	])->name('logoutAkunYa');
});

Route::get('syncDataSchedule', [
  'uses' => 'SyncDataController@syncDataSchedule',
]);

//AUTH
Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
