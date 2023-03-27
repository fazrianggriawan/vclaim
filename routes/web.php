<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) { return; });

/* Sep */
$router->get('sep/nomorSep/{nomorSep}', 'Bridging\Sep@GetByNomorSep' );
$router->post('sep/save', 'Bridging\Sep@Save' );
$router->post('sep/delete', 'Bridging\Sep@Delete' );
$router->post('sep/deleteSepInternal', 'Bridging\Sep@DeleteSepInternal' );
$router->post('sep/updatePasienPulang', 'Bridging\Sep@UpdatePasienPulang' );

/* History */
$router->get('history/nomorKartu/{nomorKartu}/from/{from}/to/{to}', 'Bridging\Monitoring@GetHistorySep' );

/* Peserta */
$router->get('peserta/nomorKartu/{nomorKartu}', 'Bridging\Peserta@GetByNomorKartu' );
$router->get('peserta/nik/{nik}', 'Bridging\Peserta@GetByNik' );
$router->get('peserta/fingerPrint/{noKartu}/{tanggal}', 'Bridging\Peserta@GetFingerPrint' );

/* Rujukan */
$router->get('rujukan/nomorRujukan/{nomorRujukan}', 'Bridging\Rujukan@GetByNomorRujukan' );
$router->get('rujukan/faskes/nomorKartu/{nomorKartu}', 'Bridging\Rujukan@GetRujukanFaskes' );
$router->get('rujukan/rs/nomorKartu/{nomorKartu}', 'Bridging\Rujukan@GetRujukanRs' );
$router->get('rujukan/keluarRs/{nomorRujukan}', 'Bridging\Rujukan@GetRujukanKeluar' );
$router->get('rujukan/jumlahSep/nomorRujukan/{nomorRujukan}/jnsPelayanan/{jnsPelayanan}', 'Bridging\Rujukan@GetJumlahSep' );
$router->post('rujukan/save/rujukanKeluar', 'Bridging\Rujukan@SaveRujukanKeluar' );

/* Surat Kontrol */
$router->get('suratKontrol/get/{noSuratKontrol}', 'Bridging\SuratKontrol@GetSuratKontrol' );
$router->get('suratKontrol/byPeserta/nomorKartu/{nomorKartu}/bulan/{bulan}/filter/{filter}', 'Bridging\SuratKontrol@ListSuratKontrolByPeserta' );
$router->get('suratKontrol/delete/noSuratKontrol/{noSuratKontrol}', 'Bridging\SuratKontrol@Delete' );
$router->get('suratKontrol/bySep/{noSep}', 'Bridging\SuratKontrol@BySep' );
$router->post('suratKontrol/save', 'Bridging\SuratKontrol@SaveRencanaKontrol' );

/* PRB */
$router->get('prb/data/from/{from}/to/{to}', 'Bridging\Prb@DataPrb' );

/* Referensi / Master Data */

$router->get('referensi/poliklinik/{keyword}', 'Bridging\Referensi@Poliklinik' );
$router->get('referensi/dokter/jnsPerawatan/{jnsPelayanan}/poliklinik/{poliklinik}', 'Bridging\Referensi@DokterDpjp' );
$router->get('referensi/faskes/{keyword}', 'Bridging\Referensi@Faskes' );
$router->get('referensi/jadwalDokter/poli/{kodePoli}/tanggal/{tglKunjungan}', 'Bridging\Referensi@JadwalDokter' );
$router->post('referensi/procedure', 'Bridging\Referensi@Procedure' );
$router->post('referensi/diagnosa', 'Bridging\Referensi@Diagnosa' );

/* Print */
$router->get('sep/print/{nomorSep}', 'Bridging\Cetak\Sep@Index' );
$router->get('sep/print/anjungan/{nomorSep}/{kodeBooking}', 'Bridging\Cetak\Sep@Anjungan' );
$router->get('sep/print/anjunganSepOnly/{nomorSep}', 'Bridging\Cetak\Sep@PrintSepOnly' );
$router->get('sep/print/booking/{kodeBooking}', 'Bridging\Cetak\Sep@DataBooking' );
$router->get('rujukan/print/{nomorRujukan}', 'Bridging\Cetak\Rujukan@Index' );
$router->get('rujukan/print/keluar/{data}', 'Bridging\Cetak\Rujukan@RujukanKeluar' );
$router->get('print/anjungan/nomor_antrian/{tipe}/{noAntrian}', 'Bridging\Cetak\Antrian@NomorAntrian' );


/* Master */
$router->get('master/poliklinik', 'Master@Poliklinik' );

/* Antrian Online */
$router->get('antrian/kodeBooking/{kodeBooking}', 'Bridging\AntrianOnline@GetAntrian' );
$router->get('antrian/byJadwal/{jamPraktek}/{kodePoli}/{tglKunjungan}', 'Bridging\AntrianOnline@AntrianByJadwal' );
$router->get('antrian/dashboard', 'Bridging\AntrianOnline@DashboardAntrian' );
$router->post('antrian/filterData', 'Bridging\AntrianOnline@FilterData' );
$router->post('antrian/save', 'Bridging\AntrianOnline@Save' );
$router->post('antrian/save_to_simrs', 'Bridging\AntrianOnline@SaveToSimrs' );
$router->post('antrian/save_after_sep', 'Bridging\AntrianOnline@SaveAfterSep' );
$router->post('antrian/checkin', 'Bridging\AntrianOnline@Checkin' );


$router->get('sendEmail', 'MailController@SendMail' );
$router->get('allowList', 'MailController@AddAllowList' );