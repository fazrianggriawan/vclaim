<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Master;
use App\Http\Libraries\AntrolLib;
use App\Http\Libraries\AppLib;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AntrianOnline extends Controller
{

    public function Save()
	{
        $request = json_decode(file_get_contents("php://input"));

        DB::beginTransaction();

        $dataAntrian = $this->SaveAntrian($request);

        $nomorAntrian = DB::table('antrian')->where('booking_code', $dataAntrian['booking_code'])->get();
        $this->SaveAntrianDetail($request, $nomorAntrian);

        DB::commit();

        if( !$dataAntrian ){
            return AppLib::response(201, array(), 'Data Gagal Disimpan');
        }

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

    private function SaveAntrianDetail($request, $dataAntrian) {
        $dataInsert = array(
            'idAntrian' => $dataAntrian[0]->id,
            'pasien' => json_encode($request->pasien),
            'rujukan' => json_encode($request->rujukan),
            'suratKontrol' => json_encode($request->suratKontrol),
            'jadwalDokter' => json_encode($request->jadwalDokter),
            'dateCreated' => date('Y-m-d H:i:s')
        );

        if( $request->suratKontrol != '' ){
            $this->saveDetailSuratKontrol($request->suratKontrol, $dataAntrian);
        }

        $this->saveDetailRujukan($request->rujukan, $dataAntrian);
        $this->saveDetailJadwal($request->jadwalDokter, $dataAntrian);

        $insert = DB::table('antrian_detail')->insert($dataInsert);

        return ( $insert ) ? $dataInsert : FALSE;
    }

    private function saveDetailSuratKontrol($data, $dataAntrian) {
        $insertData = array(
            'booking_code' => $dataAntrian[0]->booking_code,
            'nomor' => $data->noSuratKontrol,
            'surat_kontrol' => json_encode($data),
            'dateCreated' => date('Y-m-d H:i:s')
        );
        DB::table('antrian_detail_surat_kontrol')->insert($insertData);
    }

    private function saveDetailRujukan($data, $dataAntrian) {
        $insertData = array(
            'booking_code' => $dataAntrian[0]->booking_code,
            'nomor' => $data->noKunjungan,
            'rujukan' => json_encode($data),
            'dateCreated' => date('Y-m-d H:i:s')
        );
        DB::table('antrian_detail_rujukan')->insert($insertData);
    }

    private function saveDetailJadwal($data, $dataAntrian) {
        $insertData = array(
            'booking_code' => $dataAntrian[0]->booking_code,
            'jadwal_dokter' => json_encode($data),
            'dateCreated' => date('Y-m-d H:i:s')
        );
        DB::table('antrian_detail_jadwal_dokter')->insert($insertData);
    }

    private function EstimasiWaktuDilayani($jadwal, $noAntrian, $tglKunjungan)
	{
		$explodeJadwal = explode('-', $jadwal);
		$jam_mulai = $explodeJadwal[0];
		$addTime = $noAntrian * 10; // Setiap pasien 10 menit
		$time = $tglKunjungan.' '.$jam_mulai.':00';
		return strtotime($time.'+'.$addTime.'minutes') * 1000;
	}

    public function GetAntrian($kodeBooking)
    {
        $data = DB::table('antrian')
                    ->leftJoin('antrian_detail', 'antrian_detail.idAntrian', '=', 'antrian.id')
                    ->where('booking_code', $kodeBooking)
                    ->get();
        if( count($data) > 0 ){
            return AppLib::response(200, $data[0], 'Success');
        }else{
            return AppLib::response(201, array(), 'Data Tidak Ditemukan');
        }
    }

    public function FilterData()
    {
        $request = json_decode(file_get_contents("php://input"));

        $data = DB::table('antrian')
                ->leftJoin('antrian_detail', 'antrian_detail.idAntrian', '=', 'antrian.id')
                ->where('poli', $request->poli)
                ->where('tgl_kunjungan', date('Y-m-d'))
                ->where('jam_praktek', $request->jadwal)
                ->get();

        return AppLib::response(200, $data, 'Success');
    }

    public function CheckIn()
    {
        date_default_timezone_set("Asia/Jakarta");

        $post = json_decode(file_get_contents("php://input"));

        $isCheckedIn = DB::table('antrian_checkin')->where('booking_code', $post->kodebooking)->get();

        if( count($isCheckedIn) > 0 ){
            return AppLib::response(201, [], 'Data sudah check in pada '.$isCheckedIn[0]->dateCreated.'. Tidak dapet check in lagi.');
        }else{
            $data = array(
                'booking_code' => $post->kodebooking,
                'tgl_kunjungan' => $post->tanggalperiksa,
                'dateCreated' => date('Y-m-d H:i:s')
            );

            $insert = DB::table('antrian_checkin')->insert($data);

            if( $insert ){
                return AppLib::response(200, [], 'Sukses');
            }else{
                return AppLib::response(201, [], 'Data gagal disimpan');
            }
        }
    }

    public function AntrianByJadwal($jamPraktek, $kodePoli, $tglKunjungan)
    {
        $data = DB::table('antrian')
                ->select('antrian.*')
                ->leftJoin('antrian_checkin', 'antrian.booking_code', '=', 'antrian_checkin.booking_code')
                ->leftJoin('antrian_call', 'antrian_call.id_antrian', '=', 'antrian.id')
                ->where('antrian.jam_praktek', $jamPraktek)
                ->where('antrian.poli', $kodePoli)
                ->where('antrian.tgl_kunjungan', $tglKunjungan)
                ->get();

        return AppLib::response(200, $data, 'sukses');
    }

    public function DashboardAntrian()
	{
		date_default_timezone_set('Asia/Jakarta');

		if( date('H') < 12 ){
            $jadwal = '<';
        }else{
            $jadwal = '>';
        }

        $antrian = self::_antrian_by_poli();

        print_r($antrian);
        exit;

		$res = $data = array();

		foreach ($antrian as $key => $value) {
			$data = array(
				'poli' => $value,
				'waiting' => self::_antrian_by_poli($value->kode, 'waiting'),
				'called' => self::_antrian_by_poli($value->kode, 'called'),
			);

			if( count($data['waiting']) > 0 )
				$data['waiting']['estimasi'] = self::_estimasi_jam_layan(date('Y-m-d H:i:s'), 2);

			if( count($data['called']) > 0 )
				$data['called']['estimasi'] = self::_estimasi_jam_layan($data['called'][0]->call_time, 10);

			$res['antrian'][] = $data;
		}

		$res['info'] = array(
			'tanggal' => date('d M Y, H:i'),
			'jadwal' => ( date('H') < 12 ) ? 'POLI PAGI (08:00-12:00)' : 'POLI SORE (14:00-20:00)'
		);

        return AppLib::response(200, $res, 'sukses');

	}

    public function _antrian_by_poli($idPoli='', $status_call='')
    {
        if( date('H') < 12 ){
            $jadwal = '<';
        }else{
            $jadwal = '>';
        }

        $tanggal = "'".date('Y-m-d')."'";

        $antrian = DB::table('antriana')
                ->select('mst_ruangan.name as nama_poli', 'mst_poli.prefix_antrian', 'mst_poli.kode_bpjs as kode', 'antrian.no_antrian', 'antrian_call.call_time')
                ->leftJoin('antrian_call', 'antrian_call.id_antrian', '=', 'antrian.id')
                ->leftJoin('mst_poli', 'mst_poli.kode_bpjs', '=', 'antrian.poli')
                ->leftJoin('mst_ruangan', 'mst_ruangan.id', '=', 'mst_poli.id_ruangan')
                ->where('antrian.tgl_kunjungan', $tanggal)
                ->where(DB::raw('LEFT( antrian.jam_praktek, 5 )'), $jadwal, ' \'12:00\' ');

        if( $idPoli != '' ){
            $antrian->where('antrian.poli', $idPoli);
        }

        if( $status_call == 'waiting' ){
			$antrian->where('antrian_call.call_time');
			$antrian->orderBy('antrian.no_antrian', 'asc');
		}

		if( $status_call == 'called' ){
			$antrian->where('antrian_call.call_time', '<>', '');
			$antrian->orderBy('antrian.no_antrian', 'desc');
		}

        return $antrian->orderBy('prefix_antrian', 'asc')->groupBy('antrian.poli')->get();
    }

    public function _estimasi_jam_layan($time, $addTime)
	{
		$time = new DateTime($time);
		$time->add(new DateInterval('PT' . $addTime . 'M'));
		return $time->format('H:i');
	}

    public function SaveToSimrs(Request $request)
    {
        try {
            $data = array(
                "nama" => $request->pasien['nama'],
                "noaskes" => $request->pasien[''],
                "poliklinik" => "UROLOGI",
                "norm" => $request->pasien['norekmed'],
                "id_poliklinik" => '',
                "tanggal" => "2023-03-24",
                "str_tanggal" => "2023-03-24",
                "id_pasien" => "10996999",
                "type" => "BPJS",
                "rs_id" => 1,
                "golpas_id" => "004",
                "kunjunganke" => 0,
                "surkon" => "",
                "idDPJP" => "",
                "no_rujukan" => "",
                "polisurkon" => "",
                "token" => "608558",
                "noantrian" => "B8-11",
                "type" => "B8",
                "id_andro_sesi" => ""
            );
        } catch (\Throwable $th) {
            return AppLib::response(201, [], $th->getMessage());
        }
    }

    public function SaveAfterSep(Request $request)
    {
        try {
        } catch (\Throwable $th) {
            return AppLib::response(201, [], $th->getMessage());
        }
    }

}
