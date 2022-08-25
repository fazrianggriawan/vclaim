<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Http\Libraries\VclaimLib;
use Illuminate\Http\Client\Request;

class Bercomp extends Controller
{
    public function SaveBilling(Request $request)
	{
        $insert = array(
            'tglMasuk' => '',
            'tglLapor' => '',
            'noreg' => '',
            'golpaskd' => '',
            'golpaskt' => '',
            'dokterkd' => '',
            'dokterkt' => '',
            'norekmed' => '',
            'nama' => '',            
            'kunci' => '',
            'antri' => '',
            'catat' => '',
            'lunas' => '',
            'polikd' => '',
            'polikt' => '',
            'perushkd' => '',
            'perushkt' => '',
            'tgpriksa' => '',
            'icdkd' => '',
            'icdkt' => '',
            'total' => '',
            'pemakai' => '',
            'shift' => '',
            'jam' => '',
            'tglin' => '',
            'potong' => '',
            'sisa' => '',
            'tarif' => '',
            'bayar' => '',
            'tagih' => '',
            'sr_recno' => '',
            'sr_deleted' => '',
        );		
	}

    public function SaveBillingDetail()
    {
        $insert = array(
            'tgl' => '',
            'tgllapor' => '',
            'noreg' => '',
            'golpaskd' => '',
            'golpaskt' => '',
            'tarifkd1' => '',
            'tarifkt1' => '',
            'tarifkd2' => '',
            'tarifkt2' => '',
            'tarifkd' => '',
            'tarifkt' => '',
            'dokterkd' => '',
            'dokterkt' => '',
            'nbagi1' => '',
            'nbagi2' => '',
            'nbagi3' => '',
            'nbagi4' => '',
            'nbagi5' => '',
            'nbagi6' => '',
            'ndokter' => '',
            'nperawat' => '',
            'nrsad' => '',
            'bisa' => '',
            'norekmed' => '',
            'catat' => '',
            'nama' => '',
            'billing' => '',
            'lunas' => '',
            'polikd' => '',            
            'polikt' => '',
            'pemakai' => '',
            'shift' => '',
            'jam' => '',
            'tglin' => '',
            'qty' => '',
            'tarif' => '',
            'bayar' => '',
            'tagih' => '',
            'sr_recno' => '',
            'sr_deleted' => ''
        );
    }
}
