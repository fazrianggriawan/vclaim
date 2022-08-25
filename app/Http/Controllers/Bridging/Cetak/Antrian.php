<?php

namespace App\Http\Controllers\Bridging\Cetak;

use App\Http\Controllers\Controller;

class Antrian extends Controller
{
    public function NomorAntrian($tipe, $noAntrian)
    {
        return view('nomor-antrian-anjungan', ['tipe'=>$tipe, 'noAntrian'=>$noAntrian]);
    }


}
