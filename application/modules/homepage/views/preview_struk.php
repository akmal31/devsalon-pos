<!doctype html>
<html lang="en">

<?php $this->load->view("partial/v_html_header"); ?>

<body>
<?php $this->load->view("partial/v_loader"); ?>

<div class="appHeader bg-primary text-light">
    <div class="pageTitle">
        <img src="<?php echo base_url(); ?>assets/img/loading-icon.png" alt="logo" class="logo">
    </div>
</div>

<div id="appCapsule" class="full-height">

    <div class="section mt-1">
        <div class="section-title">Ringkasan Transaksi</div>
    </div>

    <div class="section mt-2">
        <div class="card">
            <div class="card-body">
                <div style="text-align:center;">
                    <img src="<?= base_url(); ?>assets/img/<?=$cabang['logo'];?>" width="100px">
                </div>
                <h4 style="text-align:center;"><?=$cabang['name'];?></h4>
                <h5 style="text-align:center;"><?=$cabang['address'];?></h5>
                <ul class="listview image-listview">
                <?php foreach ($cart as $item): ?>
                    <li>
                        <a href="#" class="item" style="padding:0px;">
                            <div class="in">

                                <!-- BAGIAN KIRI -->
                                <div>
                                    <header><?= $item['item_name'] ?></header>
                                    <footer>
                                        <?= $item['quantity'] ?> × Rp <?= number_format($item['price'],0,',','.') ?>
                                        — Diskon <?= $item['discount'] ?>%
                                    </footer>
                                </div>

                                <!-- BAGIAN KANAN (TEMPAT TOTAL) -->
                                <div style="text-align:right; min-width:110px;">
                                    <b style="font-size:14px;">
                                        Rp <?= number_format($item['total'],0,',','.') ?>
                                    </b>
                                </div>

                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
                <hr>
                <table style="width:100%; font-size:15px; margin-top:10px; line-height:1.6;">
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


                <hr>
                <h6 style="text-align:center;color:grey;">
                    Terima kasih telah mempercayai perawatan kecantikan Anda kepada <?=$cabang['name'];?>.<br>
                    Follow kami di Instagram dan Tiktok <?=$cabang['instagram'];?> untuk promo & info terbaru.
                </h6>
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
