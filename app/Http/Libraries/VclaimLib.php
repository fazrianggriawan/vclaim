<?php

namespace App\Http\Libraries;

use LZCompressor\LZString;

// use \LZCompressor\LZString;

/*
	Bersama ini kami sampaikan akses integrasi aplikasi VClaim Production melalui web service 2.0 dengan aplikasi SIM FKRTL dengan informasi sebagai berikut:

KodePPK - Nama PPK : 2501R006 - RS Tk II Prof dr J.A LATUMETEN
Consid - Secretkey : 31907 - 1mR60EB812

[PRODUCTION]
2501R006 RS Tk II Prof dr J.A LATUMETEN
user_key: f7b2fe33396725df35fdb770addabd11
	*/

// SALAK
// const URL = 'https://apijkn.bpjs-kesehatan.go.id/vclaim-rest';
// const CONS_ID = '24903';
// const SECRETKEY = '2kT3ADA426';
// const PPK = '1003R002';
// const USERKEY = '160373608a1a918cefd6fbc41bac4fd2';

// AMBON
const URL = 'https://apijkn.bpjs-kesehatan.go.id/vclaim-rest';
const CONS_ID = '31907';
const SECRETKEY = '1mR60EB812';
const PPK = '2501R006';
const USERKEY = 'f7b2fe33396725df35fdb770addabd11';

class VclaimLib
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

	public static function getUrl()
	{
		return URL;
	}

	public static function getPPK()
	{
		return PPK;
	}

    public static function getTimestamp()
    {
		date_default_timezone_set('UTC');
        return strval(time()-strtotime('1970-01-01 00:00:00'));
    }

    public static function getDescryptKey()
    {
        return CONS_ID.SECRETKEY.self::getTimestamp();
    }

	public static function getSignature()
	{
		return hash_hmac('sha256', CONS_ID."&".self::getTimestamp(), SECRETKEY, true);
	}

    public static function getEncodedSignature()
	{
        $signature = self::getSignature();
        return base64_encode($signature);
	}

	public static function exec($method, $URL, $jsonData='', $return=FALSE, $debug=FALSE)
	{
		$URL = self::getUrl().'/'.$URL;

		$timeStamp = self::getTimestamp();
		$arrayHeader[0] = "X-cons-id: ".CONS_ID;
		$arrayHeader[1] = "X-timestamp:".self::getTimestamp();
		$arrayHeader[2] = "X-signature: ".self::getEncodedSignature();
		$arrayHeader[3] = "user_key: ".USERKEY;

		$decryptKey = CONS_ID.SECRETKEY.$timeStamp;

		if( strtoupper($method) == 'GET' ){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $URL);

		}elseif( strtoupper($method) == 'POST' ){

			$ch = curl_init($URL);
			$arrayHeader[4] = "Content-Type: application/x‐www‐form‐urlencoded";
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

		}elseif( strtoupper($method) == 'PUT' ){

			$ch = curl_init($URL);
			$arrayHeader[4] = "Content-Type: application/x‐www‐form‐urlencoded";
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
			curl_setopt($ch, CURLOPT_URL, $URL);

		}elseif( strtoupper($method) == 'DELETE' ){

			$ch = curl_init($URL);
			$arrayHeader[4] = "Content-Type: application/x‐www‐form‐urlencoded";
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
			curl_setopt($ch, CURLOPT_URL, $URL);
		}

		curl_setopt($ch, CURLOPT_HTTPHEADER, $arrayHeader);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$res = curl_exec($ch);

		return self::response($res, $decryptKey);

	}

	public static function response($res, $decryptKey)
	{
		$data = json_decode($res);
		if( isset($data->response) ){
			$decrypt = self::stringDecrypt( $decryptKey , $data->response );
			$data->response = json_decode( self::decompress($decrypt) );
		}
		return json_encode($data);
	}

	public static function stringDecrypt($key, $string)
	{
		$encrypt_method = 'AES-256-CBC';
        // hash
		$key_hash = hex2bin(hash('sha256', $key));
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hex2bin(hash('sha256', $key)), 0, 16);

		return openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
	}

	public static function decompress($string)
	{
		return LZString::decompressFromEncodedURIComponent($string);
	}
}
