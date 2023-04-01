<?php

$dir = '\App\Http\Controllers\Anjungan';

// GET
// $router->get('anjungan/get/ref_dokter', $dir.'\Get_Anjungan@Ref_Dokter');

// POST
$router->post('anjungan/send-to-fingerprint', $dir.'\Save_Anjungan@SendToFingerPrint');