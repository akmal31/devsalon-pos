<!doctype html>
<html lang="en">
    <?php $this->load->view("partial/v_html_header"); ?>
<body>
    <?php $this->load->view("partial/v_loader"); ?>
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="pageTitle">
            <img src="<?php echo base_url(); ?>assets/img/loading-icon.png" alt="logo" class="logo">
        </div>
    </div>
    <!-- * App Header -->


    <!-- App Capsule -->
    <div id="appCapsule">

        <!-- Wallet Card -->
        <div class="section wallet-card-section pt-2">
            <div class="wallet-card pt-4">
                <!-- Balance -->
                <div class="text-center">
                    <div>
                        <h3>Selamat datang bos <?=$user_profile['USERNAME'];?></h3>
                    </div>
                </div>
                <!-- * Balance -->
                <!-- Wallet Footer -->
                <div class="wallet-footer pt-1"></div>
                <!-- * Wallet Footer -->
            </div>
        </div>
        <!-- Wallet Card -->

        <!-- Stats -->
        <div class="section mt-5">
            <div class="section-heading">
                <h2 class="title">Ringkasan Hari ini</h2>
            </div>
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label" for="select4b">Pilih Cabang</label> 
                    <select id="filterCabang" class="form-control custom-select">
                        <option value="">Semua Cabang</option>
                        <?php foreach ($listCabang as $c): ?>
                            <option value="<?= $c['outlet_id'] ?>" 
                                <?= ($selectedOutlet == $c['outlet_id'] ? 'selected' : '') ?>>
                                <?= $c['name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <hr>
            <div class="row mt-2">
                <div class="col-6">
                    <div class="stat-box">
                        <div class="">Pemasukan</div>
                        <div class="text-success">Rp. <?= number_format($todayRevenue, 0, ',', '.') ?></div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-box">
                        <div class="">Pengeluaran</div>
                        <div class="text-danger">Rp. <?= number_format($todayExpense, 0, ',', '.') ?></div>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-6">
                    <div class="stat-box">
                        <div class="">Customer</div>
                        <div class="text-success"><?= $todayCustomer ?></div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-box">
                        <div class="">Total Cash di Laci</div>
                        <div class="text-success">Rp <?= number_format($dataCabang['cash_laci'],0,',','.') ?></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Stats -->

        <!-- Transactions -->
        <div class="section mt-4">
            <div class="section-heading">
                <h2 class="title">Transaksi Hari ini</h2>
                <a href="<?=base_url().'transaction';?>" class="link">View All</a>
            </div>
            <div class="transactions">
                <?php if(!empty($transactions)): ?>
                    <?php foreach($transactions as $tr): ?>
                        <?php
                            // Tentukan icon sesuai payment_method
                            switch($tr['payment_method']){
                                case 'cash': // Cash
                                    $icon = 'cash-outline';
                                    break;
                                case 'qris': // QRIS
                                    $icon = 'qr-code-outline';
                                    break;
                                case 'card': // Card
                                    $icon = 'card-outline';
                                    break;
                                default:
                                    $icon = 'logo-octocat'; // default
                            }
                        ?>
                        <a href="<?= base_url('transaction/preview_struk/'.$tr['id']) ?>" class="item">
                            <div class="detail">
                                <div class="image-block imaged w48" style="font-size:40px;">
                                    <ion-icon name="<?= $icon ?>"></ion-icon>
                                </div>
                                <div>
                                    <strong><?= $tr['customer_name'] ?? 'Guest' ?></strong>
                                    <p><?= date('H:i', strtotime($tr['created_at'])) ?></p>
                                </div>
                            </div>
                            <div class="right">
                                <div class="price text-success">Rp <?= number_format($tr['grand_total'],0,',','.') ?></div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center">Belum ada transaksi hari ini</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
    <!-- * App Capsule -->


    <!-- App Bottom Menu -->
    <?php $this->load->view("partial/v_menu_bottom"); ?>
    <!-- * App Bottom Menu -->

    <?php $this->load->view("partial/v_script_bottom"); ?>

</body>

<script>
document.getElementById('filterCabang').addEventListener('change', function() {
    let outlet = this.value;
    let url = "<?= base_url('homepage') ?>";

    if (outlet) {
        window.location.href = url + "?outlet_id=" + outlet;
    } else {
        window.location.href = url; // semua cabang
    }
});
</script>


</html>