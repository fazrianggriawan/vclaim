<?php

namespace App\Http\Controllers;

use App\Http\Libraries\AppLib;
use Illuminate\Support\Facades\DB;

class Master extends Controller
{
    public function Poliklinik()
    {
        $data = DB::table('mst_poli_bpjs')->whereNotNull('prefix_antrian')->orderBy('ket')->get();
        return AppLib::response(200, $data);
    }

    public function GetPoliklinik($kodePoli)
    {
        $data = DB::table('mst_poli_bpjs')->where('kode', $kodePoli)->get();
        return $data[0];
    }


}
