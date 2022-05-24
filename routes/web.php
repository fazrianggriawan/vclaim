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

/* Bridging Vclaim */
$router->get('sep/nomorSep/{nomorSep}', 'Bridging\Sep@GetByNomorSep' );


// $router->get('sep/history/nomorKartu/{nomorKartu}', 'Bridging\Monitoring@GetHistorySep' );
/* History */
$router->get('history/nomorKartu/{nomorKartu}/from/{from}/to/{to}', 'Bridging\Monitoring@GetHistorySep' );

/* Peserta */
$router->get('peserta/nomorKartu/{nomorKartu}', 'Bridging\Peserta@GetByNomorKartu' );
$router->get('peserta/nik/{nik}', 'Bridging\Peserta@GetByNik' );

/* Rujukan */
$router->get('rujukan/nomorRujukan/{nomorRujukan}', 'Bridging\Rujukan@GetByNomorRujukan' );
$router->get('rujukan/faskes/nomorKartu/{nomorKartu}', 'Bridging\Rujukan@GetRujukanFaskes' );
$router->get('rujukan/rs/nomorKartu/{nomorKartu}', 'Bridging\Rujukan@GetRujukanRs' );

/* Surat Kontrol */
$router->get('suratKontrol/byPeserta/nomorKartu/{nomorKartu}/bulan/{bulan}/filter/{filter}', 'Bridging\SuratKontrol@ListSuratKontrolByPeserta' );

/* PRB */
$router->get('prb/data/from/{from}/to/{to}', 'Bridging\Prb@DataPrb' );

/* Referensi / Master Data */
$router->get('referensi/poliklinik/{keyword}', 'Bridging\Referensi@Poliklinik' );
