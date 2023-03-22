<?php

namespace App\Http\Libraries;

use Illuminate\Support\Facades\DB;
use LZCompressor\LZString;

const ID_SETTING 	= 1;
const API_TYPE 		= 'antrol'; // vclaim or antrol
const CONFIGURATION = 'production'; // dev or production

class AntrolLib
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public static function getConsId($setting)
	{
		return $setting->cons_id;
	}

    public static function getUserkey($setting)
	{
		return $setting->userkey;
	}

	public static function getUrl($setting)
	{
		return $setting->rest_api;
	}

	public static function getPPK($setting)
	{
		return $setting->ppk;
	}

    public static function getSecretKey($setting)
	{
		return $setting->secretkey;
	}

    public static function getTimestamp()
    {
		date_default_timezone_set('UTC');
        return strval(time()-strtotime('1970-01-01 00:00:00'));
    }

    public static function getDescryptKey($setting)
    {
        return self::getConsId($setting).self::getSecretKey($setting).self::getTimestamp();
    }

	public static function getSignature($setting)
	{
		return hash_hmac('sha256', self::getConsId($setting)."&".self::getTimestamp(), self::getSecretKey($setting), true);
	}

    public static function getEncodedSignature($setting)
	{
        $signature = self::getSignature($setting);
        return base64_encode($signature);
	}

    public static function getSetting()
    {
        $data = DB::table('setting_bridging_bpjs')
                ->where('id_setting', ID_SETTING)
                ->where('api_type', API_TYPE)
                ->where('configuration', CONFIGURATION)
                ->get();
        return $data[0];
    }

	public static function exec($method, $URL, $jsonData='', $return=FALSE, $debug=FALSE)
	{

        $setting = self::getSetting();

		$URL = self::getUrl($setting).'/'.$URL;

		$timeStamp = self::getTimestamp();
		$arrayHeader[0] = "X-cons-id: ".self::getConsId($setting);
		$arrayHeader[1] = "X-timestamp:".self::getTimestamp();
		$arrayHeader[2] = "X-signature: ".self::getEncodedSignature($setting);
		$arrayHeader[3] = "user_key: ".self::getUserkey($setting);

		$decryptKey = self::getConsId($setting).self::getSecretKey($setting).$timeStamp;

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
