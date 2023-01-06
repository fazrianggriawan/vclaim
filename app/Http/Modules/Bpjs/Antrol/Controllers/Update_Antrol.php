<?php

namespace App\Http\Modules\Bpjs\Antrol\Controllers;

use App\Http\Libraries\LibApp;
use App\Http\Libraries\WS_Antrol;
use Illuminate\Http\Client\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Update_Antrol extends BaseController
{
    public function JadwalDokter(Request $request)
    {
        $data = array(
            "kodepoli" => "ANA",
            "kodesubspesialis" => "ANA",
            "kodedokter" => 12346,
            "jadwal" => array(
                array(
                    "hari" => "1",
                    "buka" => "08:00",
                    "tutup" => "10:00"
                ),
                array(
                    "hari" => "2",
                    "buka" => "15:00",
                    "tutup" => "17:00"
                )
            )
        );
        return WS_Antrol::post('jadwaldokter/updatejadwaldokter', $data);
    }

    public function WaktuAntrian(Request $request)
    {
        $data = array(
            "kodebooking" => "16032021A001",
            "taskid" => 1,
            "waktu" => 1616559330000,
            "jenisresep" => "Tidak ada/Racikan/Non racikan"
        );
        return WS_Antrol::post('antrean/updatewaktu', $data);
    }

}
