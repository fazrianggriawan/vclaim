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

function dateHuman($date)
{
    $arrDate = explode('-', $date);
    return $arrDate[2] . '-' . $arrDate[1] . '-' . $arrDate[0];
}
?>
<style>
    .container {
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0px;
        left: 0px;
        font-family: Arial, Helvetica, sans-serif;
        line-height: 16px;
    }

    table tr td {
        font-size: 12px;
    }
</style>
<div class="container">
    <div style="font-size: 16px; font-weight: bold;">
        <div>SURAT ELEGIBILITAS PASIEN</div>
        <div>RSPAD GATOT SOEBROTO</div>
    </div>
    <table cellpadding="0" cellspacing="1" border="0">
        <tr>
            <td width="120">No.SEP</td>
            <td width="300">: <?= $sep->noSep ?></td>
        </tr>
        <tr>
            <td>Tgl.SEP</td>
            <td>: <?= dateHuman($sep->tglSep) ?></td>
            <td width="100">Peserta</td>
            <td>: <?= $sep->peserta->jnsPeserta ?></td>
        </tr>
        <tr>
            <td>No.Kartu</td>
            <td>: <?= $sep->peserta->noKartu ?></td>
            <td>Jns.Rawat</td>
            <td>: <?= strtoupper($sep->jnsPelayanan) ?></td>
        </tr>
        <tr>
            <td>Nama Peserta</td>
            <td>: <?= $sep->peserta->nama ?></td>
            <td>Jns.Kunjungan</td>
            <td>:
                <?php if (isset($registrasi->jns_kunjungan)) {
                    if ($registrasi->jns_kunjungan == '3') {
                        echo 'RENCANA KONTROL';
                    }
                    if ($registrasi->jns_kunjungan == '2') {
                        echo 'RUJUKAN INTERNAL';
                    }
                    if ($registrasi->jns_kunjungan == '1') {
                        echo 'RUJUKAN BARU';
                    }
                } ?>
            </td>
        </tr>
        <tr>
            <td>Tgl.Lahir</td>
            <td>: <?= dateHuman($sep->peserta->tglLahir) ?></td>
            <td>Poli Perujuk</td>
            <td>: <?= $rujukan->poliRujukan->nama; ?></td>
        </tr>
        <tr>
            <td>No.Telepon</td>
            <td>: <?= $rujukan->peserta->mr->noTelepon ?></td>
            <td>Kls.Hak</td>
            <td>: <?= strtoupper($sep->peserta->hakKelas) ?></td>
        </tr>
        <tr>
            <td>Sub/Spesialis</td>
            <td>: <?= $sep->poli ?></td>
            <td>Kls.Rawat</td>
            <td>: <?= ($sep->klsRawat->klsRawatNaik != '') ? $this->dataKelasRawat($sep->klsRawat->klsRawatNaik) : 'KELAS ' . $sep->klsRawat->klsRawatHak ?></td>
        </tr>
        <tr>
            <td>Dokter</td>
            <td>: <?= $sep->dpjp->nmDPJP ?></td>
            <td>Penjamin</td>
            <td>: <?= $sep->penjamin ?></td>
        </tr>
        <tr>
            <td>Faskes Perujuk</td>
            <td>: <?= $rujukan->provPerujuk->nama ?></td>
        </tr>
        <tr>
            <td>Diagnosa Awal</td>
            <td colspan="3">: <?= strtoupper($sep->diagnosa) ?></td>
        </tr>
        <tr>
            <td>Catatan</td>
            <td colspan="3">: <?= $sep->catatan ?></td>
        </tr>
        <tr>
            <td colspan="3">
                <div>* Saya menyetujui BPJS Kesehatan menggunakan infomasi medis pasien jika diperlukan.</div>
                <div>* SEP Bukan sebagai bukti penjaminan peserta.</div>
            </td>
            <td>
                <div>Pasien/Keluarga Pasien</div>
                <div style="margin-top: 30px;">
                    ______________________
                    <div style="width: 180px; text-align: right; margin-top: -10px;">.</div>
                </div>
            </td>
        </tr>
    </table>
</div>

<script>
    window.print();
</script>