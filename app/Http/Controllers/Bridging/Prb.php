<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Http\Libraries\VclaimLib;
use Illuminate\Http\Client\Request;

class Prb extends Controller
{
    public function DataPrb($from, $to)
	{
		$url = 'prb/tglMulai/'.$from.'/tglAkhir/'.$to;
        return VclaimLib::exec('GET', $url);
	}

    public function Save()
	{
		$post = json_decode(file_get_contents("php://input"));

		$obat = array();

		if( count($post->obat) > 0 ){
			foreach ($post->obat as $key => $value) {
				$a = array(
					"kdObat" => $value->obat->kode,
                    "signa1" => strval($value->signa1),
                    "signa2" => strval($value->signa2),
                    "jmlObat" => strval($value->jumlah)
				);
				array_push($obat, $a);
			}
		}

		$data['request']['t_prb'] = array(
			"noSep" => $post->data->sep,
            "noKartu" => $post->data->noKartu,
            "alamat" => $post->data->alamat,
            "email" => $post->data->email,
            "programPRB" => $post->data->programPrb,
            "kodeDPJP" => $post->data->dokter,
            "keterangan" => $post->data->keterangan,
            "saran" => $post->data->saran,
            "user" => 9481,
            "obat" => $obat
		);

        return VclaimLib::exec('POST', 'PRB/insert', json_encode($data));
	}

    public function Delete()
	{
		$post = json_decode(file_get_contents("php://input"));

		$data['request']['t_prb'] = array(
			"noSrb" => $post->noSRB,
			"noSep" => $post->noSEP,
			"user" =>  9481
        );

        return VclaimLib::exec('DELETE', 'PRB/Delete', json_encode($data));
	}
}
