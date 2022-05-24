<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Http\Libraries\VclaimLib;
use Illuminate\Http\Client\Request;

class SuratKontrol extends Controller
{
    public function BySep(Request $request)
	{
		$url = 'RencanaKontrol/nosep/'.$request->input('key');
        return VclaimLib::exec('GET', $url);
	}

    public function ListSuratKontrol(Request $request)
	{
        $parameter1 = $request->input('from');
		$parameter2 = $request->input('to');
		$parameter3 = $request->input('filter');

		$url = 'RencanaKontrol/ListRencanaKontrol/tglAwal/'.$parameter1.'/tglAkhir/'.$parameter2.'/filter/'.$parameter3;
        return VclaimLib::exec('GET', $url);
	}

    public function ListSuratKontrolByPeserta($nomorKartu, $bulan, $filter)
	{
		$url = 'RencanaKontrol/ListRencanaKontrol/Bulan/'.substr($bulan,5,2).'/Tahun/'.substr($bulan,0, 4).'/Nokartu/'.$nomorKartu.'/filter/'.$filter;
        return VclaimLib::exec('GET', $url);
	}

    public function NomorSurat(Request $request)
	{
		$url = 'RencanaKontrol/noSuratKontrol/'.$request->input('key');
		$this->lib_vclaim->exec('GET', $url);
	}

    public function SaveRencanaKontrol()
	{
		$post = json_decode(file_get_contents("php://input"));

		$tgl = new \DateTime($post->tgl);

		$data["request"] = array(
			"noSEP" => $post->noSep,
			"kodeDokter" => $post->dokter,
			"poliKontrol" => $post->poli,
			"tglRencanaKontrol" => $tgl->format('Y-m-d'),
			"user" => 'Vclaim'
		);

        return VclaimLib::exec('POST', 'RencanaKontrol/insert', json_encode($data));

	}

    public function SaveSpri()
	{

		$post = json_decode(file_get_contents("php://input"));

		$tgl = new \DateTime($post->tgl);

		$data["request"] = array(
			"noKartu" => $post->noKartu,
			"kodeDokter" => $post->dokter,
			"poliKontrol" => $post->poli,
			"tglRencanaKontrol" => $tgl->format('Y-m-d'),
			"user" => 'Vclaim'
		);

        return VclaimLib::exec('POST', 'RencanaKontrol/InsertSPRI', json_encode($data));

	}

}
