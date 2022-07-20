<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Http\Libraries\VclaimLib;
use Illuminate\Http\Client\Request;

class Peserta extends Controller
{
    public static function GetByNomorKartu($nomorKartu)
	{
		$url = 'Peserta/nokartu/'.$nomorKartu.'/tglSEP/'.date('Y-m-d');
        return VclaimLib::exec('GET', $url);
	}

    public static function GetByNik($nik)
	{
		$url = 'Peserta/nik/'.$nik.'/tglSEP/'.date('Y-m-d');
        return VclaimLib::exec('GET', $url);
	}
}
