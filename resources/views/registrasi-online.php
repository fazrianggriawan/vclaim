<?php
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
        width: 100%;
        height: 100%;
        top: 5px;
        left: 5px;
        font-family: Arial, Helvetica, sans-serif;
        line-height: 16px;
    }

    table tr td{
        font-size: 12px;
    }
</style>
<div class="container">
    <div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;">
        <div>DATA REGISTRASI ONLINE</div>
    </div>
    <table cellpadding="0" cellspacing="1" border="0">
        <tr>
            <td width="100">Kode Booking</td>
            <td>: <?= $registrasi->booking_code ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: <?= $jadwalDokter->tglKunjungan ?></td>
        </tr>
        <tr>
            <td>No.RM</td>
            <td>: <?= substr($pasien->norekmed, -6) ?></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>: <?= $pasien->nama ?></td>
        </tr>
        <tr>
            <td>No.BPJS</td>
            <td>: <?= $pasien->noaskes ?></td>
        </tr>
        <tr>
            <td>Poliklinik</td>
            <td>: <?= $jadwalDokter->namapoli ?></td>
        </tr>
        <tr>
            <td>Dokter</td>
            <td>: <?= $jadwalDokter->namadokter ?></td>
        </tr>
    </table>
</div>

<script>
    window.print();
</script>