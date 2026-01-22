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

        <!-- Transactions -->
        <div class="section mt-4">
            <div class="section-heading">
                <h2 class="title">Semua Transaksi</h2>
            </div>
            <div class="section-heading">
                <input type="date" class="form-control" id="filter_tanggal">
            </div>
            <div class="transactions" id="transactionsContainer"><p class="text-center"><ion-icon name="telescope-outline" style="font-size: 60px;"></ion-icon><br>- Belum ada transaksi -</p></div>
        </div>

    </div>
    <!-- * App Capsule -->


    <!-- App Bottom Menu -->
    <?php $this->load->view("partial/v_menu_bottom"); ?>
    <!-- * App Bottom Menu -->

    <?php $this->load->view("partial/v_script_bottom"); ?>

</body>

<script>
    const filterTanggal = document.getElementById('filter_tanggal');
    const transactionsContainer = document.getElementById('transactionsContainer');

    // default hari ini
    const today = new Date().toISOString().split('T')[0];
    filterTanggal.value = today;

    // ==============================
    // LOAD TRANSAKSI
    // ==============================
    function loadTransactions(tanggal) {
        fetch('<?= base_url("transaction/filterTransactions") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ date: tanggal })
        })
        .then(res => res.json())
        .then(data => {
            let html = '';

            if (data && data.length > 0) {
                data.forEach(tr => {
                    let icon = 'cash-outline';
                    if (tr.payment_method === 'qris') icon = 'qr-code-outline';
                    if (tr.payment_method === 'card') icon = 'card-outline';

                    html += `
                    <div class="item d-flex justify-content-between align-items-center" style="padding-right:10px">

                        <!-- PREVIEW -->
                        <a href="<?= base_url('transaction/preview_struk/') ?>${tr.id}"
                           class="flex-grow-1 text-decoration-none text-dark">
                            <div class="detail d-flex align-items-center">
                                <div class="image-block imaged w48" style="font-size:40px">
                                    <ion-icon name="${icon}"></ion-icon>
                                </div>
                                <div>
                                    <strong>${tr.customer_name || 'Guest'}</strong>
                                    <strong class="text-primary" style="font-size:12px">
                                        ${tr.outlet_name.trim().split(/\s+/).pop()}
                                    </strong>
                                    <p class="mb-0 text-muted">
                                        ${new Date(tr.created_at).toLocaleDateString('id-ID')}
                                        ${new Date(tr.created_at).toLocaleTimeString('id-ID', {
                                            hour: '2-digit',
                                            minute: '2-digit'
                                        })}
                                    </p>
                                </div>
                            </div>
                        </a>

                        <!-- HARGA -->
                        <div class="right text-end me-1">
                            <div class="price text-success" style="font-size:12px">
                                Rp ${Number(tr.grand_total).toLocaleString('id-ID')}
                            </div>
                        </div>

                        <?php if ($user_profile['USER_GROUP_ID'] == 1): ?>
                        <!-- DELETE -->
                        <button class="btn btn-sm btn-danger"
                            onclick="deleteTransaction(event, ${tr.id})">
                            <ion-icon name="trash-outline"></ion-icon>
                        </button>
                        <?php endif; ?>

                    </div>`;
                });
            } else {
                html = `
                <p class="text-center">
                    <ion-icon name="telescope-outline" style="font-size:60px"></ion-icon><br>
                    - Belum ada transaksi -
                </p>`;
            }

            transactionsContainer.innerHTML = html;
        })
        .catch(err => {
            console.error(err);
            transactionsContainer.innerHTML =
                '<p class="text-center text-danger">Gagal memuat data</p>';
        });
    }

    // ==============================
    // DELETE TRANSAKSI
    // ==============================
    function deleteTransaction(e, id) {
        e.preventDefault();
        e.stopPropagation();

        if (!confirm('Yakin hapus transaksi ini?')) return;

        fetch(`<?= base_url('transaction/delete/') ?>${id}`, {
            method: 'POST' // aman untuk CI
        })
        .then(res => res.json())
        .then(res => {
            if (res.status) {
                alert(res.message);
                loadTransactions(filterTanggal.value); // reload langsung
            } else {
                alert(res.message || 'Gagal menghapus transaksi 1');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan sistem');
        });
    }

    // ==============================
    // INIT
    // ==============================
    loadTransactions(today);

    filterTanggal.addEventListener('change', function () {
        loadTransactions(this.value);
    });
</script>

</html>