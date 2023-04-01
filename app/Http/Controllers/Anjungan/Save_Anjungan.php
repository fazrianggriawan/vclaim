<?php

namespace App\Http\Controllers\Anjungan;

use App\Http\Controllers\Controller;
use App\Http\Libraries\AppLib;
use App\Http\Libraries\VclaimLib;
use Illuminate\Http\Request;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Save_Anjungan extends Controller
{
    public function SendToFingerPrint(Request $request)
    {
        try {
            $connection = new AMQPStreamConnection('rspadonline.bertek.co.id', 5672, 'guest', 'guest');
            $channel = $connection->channel();

            $ip  = $_SERVER['REMOTE_ADDR'];

            $channel->queue_declare("$ip", false, false, false, false);

            $msg = new AMQPMessage($request->nomor_kartu);
            $channel->basic_publish($msg, '', "$ip");
            $channel->close();
            $connection->close();

            return AppLib::response(200, [], 'Berhasil mengirim nomor kartu '.$request->nomor_kartu);
        } catch (\Throwable $th) {
            return AppLib::response(201, [], 'Gagal mengirim '.$th->getMessage());
        }
    }
}
