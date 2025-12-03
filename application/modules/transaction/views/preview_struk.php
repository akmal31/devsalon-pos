<!doctype html>
<html lang="en">

<?php $this->load->view("partial/v_html_header"); ?>

<body>
<style>
@media print {

    /* Sembunyikan semua elemen */
    body * {
        visibility: hidden;
    }

    /* Tampilkan hanya printArea */
    #printArea, #printArea * {
        visibility: visible;
    }

    /* Posisi di paling atas halaman saat print */
    #printArea {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }

    /* Sembunyikan button print supaya tidak tampil di PDF */
    #btnPrint,
    #btnKembali,
    .appHeader,
    .section.mt-1,
    .menu-bottom {
        display: none !important;
    }
}
</style>

<?php $this->load->view("partial/v_loader"); ?>

<div class="appHeader bg-primary text-light">
    <div class="pageTitle">
        <img src="<?php echo base_url(); ?>assets/img/loading-icon.png" alt="logo" class="logo">
    </div>
</div>

<div id="appCapsule" class="full-height">
    <div id="printArea" style="background-color: white;">
        <div>
            <div>
                <div style="text-align:center;color:black">
                    <img src="<?= base_url(); ?>assets/img/<?=$cabang['logo'];?>" width="300px">
                    <h1 style="text-align:center;"><?=$cabang['name'];?></h1>
                    <h1 style="text-align:center;"><?=$cabang['address'];?></h1>
                    <h1><?php echo date("j F Y H:i", strtotime($tanggal_transaksi));?></h1>
                    <p>==================================================================</p>
                </div>
                <ul class="listview image-listview">
                <?php foreach ($cart as $item): ?>
                    <li>
                        <a href="#" class="item" style="padding:0px;">
                            <div class="in">

                                <!-- BAGIAN KIRI -->
                                <div>
                                    <header style="font-size:35px;padding:10px;font-weight:bold;"><?= $item['item_name'] ?></header>
                                    <header style="font-size:32px;padding-left:10px;">
                                        <?= $item['quantity'] ?> × Rp <?= number_format($item['price'],0,',','.') ?>
                                        <br> Diskon <?= $item['discount'] ?>%
                </header>
                                </div>

                                <!-- BAGIAN KANAN (TEMPAT TOTAL) -->
                                <div style="text-align:right; min-width:110px;">
                                    <b style="font-size:35px;font-weight:bold;">
                                        Rp <?= number_format($item['total'],0,',','.') ?>
                                    </b>
                                </div>

                            </div>
                        </a>
                    </li>
                    <div style="text-align:center;">
                        <p>==================================================================</p>
                    </div>
                <?php endforeach; ?>
                <table style="width:100%; font-size:35px; margin-top:10px; line-height:1.6;">
                    <tr>
                        <td style="text-align:left; font-weight:bold;">Grand Total</td>
                        <td style="text-align:right; font-weight:bold;">
                            Rp <?= number_format($grand_total,0,',','.') ?>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align:left;">Metode</td>
                        <td style="text-align:right;">
                            <?= $metode_bayar ?>
                        </td>
                    </tr>

                    <?php if ($tips > 0): ?>
                    <tr>
                        <td style="text-align:left;">Tips</td>
                        <td style="text-align:right;">
                            Rp <?= number_format($tips,0,',','.') ?>
                        </td>
                    </tr>
                    <?php endif; ?>

                    <?php if ($metode_bayar == 'cash'): ?>
                    <tr>
                        <td style="text-align:left;">Uang Cash</td>
                        <td style="text-align:right;">
                            Rp <?= number_format($uang_bayar,0,',','.') ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:left;">Kembali</td>
                        <td style="text-align:right;">
                            Rp <?= number_format($kembalian,0,',','.') ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                </table>
                <br>
                <div style="text-align:center;">
                    <p>==================================================================</p>
                </div>
                <h1 style="text-align:center;color:black;">
                    Terima kasih telah mempercayai perawatan kecantikan Anda <br>kepada <?=$cabang['name'];?>.<br><br>
                    Follow kami di Instagram dan Tiktok <?=$cabang['instagram'];?> untuk promo & info terbaru.
                </h1>
            </div>
        </div>
    </div>

    <div style="text-align:center; margin-top:20px;">
        <button id="btnPrint" onclick="window.print()" class="btn btn-primary">Print Struk</button>
        <a id="btnKembali" href="<?=base_url('homepage');?>" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<?php $this->load->view("partial/v_menu_bottom"); ?>
<?php $this->load->view("partial/v_script_bottom"); ?>

</body>

</html>
