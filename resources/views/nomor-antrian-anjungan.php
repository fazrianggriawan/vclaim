<style>
    .container {
        position: fixed;
        width: 100%;
        height: 100%;
        top: 5px;
        left: 5px;
        font-family: Arial, Helvetica, sans-serif;
        line-height: 16px;
        text-align: center;
    }
</style>
<div class="container">
    <div style="font-size: 14px; font-weight: bold; margin-bottom: 5px;">
        <div>RUMAH SAKIT SALAK - BOGOR</div>
        <? if( $tipe == 'tni' ){ ?><div>TNI / KELUARGA TNI / PURNAWIRAWAN</div> <? } ?>
        <? if( $tipe == 'bpjs' ){ ?><div>BPJS KESEHATAN</div> <? } ?>
        <? if( $tipe == 'tunai' ){ ?><div>PASIEN UMUM</div> <? } ?>
    </div>
    <div style="font-size: 16px; font-weight: bold; margin-bottom: 10px; margin-top: 10px;">
        <div>NOMOR ANTRIAN</div>
    </div>

    <div style="font-size: 32px; font-weight: bold; margin-bottom: 20px;">
        <div><? echo $noAntrian ?></div>
    </div>

    <div style="font-size: 12px;">
        <? if( $tipe == 'tunai' ){ ?> <div>Silahkan Menunggu di Loket 1 atau 2</div> <? } ?>
        <? if( $tipe == 'bpjs' ){ ?> <div>Silahkan Menunggu di Loket 3 atau 4</div> <? } ?>
        <? if( $tipe == 'tni' ){ ?> <div>Silahkan Menunggu di Loket 5 atau 6</div> <? } ?>

        <div>Tgl. Cetak : <? echo date('d-m-Y')?> Jam : <? echo date('H:i:s') ?></div>
    </div>
</div>

<script>
    window.print();
</script>