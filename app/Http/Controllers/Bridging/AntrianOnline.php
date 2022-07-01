<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Master;
use App\Http\Libraries\AntrolLib;
use App\Http\Libraries\AppLib;
use Illuminate\Support\Facades\DB;

class AntrianOnline extends Controller
{
    public function Save()
	{
        $request = json_decode(file_get_contents("php://input"));

        $dataAntrian = $this->SaveAntrian($request);
        if( !$dataAntrian ){
            return AppLib::response(201, array(), 'Data Gagal Disimpan');
        }

        $nomorAntrian = DB::table('antrian')->where('booking_code', $dataAntrian['booking_code'])->get();
        $totalKuota = 80;

        $data = array(
            "kodebooking" => $dataAntrian['booking_code'],
            "jenispasien" => $dataAntrian['jns_pasien'],
            "nomorkartu" => $dataAntrian['no_kartu_bpjs'],
            "nik" => $dataAntrian['nik'],
            "nohp" => $dataAntrian['hp'],
            "kodepoli" => $request->jadwalDokter->kodepoli,
            "namapoli" => $request->jadwalDokter->namapoli,
            "pasienbaru" => 0,
            "norm" => $dataAntrian['norm'],
            "tanggalperiksa" => $dataAntrian['tgl_kunjungan'],
            "kodedokter" => $dataAntrian['kodedokter_bpjs'],
            "namadokter" => $request->jadwalDokter->namadokter,
            "jampraktek" => $dataAntrian['jam_praktek'],
            "jeniskunjungan" => $dataAntrian['jns_kunjungan'],
            "nomorreferensi" => $dataAntrian['no_referensi'],
            "nomorantrean" => $dataAntrian['prefix_antrian'].'-'.$nomorAntrian[0]->no_antrian,
            "angkaantrean" => $nomorAntrian[0]->no_antrian,
            "estimasidilayani" => $this->EstimasiWaktuDilayani($dataAntrian['jam_praktek'], $nomorAntrian[0]->no_antrian, $dataAntrian['tgl_kunjungan']),
            "sisakuotajkn" => ($totalKuota - $nomorAntrian[0]->no_antrian),
            "kuotajkn" => $totalKuota,
            "sisakuotanonjkn" => ($totalKuota - $nomorAntrian[0]->no_antrian),
            "kuotanonjkn" => $totalKuota,
            "keterangan" => "Peserta harap 30 menit lebih awal guna pencatatan administrasi."
        );

		$url = 'antrean/add';
        $response = AntrolLib::exec('POST', $url, json_encode($data));

        $dataResponse = json_decode($response);

        if( $dataResponse->metadata->code == 200 ){
            $response = array(
                'metadata' => array('code'=>200, 'message'=>'Ok'),
                'response' => $data
            );

            return json_encode($response);
        }else{
            return $response;
        }
	}

    private function GenerateKodeBooking()
    {
        $bytes = random_bytes(3);
        return strtoupper(bin2hex($bytes));
    }

    private function SaveAntrian($request)
    {
        $kodeBooking = $this->GenerateKodeBooking();
        $dataPoli = Master::GetPoliklinik($request->jadwalDokter->kodepoli);
        $queryNoAntrian = '(SELECT COALESCE (MAX(aa.no_antrian)+1, 11) AS nomor_antrian FROM antrian AS aa WHERE aa.poli = "'.$request->jadwalDokter->kodepoli.'" AND aa.tgl_kunjungan = "'.$request->jadwalDokter->tglKunjungan.'")';

        $dataInsert = array(
            'booking_code' => $kodeBooking,
            'nama' => $request->pasien->nama,
            'tgl_kunjungan' => $request->jadwalDokter->tglKunjungan,
            'prefix_antrian' => $dataPoli->prefix_antrian,
            'no_antrian' => DB::raw($queryNoAntrian),
            'poli' => $request->jadwalDokter->kodepoli,
            'jns_pasien' => (($request->jenisPembayaran == 'bpjs') ? 'JKN' : 'NON JKN'),
            'no_kartu_bpjs' => $request->pasien->noaskes,
            'norm' => substr($request->pasien->norekmed, -6),
            'no_referensi' => (isset($request->rujukan->noKunjungan)) ? $request->rujukan->noKunjungan : '',
            'jns_kunjungan' => $request->jenisKunjungan->kode,
            'jam_praktek' => $request->jadwalDokter->jadwal,
            'kodedokter_bpjs' => $request->jadwalDokter->kodedokter,
            'hp' => (isset($request->rujukan->peserta->mr->noTelepon)) ? $request->rujukan->peserta->mr->noTelepon : '',
            'nik' => (isset($request->rujukan->peserta->nik)) ? $request->rujukan->peserta->nik : ''
        );
        $insert = DB::table('antrian')->insert($dataInsert);
        return ( $insert ) ? $dataInsert : FALSE;
    }

    private function EstimasiWaktuDilayani($jadwal, $noAntrian, $tglKunjungan)
	{
		$explodeJadwal = explode('-', $jadwal);
		$jam_mulai = $explodeJadwal[0];
		$addTime = $noAntrian * 10; // Setiap pasien 10 menit
		$time = $tglKunjungan.' '.$jam_mulai.':00';
		return strtotime($time.'+'.$addTime.'minutes') * 1000;
	}

}