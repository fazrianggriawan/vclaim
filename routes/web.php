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

/* Rujukan */
$router->get('rujukan/nomorRujukan/{nomorRujukan}', 'Bridging\Rujukan@GetByNomorRujukan' );
$router->get('rujukan/faskes/nomorKartu/{nomorKartu}', 'Bridging\Rujukan@GetRujukanFaskes' );
$router->get('rujukan/rs/nomorKartu/{nomorKartu}', 'Bridging\Rujukan@GetRujukanRs' );
$router->get('rujukan/keluarRs/{nomorRujukan}', 'Bridging\Rujukan@GetRujukanKeluar' );
$router->post('rujukan/save/rujukanKeluar', 'Bridging\Rujukan@SaveRujukanKeluar' );

/* Surat Kontrol */
$router->get('suratKontrol/get/{noSuratKontrol}', 'Bridging\SuratKontrol@GetSuratKontrol' );
$router->get('suratKontrol/byPeserta/nomorKartu/{nomorKartu}/bulan/{bulan}/filter/{filter}', 'Bridging\SuratKontrol@ListSuratKontrolByPeserta' );
$router->get('suratKontrol/delete/noSuratKontrol/{noSuratKontrol}', 'Bridging\SuratKontrol@Delete' );
$router->post('suratKontrol/save', 'Bridging\SuratKontrol@SaveRencanaKontrol' );


/* PRB */
$router->get('prb/data/from/{from}/to/{to}', 'Bridging\Prb@DataPrb' );

/* Referensi / Master Data */
$router->get('referensi/diagnosa/{keyword}', 'Bridging\Referensi@Diagnosa' );
$router->get('referensi/poliklinik/{keyword}', 'Bridging\Referensi@Poliklinik' );
$router->get('referensi/dokter/jnsPerawatan/{jnsPelayanan}/poliklinik/{poliklinik}', 'Bridging\Referensi@DokterDpjp' );
$router->get('referensi/faskes/{keyword}', 'Bridging\Referensi@Faskes' );

// Print
$router->get('sep/print/{nomorSep}', 'Bridging\Cetak\Sep@Index' );
$router->get('rujukan/print/{nomorRujukan}', 'Bridging\Cetak\Rujukan@Index' );
$router->get('rujukan/print/keluar/{data}', 'Bridging\Cetak\Rujukan@RujukanKeluar' );