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

    // Set default ke hari ini
    const today = new Date().toISOString().split('T')[0];
    filterTanggal.value = today;

    // Function load transaksi
    function loadTransactions(tanggal) {
        fetch('<?= base_url("transaction/filterTransactions") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ date: tanggal })
        })
        .then(res => res.json())
        .then(data => {
            let html = '';

            if(data.length > 0){
                data.forEach(tr => {
                    let icon = 'logo-octocat';
                    switch(tr.payment_method){
                        case 'cash': icon = 'cash-outline'; break;
                        case 'qris': icon = 'qr-code-outline'; break;
                        case 'card': icon = 'card-outline'; break;
                    }

                    html += `
                    <a href="<?= base_url('homepage/preview_struk/') ?>${tr.id}" class="item">
                        <div class="detail">
                            <div class="image-block imaged w48" style="font-size:40px;">
                                <ion-icon name="${icon}"></ion-icon>
                            </div>
                            <div>
                                <strong>${tr.customer_name || 'Guest'}</strong>
                                <p>${new Date(tr.created_at).toLocaleDateString('id-ID', {day: 'numeric', month:'long', year:'numeric'})} 
                                ${new Date(tr.created_at).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'})}
                                </p>
                            </div>
                        </div>
                        <div class="right">
                            <div class="price text-success">Rp ${Number(tr.grand_total).toLocaleString('id-ID')}</div>
                        </div>
                    </a>
                    `;
                });
            } else {
                html = '<p class="text-center"><ion-icon name="telescope-outline" style="font-size: 60px;"></ion-icon><br>- Belum ada transaksi -</p>';
            }

            transactionsContainer.innerHTML = html;
        })
        .catch(err => console.error(err));
    }

    // Load default transaksi hari ini
    loadTransactions(today);

    // Event: filter tanggal
    filterTanggal.addEventListener('change', function() {
        loadTransactions(this.value);
    });
</script>


</html>