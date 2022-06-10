<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Http\Libraries\VclaimLib;
use DateInterval;
use DateTime;

class Rujukan extends Controller
{
    public static function GetByNomorRujukan($nomorRujukan)
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

    public function GetRujukanKeluar($nomorRujukan)
	{
        // $from = date("Y-m-d", strtotime("-1 months"));
        // $date = new DateTime(date('Y-m-d')); // Y-m-d
        // $date->add(new DateInterval('P90D'));
        // $from =  $date->format('Y-m-d');
        // $to = date('Y-m-d');
        // $url = 'Rujukan/Keluar/List/tglMulai/'.$from.'/tglAkhir/'.$to;
        // return $url;

        return VclaimLib::exec('GET', 'Rujukan/Keluar/'.$nomorRujukan);
	}

    public function SaveRujukanKeluar()
    {
        $request = json_decode(file_get_contents("php://input"));

        $data['request']['t_rujukan'] = array (
            'noSep' => $request->noSep->noSep,
            'tglRujukan' => $request->tanggal,
            'tglRencanaKunjungan' => $request->tglRencana,
            'ppkDirujuk' => $request->ppk->kode,
            'jnsPelayanan' => $request->jnsPelayanan,
            'catatan' => $request->catatan,
            'diagRujukan' => $request->diagnosa->kode,
            'tipeRujukan' => $request->tipeRujukan,
            'poliRujukan' => $request->poli->kode,
            'user' => 'Vclaim',
        );

        return VclaimLib::exec('POST', 'Rujukan/2.0/insert', json_encode($data));

    }

}
