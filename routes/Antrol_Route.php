<?php

/* Route Module Billing */

$folder           = '\App\Http\Modules\Bpjs\Antrol\Controllers\\';
$get_controller   = 'Get_Antrol';
$save_controller  = 'Save_Antrol';
$updateController = 'Update_Antrol';
$deleteController = 'Delete_Antrol';
$prefix_url       = 'module/bpjs/antrol';

// GET
$router->get($prefix_url.'/get/ref_poli', $folder.$get_controller.'@Ref_Poli');
$router->get($prefix_url.'/get/ref_dokter', $folder.$get_controller.'@Ref_Dokter');
$router->get($prefix_url.'/get/ref_jadwal_dokter/{kodePoliBpjs}/{tanggal}', $folder.$get_controller.'@Ref_Jadwal_Dokter');
$router->get($prefix_url.'/get/ref_poli_fingerprint', $folder.$get_controller.'@Ref_Poli_Fingerprint');
$router->get($prefix_url.'/get/ref_pasien_fingerprint/{jnsIdentitas}/{nomorIdentitas}', $folder.$get_controller.'@Ref_Pasien_Fingerprint');
$router->get($prefix_url.'/get/list_waktu_antrian/{kodebooking}', $folder.$get_controller.'@List_Task');
$router->get($prefix_url.'/get/dashboard_per_tanggal/{tanggal}/{jnsWaktu}', $folder.$get_controller.'@Dahboard_Per_Tanggal');
$router->get($prefix_url.'/get/dashboard_per_bulan/{bulan}/{tahun}/{jnsWaktu}', $folder.$get_controller.'@Dashboard_Per_Bulan');
$router->get($prefix_url.'/get/antrian_per_tanggal/{tanggal}', $folder.$get_controller.'@Antrian_Per_Tanggal');
$router->get($prefix_url.'/get/antrian_per_kodebooking/{kodebooking}', $folder.$get_controller.'@Antrian_Per_Kodebooking');
$router->get($prefix_url.'/get/antrian_belum_dilayani', $folder.$get_controller.'@Antrian_Belum_Dilayani');
$router->get($prefix_url.'/get/antrian_belum_dilayani_per_poli/{kodePoliBpjs}/{kodeDokterBpjs}/{hari}/{jamPraktek}', $folder.$get_controller.'@Antrian_Belum_Dilayani_Per_Poli');

// POST
$router->post($prefix_url.'/save/antrian', $folder.$save_controller.'@Antrian');
$router->post($prefix_url.'/save/antrian_farmasi', $folder.$save_controller.'@AntrianFarmasi');

// PUT / UPDATE
$router->put($prefix_url.'/update/jadwal_dokter', $folder.$updateController.'@JadwalDokter');
$router->put($prefix_url.'/update/waktu_antrian', $folder.$updateController.'@WaktuAntrian');

// DELETE
$router->delete($prefix_url.'/delete/batal', $folder.$deleteController.'@Batal');