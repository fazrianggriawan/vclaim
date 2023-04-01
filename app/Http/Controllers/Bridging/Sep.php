<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Http\Libraries\VclaimLib;
use Illuminate\Support\Facades\DB;

class Sep extends Controller
{

    public function Save()
    {
        $request = json_decode(file_get_contents("php://input"));

        $setting = VclaimLib::getSetting();

        $data['request']['t_sep'] =
            array(
                'noKartu'        => $request->noKartu,
                'tglSep'        => date('Y-m-d'),
                'ppkPelayanan' => VclaimLib::getPPK($setting),
                'jnsPelayanan' => $request->jnsPelayanan,
                'klsRawat'        => array(
                    "klsRawatHak"     => $request->hakKelas->kode,
                    "klsRawatNaik"    => $request->naikKelas,
                    "pembiayaan"      => $request->naikKelasPembiayaan,
                    "penanggungJawab" => $request->naikKelasPenanggungJawab
                ),
                'noMR'         => $request->norm,
                'rujukan'     => array(
                    'asalRujukan' => (isset($request->rujukan->asalFaskes)) ? $request->rujukan->asalFaskes : '1', // 1 = RS, 2 = Puskesmas
                    'tglRujukan'  => (isset($request->rujukan->rujukan->tglKunjungan)) ? $request->rujukan->rujukan->tglKunjungan : '',
                    'noRujukan'   => (isset($request->rujukan->rujukan->noKunjungan)) ? $request->rujukan->rujukan->noKunjungan : '',
                    'ppkRujukan'  => (isset($request->rujukan->rujukan->provPerujuk)) ? $request->rujukan->rujukan->provPerujuk->kode : VclaimLib::getPPK($setting)
                ),
                'catatan'          => $request->catatan,
                'diagAwal'          => $request->diagnosa->kode,
                'poli'              => array(
                    'tujuan'    => $request->poliklinik->kode,
                    'eksekutif' => $request->poliklinikEks,
                ),
                'cob'              => array(
                    'cob' => $request->cob,
                ),
                'katarak'          => array(
                    'katarak' => $request->katarak,
                ),
                'jaminan'          => array(
                    'lakaLantas'  => $request->isLakaLantas,
                    'noLP'  => $request->lakaNoLp,
                    'penjamin'    => array(
                        'tglKejadian' => $request->lakaTglKejadian,
                        'keterangan'  => $request->lakaKeterangan,
                        'suplesi'       => array(
                            'suplesi' => $request->lakaSuplesi,
                            'noSepSuplesi' => $request->lakaNoSuplesi,
                            'lokasiLaka' => array(
                                'kdPropinsi'  => $request->lakaPropinsi,
                                'kdKabupaten' => $request->lakaKabupaten,
                                'kdKecamatan' => $request->lakaKecamatan,
                            )
                        )
                    ),
                ),
                'tujuanKunj'    => $request->tujuanKunj,
                'flagProcedure' => $request->flagProcedure,
                'kdPenunjang'   => $request->kdPenunjang,
                'assesmentPel'  => $request->assessmentPel,
                'skdp'             => array(
                    'noSurat'  => (isset($request->skdp->noSuratKontrol)) ? $request->skdp->noSuratKontrol : '',
                    'kodeDPJP' => (isset($request->skdp->kodeDokter)) ? $request->skdp->kodeDokter : ''
                ),
                'dpjpLayan'     => $request->dokter->kode,
                'noTelp'         => $request->tlp,
                'user'             => 'Vclaim',
            );

        $response = VclaimLib::exec('POST', 'SEP/2.0/insert', json_encode($data));

        if( isset($request->kodeBooking) ){
            $this->saveSepRegistrasi($request->kodeBooking, $response, $request);
        }

        return $response;
    }

    public function SaveSepRegistrasi($kodeBooking, $response, $request)
    {
        if( isset($kodeBooking) ){
            $res = json_decode($response);
            if( $res->metaData->code == '200' ){
                $sep = $res->metaData->response->sep;
                if( strlen($sep->noSep) > 12 ){
                    $insertData = array(
                        'booking_code' => $kodeBooking,
                        'nomor' => $sep->noSep,
                        'no_bpjs' => $request->noKartu,
                        'sep' => json_encode($sep),
                        'dateCreated' => date('Y-m-d H:i:s')
                    );
                    DB::table('antrian_detail_sep')->insert($insertData);
                }
            }
        }
    }


    public function GetByNomorSep($nomorSep)
    {
        return VclaimLib::exec('GET', 'SEP/' . $nomorSep);
    }

    public function SepInternal($nomorSep)
    {
        return VclaimLib::exec('GET', 'SEP/Internal/' . $nomorSep);
    }

    public function Delete()
    {
        $request = json_decode(file_get_contents("php://input"));

        $data['request']['t_sep'] = array('noSep' => $request->noSep, 'user' => 'Vclaim');

        return VclaimLib::exec('DELETE', 'SEP/2.0/delete', json_encode($data));
    }

    public function DeleteSepInternal()
    {
        $request = json_decode(file_get_contents("php://input"));

        $data['request']['t_sep'] = array(
            'noSep' => $request->nosep,
            'noSurat' => $request->nosurat,
            'tglRujukanInternal' => $request->tglrujukinternal,
            'kdPoliTuj' => $request->kdpolituj,
            'user' => 'Vclaim'
        );

        return VclaimLib::exec('DELETE', 'SEP/Internal/delete', json_encode($data));
    }

    public function UpdatePasienPulang()
    {
        $request = json_decode(file_get_contents("php://input"));

        $data["request"]["t_sep"] = array(
            "noSep" =>  $request->noSep,
            "statusPulang" => $request->statusPulang,
            "noSuratMeninggal" => $request->noSuratMeninggal,
            "tglMeninggal" => $request->tglMeninggal,
            "tglPulang" => $request->tanggal,
            "noLPManual" => $request->noLp,
            "user" => 'VClaim'
        );

        return VclaimLib::exec('PUT', 'SEP/2.0/updtglplg', json_encode($data));
    }

    public function FingerPrint($nomor_kartu, $tanggal)
    {
        $url = 'SEP/FingerPrint/Peserta/'.$nomor_kartu.'/TglPelayanan/'.$tanggal;
        return VclaimLib::exec('GET', $url);
    }

}
