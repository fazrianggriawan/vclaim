<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Http\Libraries\VclaimLib;
use Illuminate\Http\Client\Request;

class Referensi extends Controller
{
    public function Diagnosa(Request $request)
	{
		$url = 'referensi/diagnosa/'.$request->input('key');
        return VclaimLib::exec('GET', $url);
	}

    public function Poliklinik($keyword)
	{
        return VclaimLib::exec('GET', 'referensi/poli/'.$keyword);
	}

    public function Faskes(Request $request)
	{
		$url = 'referensi/faskes/'.$request->input('key').'/2';
        return VclaimLib::exec('GET', $url);
	}

    public function Procedure(Request $request)
	{
        $url = 'referensi/procedure/'.$request->input('key');
        return VclaimLib::exec('GET', $url);
	}

    public function KelasRawat()
	{
        return VclaimLib::exec('GET', 'referensi/kelasrawat');
	}

    public function Dokter(Request $request)
	{
        $url = 'referensi/dokter/'.$request->input('key');
        return VclaimLib::exec('GET', $url);
	}

    public function Spesialistik()
	{
		return VclaimLib::exec('GET', 'referensi/spesialistik');
	}

    public function RuangRawat()
	{
        return VclaimLib::exec('GET', 'referensi/ruangrawat');
	}

    public function CaraKeluar()
	{
        return VclaimLib::exec('GET', 'referensi/carakeluar');
	}

    public function PascaPulang()
	{
        return VclaimLib::exec('GET', 'referensi/pascapulang');
	}

    public function Kabupaten(Request $request)
	{
        $url = 'referensi/kabupaten/propinsi/'.$request->input('id_propinsi');
        return VclaimLib::exec('GET', $url);
	}

    public function Kecamatan(Request $request)
	{
		$url = 'referensi/kecamatan/kabupaten/'.$request->input('id_kabupaten');
		return VclaimLib::exec('GET', $url);
	}

	public function ProgramPrb()
	{
		return VclaimLib::exec('GET', 'referensi/diagnosaprb');
	}

	public function DokterDpjp(Request $request)
	{
		$tgl_pelayanan = Date('Y-m-d');
        $jnsPelayanan = $request->input('jns_pelayanan');
        $idSpesialis = $request->input('id_spesialis');

		if( $idSpesialis == 'IGD' ) $jnsPelayanan = 1;

		$url = 'referensi/dokter/pelayanan/'.$jnsPelayanan.'/tglPelayanan/'.$tgl_pelayanan.'/Spesialis/'.$idSpesialis;

		return VclaimLib::exec('GET', $url);
	}

	public function ObatPrb(Request $request)
	{
		$url = 'referensi/obatprb/'.$request->input('key');
		return VclaimLib::exec('GET', $url);
	}

}
