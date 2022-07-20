<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Http\Libraries\VclaimLib;
use App\Http\Libraries\AntrolLib;
use Illuminate\Http\Client\Request;

class Referensi extends Controller
{
    public function Diagnosa($keyword)
    {
        $url = 'referensi/diagnosa/' . $keyword;
        return VclaimLib::exec('GET', $url);
    }

    public function Poliklinik($keyword)
    {
        return VclaimLib::exec('GET', 'referensi/poli/' . $keyword);
    }

    public function Faskes($keyword)
    {
        $url = 'referensi/faskes/' . $keyword . '/2';
        return VclaimLib::exec('GET', $url);
    }

    public function Procedure(Request $request)
    {
        $url = 'referensi/procedure/' . $request->input('key');
        return VclaimLib::exec('GET', $url);
    }

    public function KelasRawat()
    {
        return VclaimLib::exec('GET', 'referensi/kelasrawat');
    }

    public function Dokter(Request $request)
    {
        $url = 'referensi/dokter/' . $request->input('key');
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
        $url = 'referensi/kabupaten/propinsi/' . $request->input('id_propinsi');
        return VclaimLib::exec('GET', $url);
    }

    public function Kecamatan(Request $request)
    {
        $url = 'referensi/kecamatan/kabupaten/' . $request->input('id_kabupaten');
        return VclaimLib::exec('GET', $url);
    }

    public function ProgramPrb()
    {
        return VclaimLib::exec('GET', 'referensi/diagnosaprb');
    }

    public function DokterDpjp($jnsPelayanan, $poliklinik)
    {
        $tgl_pelayanan = Date('Y-m-d');

        $url = 'referensi/dokter/pelayanan/' . $jnsPelayanan . '/tglPelayanan/' . $tgl_pelayanan . '/Spesialis/' . $poliklinik;

        return VclaimLib::exec('GET', $url);
    }

    public function ObatPrb(Request $request)
    {
        $url = 'referensi/obatprb/' . $request->input('key');
        return VclaimLib::exec('GET', $url);
    }

    public function JadwalDokter($kodePoli, $tglKunjungan)
    {
        if ($tglKunjungan == 'now') {
            $tglKunjungan =  date('Y-m-d');
        }
        $url = 'jadwaldokter/kodepoli/' . $kodePoli . '/tanggal/' . $tglKunjungan;
        return AntrolLib::exec('GET', $url);
    }
}
