<?php

use App\Http\Libraries\AppLib;

function dataKelasRawat($var = null)
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
?>
<style>
    .container {
        position: fixed;
        width: 270px;
        height: 100%;
        top: 0px;
        left: 0px;
        font-family: Arial, Helvetica, sans-serif;
        line-height: 16px;
    }

    table tr td{
        font-size: 12px;
        vertical-align: top;
    }
</style>
<div class="container">
    <div style="font-size: 18px; font-weight: bold; margin-bottom: 5px; text-align: center">
        <div>RSPAD GATOT SOEBROTO</div>
        <div style="margin-top: 5px;">TIKET REGISTRASI</div>
    </div>
    <hr>
    <div style="text-align: center;">
        <div style="font-size: 16px; padding: 5px;">
            ANTRIAN POLIKLINIK
            <div><?= strtoupper($jadwalDokter->namasubspesialis) ?></div>
        </div>
        <div style="font-size: 20px; font-weight: bold; margin-bottom: 10px; margin-top: 5px;"><?= $registrasi->prefix_antrian ?>-<?= $registrasi->no_antrian ?></div>
        <hr>
        <div style="text-align: center;"><?= $barcode ?></div>
        <div style="font-size: 16px; padding: 10px;">KODE BOOKING</div>
        <div style="font-size: 18px; font-weight: bold;"><?= $kodeBooking ?></div>
    </div>
    <hr>
    <table cellpadding="0" cellspacing="1" border="0" style="font-size: 14px;">
        <tr>
            <td>Tanggal</td>
            <td width="10" style="text-align: center;">:</td>
            <td><?= AppLib::dateHuman($jadwalDokter->tglKunjungan) ?></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td style="text-align: center;">:</td>
            <td><?= $pasien->nama ?></td>
        </tr>
        <tr>
            <td>No. RM</td>
            <td style="text-align: center;">:</td>
            <td><?= $pasien->norekmed ?></td>
        </tr>
        <tr>
            <td>No. BPJS</td>
            <td style="text-align: center;">:</td>
            <td><?= $pasien->noaskes ?></td>
        </tr>
        <tr>
            <td>No. Rujukan</td>
            <td style="text-align: center;">:</td>
            <td><?= $rujukan->noKunjungan ?></td>
        </tr>
        <tr>
            <td>No. SEP</td>
            <td style="text-align: center;">:</td>
            <td><?= (isset($sep->noSep)) ? $sep->noSep : '-' ?></td>
        </tr>
        <tr>
            <td>Poliklinik</td>
            <td style="text-align: center;">:</td>
            <td><?= strtoupper($jadwalDokter->namasubspesialis) ?></td>
        </tr>
        <tr>
            <td>Dokter</td>
            <td style="text-align: center;">:</td>
            <td><?= $jadwalDokter->namadokter ?></td>
        </tr>
        <tr>
            <td>Jadwal Praktek</td>
            <td style="text-align: center;">:</td>
            <td><?= $jadwalDokter->jadwal ?></td>
        </tr>
    </table>
    <div style="width: 300px; text-align: right;">.</div>
</div>

<script>
    window.print();
</script>