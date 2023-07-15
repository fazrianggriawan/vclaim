<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Antrian extends Model
{
    protected $table        = 'antrian';
    protected $primaryKey   = 'id';
    protected $connection   = 'mysql_online';

    public function antrian_detail()
    {
        return $this->hasOne(Antrian_Detail::class, 'idAntrian', 'id');
    }

    public function antrian_detail_sep()
    {
        return $this->hasOne(Antrian_Detail::class, 'idAntrian', 'id');
    }

    public static function SaveAntrian($registrasi, $post)
	{

        $qNoAntrian = self::QueryGenerateNomorAntrian($registrasi->poli, $registrasi->tglKunjungan);

        $insert = DB::table('antrian')->insert([
            'booking_code' => $registrasi->bookingCode,
            'tgl_kunjungan' => $registrasi->tglKunjungan,
            'no_antrian' => DB::raw($qNoAntrian),
            'prefix_antrian' => $registrasi->prefixAntrian,
            'poli' => $registrasi->poli,
            'jns_pasien' => $registrasi->jnsPasien,
            'nama' => $post->pasien->nama,
            'norm' => $post->pasien->norm,
            'no_kartu_bpjs' => $post->pasien->noKartu,
            'jns_kunjungan' => $post->registrasi->jnsKunjungan,
            'jam_praktek' => $post->dokter->jadwal,
            'kodedokter_bpjs' => $post->dokter->kodedokter,
        ]);

		return ($insert) ? TRUE : FALSE ;
	}

    public static function IsRegisteredAntrian($post)
    {
        $data = DB::table('antrian')
					->where('no_kartu_bpjs', $post->pasien->noKartu)
                    ->where('tgl_kunjungan', date('Y-m-d'))
					->where('poli', $post->selectedPoli->kode)
					->get();

		return (count($data) > 0) ? TRUE : FALSE ;
    }

    public static function GetPoliBpjs($kodepoli)
	{
        return DB::table('mst_poli_bpjs')->where('kode', $kodepoli)->get();
	}

    public static function GetByKodeBooking($kodeBooking)
	{
        return DB::table('antrian')->where('booking_code', $kodeBooking)->get();
	}

    public static function GetTotalKuota($data)
	{
        return DB::table('antrian')
                ->where('jns_pasien', $data->jnsPasien)
                ->where('poli', $data->poli)
                ->where('tgl_kunjungan', $data->tglKunjungan)
		        ->get();
	}

    public static function QueryGenerateNomorAntrian($poli, $tglKunjungan)
	{
		return '(SELECT
                    COALESCE (MAX(aa.no_antrian)+1, 11)
                FROM
                    antrian AS aa
                WHERE
                    aa.poli = "'.$poli.'" AND aa.tgl_kunjungan = "'.$tglKunjungan.'")';
	}

    public static function GenerateKodeBooking()
	{
		$bytes = random_bytes(3);
        return strtoupper(bin2hex($bytes));
	}

}
