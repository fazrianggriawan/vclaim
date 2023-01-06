<?php

namespace App\Http\Modules\Bpjs\Antrol\Controllers;

use App\Http\Libraries\WS_Antrol;
use Laravel\Lumen\Routing\Controller as BaseController;

class Delete_Antrol extends BaseController
{
    public function Batal()
	{
        $data = array(
            "kodebooking" => "16032021A001",
            "keterangan" => "Terjadi perubahan jadwal dokter, silahkan daftar kembali"
        );

        return WS_Antrol::post('antrean/batal', $data);
	}

}
