<?php

namespace App\Http\Modules\Bpjs\Antrol\Controllers;

use App\Http\Modules\Bpjs\Antrol\Classes\DataAntrian;
use App\Http\Modules\Bpjs\Antrol\Classes\WS_Antrol;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Save_Antrol extends BaseController
{
    public function Antrian(Request $request)
	{
        $dataAntrian = array();
        $data = array(
            "kodebooking" => $request->nomor_booking,
            "jenispasien" => $request->jns_pasien,
            "nomorkartu" => $request->no_kartu_bpjs,
            "nik" => $request->nik,
            "nohp" => $request->hp,
            "kodepoli" => $request->kodePoliBpjs,
            "namapoli" => $request->namaPoliBpjs,
            "pasienbaru" => $request->pasienBaru,
            "norm" => $request->norm,
            "tanggalperiksa" => $request->tgl_kunjungan,
            "kodedokter" => $request->kodedokter_bpjs,
            "namadokter" => $request->namadokter,
            "jampraktek" => $request->jam_praktek,
            "jeniskunjungan" => $request->jns_kunjungan,
            "nomorreferensi" => $request->no_referensi,
            "nomorantrean" => $request->prefix_antrian.'-'.$request->angka_antrian,
            "angkaantrean" => $request->angka_antrian,
            "estimasidilayani" => $request->estimasi_dilayani,
            "sisakuotajkn" => $request->sisa_kuota_jkn,
            "kuotajkn" => $request->total_quota,
            "sisakuotanonjkn" => $request->sisa_kuota_umum,
            "kuotanonjkn" => $request->kuota_non_jkn,
            "keterangan" => "Peserta harap 30 menit lebih awal guna pencatatan administrasi."
        );

        return WS_Antrol::post('antrean/add', $data);
	}

    public function AntrianFarmasi(Request $request)
	{
        $data = array(
            "kodebooking" => "16032021A001",
            "jenisresep" => "racikan",
            "nomorantrean" => 1,
            "keterangan" => ""
        );
        return WS_Antrol::post('antrean/farmasi/add', $data);
	}

}
