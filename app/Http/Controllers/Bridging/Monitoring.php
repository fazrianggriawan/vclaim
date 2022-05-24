<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Http\Libraries\VclaimLib;
use Illuminate\Http\Client\Request;

class Monitoring extends Controller
{
    public function GetHistorySep($nomorKartu, $from, $to)
	{
		$url = 'monitoring/HistoriPelayanan/NoKartu/'.$nomorKartu.'/tglAwal/'.$from.'/tglAkhir/'.$to;
        return VclaimLib::exec('GET', $url);
	}

    public function DataKunjungan(Request $request)
	{
		$url = 'Monitoring/Kunjungan/Tanggal/'.$request->input('tgl').'/JnsPelayanan/'.$request->input('jnsPelayanan');
        return VclaimLib::exec('GET', $url);
	}

    public function DataKlaim(Request $request)
	{
		$url = 'Monitoring/Klaim/Tanggal/'.$request->input('tglPulang').'/JnsPelayanan/'.$request->input('jnsPelayanan').'/Status/'.$request->input('statusKlaim');
		return VclaimLib::exec('GET', $url);
	}

}
