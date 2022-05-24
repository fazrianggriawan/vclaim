<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Http\Libraries\VclaimLib;

class Sep extends Controller
{
    public function GetByNomorSep($nomorSep)
	{
        return VclaimLib::exec('GET', 'SEP/'.$nomorSep);
	}
}
