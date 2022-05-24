<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Http\Libraries\VclaimLib;

class Rujukan extends Controller
{
    public function GetByNomorRujukan($nomorRujukan)
	{
        return VclaimLib::exec('GET', 'Rujukan/'.$nomorRujukan);
	}

    public function GetRujukanFaskes($nomorKartu)
	{
        return VclaimLib::exec('GET', 'Rujukan/List/Peserta/'.$nomorKartu);
	}

    public function GetRujukanRs($nomorKartu)
	{
        return VclaimLib::exec('GET', 'Rujukan/RS/List/Peserta/'.$nomorKartu);
	}

}
