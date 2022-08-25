<style>
    .container {
        position: fixed;
        width: 270px;
        height: 100%;
        top: 0;
        left: 0;
        font-family: Arial, Helvetica, sans-serif;
        line-height: 16px;
    }
</style>
<div class="container">
    <div style="font-size: 14px; font-weight: bold; margin-bottom: 5px; text-align: center;">
        <div>RUMAH SAKIT SALAK - BOGOR</div>
        <?php if( $tipe == 'tni' ){ ?><div>TNI / KELUARGA TNI / PURNAWIRAWAN</div> <?php } ?>
        <?php if( $tipe == 'bpjs' ){ ?><div>BPJS KESEHATAN</div> <?php } ?>
        <?php if( $tipe == 'tunai' ){ ?><div>PASIEN UMUM</div> <?php } ?>

        <div style="font-size: 16px; font-weight: bold; margin-bottom: 10px; margin-top: 10px;">
            <div>NOMOR ANTRIAN</div>
        </div>

        <div style="font-size: 32px; font-weight: bold; margin-bottom: 20px;"><?php echo $noAntrian ?></div>

        <div style="font-size: 12px;">
            <?php if( $tipe == 'tunai' ){ ?> <div>Silahkan Menunggu di Loket 1 atau 2</div> <?php } ?>
            <?php if( $tipe == 'bpjs' ){ ?> <div>Silahkan Menunggu di Loket 3 atau 4</div> <?php } ?>
            <?php if( $tipe == 'tni' ){ ?> <div>Silahkan Menunggu di Loket 5 atau 6</div> <?php } ?>

            <div>Tgl. Cetak : <?php echo date('d-m-Y')?> Jam : <?php echo date('H:i:s') ?></div>
        </div>

    </div>

    <div style="width: 300px; text-align:right;">.</div>
</div>

<script>
    window.print();
</script>