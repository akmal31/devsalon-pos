<!doctype html>
<html lang="en">

<?php $this->load->view("partial/v_html_header"); ?>

<body>
<div id="appCapsule" class="full-height">

    <div class="section mt-2" id="printArea">
        <div class="card">
            <div class="card-body">
                <h4 style="text-align:center;"><?=$cabang['name'];?></h4>
                <h5 style="text-align:center;"><?=$cabang['address'];?></h5>
                <h5 style="text-align:right;"><?=$tanggal_transaksi;?></h5>
                <?php foreach ($cart as $item): ?>
                        <div class="in">
                            <!-- BAGIAN KIRI -->
                            <div>
                                <h4><?= $item['item_name'] ?></h4>
                                <h5><?= $item['quantity'] ?> × Rp <?= number_format($item['price'],0,',','.') ?> — Diskon <?= $item['discount'] ?>%</h5>
                            </div>
                            <!-- BAGIAN KANAN (TEMPAT TOTAL) -->
                            <div style="text-align:right; min-width:110px;">
                                <b style="font-size:14px;">
                                    Rp <?= number_format($item['total'],0,',','.') ?>
                                </b>
                            </div>
                        </div>
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
</div>
<?php $this->load->view("partial/v_script_bottom"); ?>

</body>

</html>
