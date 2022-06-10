<?php

namespace App\Http\Controllers\Bridging\Cetak;

use App\Http\Controllers\Controller;
use App\Http\Libraries\PDFBarcode;
use App\Http\Libraries\VclaimLib;
use App\Http\Controllers\Bridging\Rujukan as RujukanController;
use DateInterval;
use DateTime;
use stdClass;

class Rujukan extends Controller
{
    public function Index($nomorRujukan)
    {
        $data = RujukanController::GetByNomorRujukan($nomorRujukan);

        if( $data ){
            $data = json_decode($data);
            if( $data->metaData->code == '200' ){
                $this->doPrint($data->response);
            }else{
                return $this->metaData->message;
            }
        }else{
            return 'Terjadi Gangguan Pada Server BPJS.';
        }
    }

    public function RujukanKeluar($data)
    {
        $data = json_decode(base64_decode($data));
        if( $data ){
            $data->rujukan->tglKunjungan = $data->rujukan->tglRujukan;
            $data->rujukan->noKunjungan = $data->rujukan->noRujukan;
            $data->rujukan->poliRujukan = $data->rujukan->poliTujuan;
            $data->rujukan->provPerujuk = $data->rujukan->AsalRujukan;
            $data->rujukan->peserta->sex = $data->rujukan->peserta->kelamin;
            // print_r($data);
            // exit;
            $this->doPrint($data);
        }else{
            return 'Terjadi Gangguan Pada Server BPJS.';
        }
    }

    public function doPrint($data)
    {
        header("Content-type:application/pdf");

		$pdf = new PDFBarcode();
        $pdf->AddPage();

		$border = 0;
		$heightCell = 6;
		$widthCell = 28;
		$widthCellData = 80;
		$widthCellData2 = 58;
		$fontWeight = '';

        $date = new DateTime($data->rujukan->tglKunjungan); // Y-m-d
        $date->add(new DateInterval('P90D'));
        $tglBerlaku =  $date->format('Y-m-d');

        $pdf->SetFont('arial', 'B', 12);
		$pdf->Cell(80, 5,'SURAT RUJUKAN', $border);
        $pdf->Cell($widthCell);
		$pdf->Cell($widthCellData-20, $heightCell,'No. '.$data->rujukan->noKunjungan, $border);
		$pdf->Ln(5);
		$pdf->Cell($widthCellData, 5,'RS Tk II Prof dr J.A LATUMETEN', $border);
        $pdf->Cell($widthCell);
		$pdf->SetFont('arial', '', 10);
		$pdf->Cell($widthCellData-20, $heightCell,'Tgl. '.$this->mailDateFormat($data->rujukan->tglKunjungan), $border);

		$pdf->SetFont('arial', $fontWeight, 10);
		$pdf->Ln(10);
		$pdf->Cell($widthCell, $heightCell, 'Kepada Yth', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$data->rujukan->poliRujukan->nama, $border);
		$pdf->Ln();
		$pdf->Cell($widthCell+2, $heightCell, '', $border);
		$pdf->Cell($widthCellData-2, $heightCell, $data->rujukan->provPerujuk->nama, $border);
		$pdf->Cell($widthCellData-40, $heightCell, '', $border);
		$pdf->Ln(8);
		$pdf->Cell($widthCellData+28, $heightCell, 'Mohon Pemeriksaan dan Penanganan Lebih Lanjut :', $border);
		$pdf->Cell($widthCellData-40, $heightCell, strtoupper(@$data->rujukan->pelayanan->nama), $border);
		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'No. Kartu', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$data->rujukan->peserta->noKartu, $border);
		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'Nama Peserta', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$data->rujukan->peserta->nama.' ('.$data->rujukan->peserta->sex.')', $border);
		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'Tgl.Lahir', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$this->mailDateFormat($data->rujukan->peserta->tglLahir), $border);
		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'Diagnosa', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$data->rujukan->diagnosa->kode.' '.$data->rujukan->diagnosa->nama, $border);
		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'Keterangan', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.@$data->rujukan->keluhan, $border);
		$pdf->Ln(10);
		$pdf->Cell($widthCell+90, $heightCell,'Demikian atas bantuannya,diucapkan banyak terima kasih.', $border);
		$pdf->Ln(10);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell($widthCell+90, $heightCell,'* Rujukan Berlaku Sampai Dengan '.$this->mailDateFormat($tglBerlaku), $border);
		$pdf->Ln();
		$pdf->Cell($widthCell+90, $heightCell,'* Tgl.Rencana Berkunjung '.$this->mailDateFormat($data->rujukan->tglKunjungan), $border);

		$pdf->SetFont('arial', $fontWeight, 10);
		$pdf->Cell($widthCell+20, $heightCell,'Mengetahui,', $border);
		$pdf->Ln(10);
		$pdf->Cell($widthCell+90, $heightCell,'', $border);
		$pdf->Cell($widthCell+20, $heightCell,'_______________________', $border);

		$pdf->Output();
        exit;
    }

    public function mailDateFormat($date){
        $hari = substr($date, 8, 2);
        $bulan = substr($date, 5, 2);
        $tahun  = substr($date, 0, 4);

        $namabulan = array(
                        '01' => 'Januari',
                        '02' => 'Februari',
                        '03' => 'Maret',
                        '04' => 'April',
                        '05' => 'Mei',
                        '06' => 'Juni',
                        '07' => 'Juli',
                        '08' => 'Agustus',
                        '09' => 'September',
                        '10' => 'Oktober',
                        '11' => 'November',
                        '12' => 'Desember',
                );

        return $hari.' '.($namabulan[$bulan]).' '.$tahun ;

    }

}
