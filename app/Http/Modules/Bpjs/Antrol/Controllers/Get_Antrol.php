<?php

namespace App\Http\Modules\Bpjs\Antrol\Controllers;

use App\Http\Modules\Bpjs\Antrol\Classes\WS_Antrol;
use Laravel\Lumen\Routing\Controller as BaseController;

class Get_Antrol extends BaseController
{
    public function Ref_Poli()
    {
        return WS_Antrol::get('ref/poli');
    }

    public function Ref_Dokter()
    {
        return WS_Antrol::get('ref/dokter');
    }

    public function Ref_Jadwal_Dokter($kodePoliBpjs, $tanggal)
    {
        return WS_Antrol::get('jadwaldokter/kodepoli/'.$kodePoliBpjs.'/tanggal/'.$tanggal);
    }

    public function Ref_Poli_Fingerprint()
    {
        return WS_Antrol::get('ref/poli/fp');
    }

    public function Ref_Pasien_Fingerprint($jnsIdentitas, $nomorIdentitas)
    {
        return WS_Antrol::get('ref/pasien/fp/identitas/'.$jnsIdentitas.'/noidentitas/'.$nomorIdentitas);
    }

    public function List_Task($kodebooking)
    {
        $data = array('kodebooking' => $kodebooking);
        return WS_Antrol::post('antrean/getlisttask', $data);
    }

    public function Dahboard_Per_Tanggal($tanggal, $jnsWaktu)
    {
        return WS_Antrol::get('dashboard/waktutunggu/tanggal/'.$tanggal.'/waktu/'.$jnsWaktu);
    }

    public function Dashboard_Per_Bulan($bulan, $tahun, $jnsWaktu)
    {
        return WS_Antrol::get('dashboard/waktutunggu/bulan/'.$bulan.'/tahun/'.$tahun.'/waktu/'.$jnsWaktu);
    }

    public function Antrian_Per_Tanggal($tanggal)
    {
        return WS_Antrol::get('antrean/pendaftaran/tanggal/'.$tanggal);
    }

    public function Antrian_Per_Kodebooking($kodebooking)
    {
        return WS_Antrol::get('antrean/pendaftaran/kodebooking/'.$kodebooking);
    }

    public function Antrian_Belum_Dilayani()
    {
        return WS_Antrol::get('antrean/pendaftaran/aktif');
    }

    public function Antrian_Belum_Dilayani_Per_Poli($kodePoliBpjs, $kodeDokterBpjs, $hari, $jamPraktek)
    {
        return WS_Antrol::get('antrean/pendaftaran/kodepoli/'.$kodePoliBpjs.'/kodedokter/'.$kodeDokterBpjs.'/hari/'.$hari.'/jampraktek/'.$jamPraktek);
    }
}
