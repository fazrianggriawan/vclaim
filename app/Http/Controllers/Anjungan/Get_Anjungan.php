<?php

namespace App\Http\Controllers\Anjungan;

use App\Http\Controllers\Controller;
use App\Http\Libraries\VclaimLib;
use Illuminate\Http\Client\Request;

class Get_Anjungan extends Controller
{
    public function DataLpk(Request $request)
	{
		$url = 'LPK/TglMasuk/'.$request->input('tglMasuk').'/JnsPelayanan/'.$request->input('jnsPelayanan');
        return VclaimLib::exec('GET', $url);
	}
}
