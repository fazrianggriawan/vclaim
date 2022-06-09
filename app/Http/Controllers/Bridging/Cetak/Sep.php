<?php

namespace App\Http\Controllers\Bridging\Cetak;

use App\Http\Controllers\Controller;
use App\Http\Libraries\PDFBarcode;
use App\Http\Libraries\VclaimLib;

class Sep extends Controller
{
    public function Index($nomorSep)
    {
        $data = json_decode( VclaimLib::exec('GET', 'SEP/'.$nomorSep) );
        if( $data ){
            if( $data->metaData->code == '200' ){
                $sep = $data->response;
                $this->doPrint($sep);
            }else{
                return $this->metaData->message;
                exit;
            }
        }else{
            return 'Terjadi Gangguan Pada Server BPJS.';
            exit;
        }
    }

    public function doPrint($sep)
    {
        header("Content-type:application/pdf");

		$pdf = new PDFBarcode();

		$border = 0;
		$heightCell = 6;
		$widthCell = 28;
		$widthCellData = 90;
		$widthCellData2 = 58;
		$fontWeight = '';

        $dokter = ($sep->dpjp->nmDPJP) ? $sep->dpjp->nmDPJP : $sep->kontrol->nmDokter;

		$pdf->AddPage();

		$pdf->SetFont('arial', 'B', 12);
		// $pdf->Cell($widthCell-5);
		$pdf->Cell(70, 5,'SURAT ELEGIBILITAS PASIEN', $border);
		$pdf->Ln(5);
		$pdf->Cell($widthCell);
		$pdf->Cell(70, 5,'', $border);

		$pdf->SetLeftMargin(5);
		$pdf->SetFont('arial', $fontWeight, 10);
		$pdf->Ln(10);
		$pdf->Cell($widthCell, $heightCell,'No.SEP', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$sep->noSep, $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'Tgl.SEP', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$sep->tglSep, $border);

		$pdf->Cell($widthCell, $heightCell,'Peserta', $border);
		$pdf->Cell($widthCellData2, $heightCell,': '.$sep->peserta->jnsPeserta, $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'No.Kartu', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$sep->peserta->noKartu.' ( MR. '.$sep->peserta->noMr.' )', $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'Nama Peserta', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$sep->peserta->nama, $border);
		$pdf->Cell($widthCell, $heightCell,'Jns.Rawat', $border);
		$pdf->Cell($widthCellData2, $heightCell,': '.$sep->jnsPelayanan, $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'Tgl.Lahir', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$sep->peserta->tglLahir.' Kelamin : '.$sep->peserta->kelamin, $border);
		$pdf->Cell($widthCell, $heightCell,'Jns.Kunjungan', $border);
		$pdf->Cell($widthCellData2, $heightCell,': ', $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'No.Telepon', $border);
		$pdf->Cell($widthCellData, $heightCell,': ', $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'Sub/Spesialis', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$sep->poli, $border);
		$pdf->Cell($widthCell, $heightCell,'Poli Perujuk', $border);
		$pdf->Cell($widthCellData2, $heightCell,': ', $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'Dokter', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$dokter, $border);
		$pdf->Cell($widthCell, $heightCell,'Kls.Hak', $border);
		$pdf->Cell($widthCellData2, $heightCell,': '.$sep->peserta->hakKelas, $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'Faskes Perujuk', $border);
		$pdf->Cell($widthCellData, $heightCell,': ', $border);
		$pdf->Cell($widthCell, $heightCell,'Kls.Rawat', $border);
		if( $sep->klsRawat->klsRawatNaik != '' ){
			$pdf->Cell($widthCellData2, $heightCell,': '.$this->_dataKelasRawat($sep->klsRawat->klsRawatNaik), $border);
		}else{
			$pdf->Cell($widthCellData2, $heightCell,': Kelas '.$sep->klsRawat->klsRawatHak, $border);
		}

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'Diagnosa Awal', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$sep->diagnosa, $border);
		$pdf->Cell($widthCell, $heightCell,'Penjamin', $border);
		$pdf->Cell($widthCellData2, $heightCell,': '.$sep->penjamin, $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'Catatan', $border);
		$pdf->Cell(176, $heightCell,': '.$sep->catatan, $border);

		$pdf->SetFont('arial', '', 9);
		$pdf->Ln(10);
		$pdf->Cell(140, 5,'* Saya menyetujui BPJS Kesehatan menggunakan infomasi medis pasien jika diperlukan.', $border);
		$pdf->SetFont('arial', $fontWeight, 10);
		$pdf->Cell(45, 5,'Pasien/Keluarga Pasien', $border);
		$pdf->Ln(5);
		$pdf->SetFont('arial', '', 9);
		$pdf->Cell(140, 5,'* SEP Bukan sebagai bukti penjaminan peserta.', $border);
		$pdf->Ln(5);
		$pdf->Cell(140, 5,'', $border);
		$pdf->Cell(45, 5,'_______________________', $border);

		$pdf->Output();
        exit;
    }

    public function _dataKelasRawat($var = null)
	{
		$data = array(
			'1' => 'VVIP',
			'2' => 'VIP',
			'3' => 'Kelas 1',
			'4' => 'Kelas 2',
			'5' => 'Kelas 3',
			'6' => 'ICCU',
			'7' => 'ICU',
		);
		return $data[$var];
	}


}
