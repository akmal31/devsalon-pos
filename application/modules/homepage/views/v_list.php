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
                        <h3><?=$dataCabang['name'];?></h3>
                        <a href="homepage/add_transaction" class="btn btn-primary btn-block btn-lg">
                            <ion-icon name="add-outline"></ion-icon>Tambah Transaksi
                        </a>
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
        <div class="section mt-4">
            <div class="section-heading">
                <h2 class="title">Ringkasan Hari ini</h2>
            </div>
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
                        <div class="">Total Cash Laci</div>
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
                        <a href="#" class="item" onclick="openTransactionPopup(<?= $tr['id'] ?>)">
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
        <div class="modal fade dialogbox" id="DialogSummary" data-bs-backdrop="static" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header"></div>

                    <div class="modal-body mb-0" id="summaryBody">
                        <!-- isi dinamis -->
                    </div>

                    <div class="modal-footer">
                        <div class="btn-list">
                            <a href="#" class="btn btn-text-danger btn-block" data-bs-dismiss="modal">
                                <ion-icon name="close-outline"></ion-icon> TUTUP
                            </a>
                        </div>
                    </div>

                </div>
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
    function openTransactionPopup(id) {
        let html = `
            <p>Pilih aksi untuk transaksi #${id}</p>
            <div class="btn-list">
                <a href="#" class="btn btn-text-primary btn-block" onclick="openSummary(${id})">
                    <ion-icon name="create-outline"></ion-icon> Ringkasan & Komisi
                </a>

                <a href="#" class="btn btn-text-success btn-block" onclick="openStruk(${id})">
                    <ion-icon name="receipt-outline"></ion-icon> Lihat Struk
                </a>
            </div>
        `;

        document.getElementById("summaryBody").innerHTML = html;

        let mdl = new bootstrap.Modal(document.getElementById("DialogSummary"));
        mdl.show();
    }

    function cleanNumber(str) {
        if (!str) return 0;
        return str.toString().replace(/\./g, '');
    }

    function formatRupiahInput(el) {
        let val = el.value.replace(/\D/g, ""); // hapus semua non-digit
        if (!val) {
            el.value = "";
            return;
        }

        el.value = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // OPEN RINGKASAN + KOMISI (fixed)
    function openSummary(id) {
        fetch(`<?=base_url('homepage/ringkasan_transaksi_saved/')?>${id}`)
        .then(res => {
            if (!res.ok) throw new Error("Network response was not ok");
            return res.json();
        })
        .then(json => {
            if (!json || !json.status || !json.data) {
                document.getElementById("summaryBody").innerHTML = "<p class='text-danger'>Data tidak tersedia.</p>";
                return;
            }

            let tr = json.data;

            // safe: kalau details kosong
            const details = Array.isArray(tr.details) ? tr.details : [];

            let rows = details.map(item => {
                // pastikan staff adalah array
                const staffArr = Array.isArray(item.staff) ? item.staff : [];

                // total komisi item ini (parse angka aman)
                let totalKomisi = staffArr.reduce((acc, st) => {
                    const k = Number(st.price || st.price === 0 ? st.price : 0);
                    return acc + (isNaN(k) ? 0 : k);
                }, 0);

                // style merah kalau lebih besar dari harga
                const priceNum = Number(item.total || 0);
                let warn = totalKomisi > priceNum ? 'color:red;font-weight:bold' : '';

                // staff rows — gunakan properti staff_name & staff_id sesuai backend
                let staffRows = staffArr.map(st => {
                    const staffName = st.name ?? "Unknown";
                    const staffId   = st.user_id ?? "";
                    const priceVal  = numberWithCommas(st.price ?? 0);
                    return `
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div>${escapeHtml(staffName)}</div>
                            <div style="width:120px">
                                <input type="text" class="form-control form-control-sm komisi-input rupiah-input"
                                    data-detail="${item.detail_id}" 
                                    data-staff="${staffId}"
                                    value="${priceVal}">
                            </div>
                        </div>
                    `;
                }).join("");

                return `
                        <div class="item-block mb-3 p-2 bg-light rounded">
                            <div class="d-flex justify-content-between mb-1">
                            <strong>${escapeHtml(item.item_name)}</strong>
                            <strong class="item-price" data-raw="${priceNum}">Rp ${numberWithCommas(priceNum)}</strong>
                        </div><hr>
                        <div class="komisi-validation mb-2"></div>
                        <div>
                            ${staffRows || '<small class="text-muted">Belum ada staff</small>'}
                        </div>
                    </div>
                `;
            }).join("");

            document.getElementById("summaryBody").innerHTML = `
                <h5>${escapeHtml(tr.customer_name || "-")}</h5>
                <hr>
                ${rows}
            `;

            // Pasang event listener untuk validasi realtime komisi per input
            document.querySelectorAll('.komisi-input').forEach(inp => {
                inp.addEventListener('input', function () {
                    const detailId = this.dataset.detail;
                    // re-calc total komisi for this detail
                    const inputs = document.querySelectorAll(`.komisi-input[data-detail="${detailId}"]`);
                    let sum = 0;
                    inputs.forEach(i => {
                        const v = Number(cleanNumber(i.value)) || 0;
                        sum += v;
                    });

                    // ambil harga item dari DOM (search sibling strong text) — safer: you can keep price in data attribute
                    // untuk sekarang: update the Total Komisi text inside the same .item-block
                    const itemBlock = this.closest('.item-block');
                    const priceText = itemBlock.querySelector('div.d-flex strong:nth-child(2)')?.textContent || "";
                    const priceNum = parseInt( (priceText.match(/[\d\.]+/)||["0"])[0].replace(/\./g,"") ) || 0;

                    const totalKomisiEl = itemBlock.querySelector('.text-end');
                    if (sum > priceNum) {
                        totalKomisiEl.style.color = 'red';
                        totalKomisiEl.style.fontWeight = '700';
                    } else {
                        totalKomisiEl.style.color = '';
                        totalKomisiEl.style.fontWeight = '';
                    }
                    totalKomisiEl.innerHTML = `Total Komisi: Rp ${numberWithCommas(sum)}`;
                });
            });

            document.querySelectorAll('.rupiah-input').forEach(inp => {
                inp.addEventListener('keyup', function () {
                    formatRupiahInput(this);
                    this.dispatchEvent(new Event('input')); // supaya total komisi ke-update
                });
            });

            document.getElementById("summaryBody").innerHTML = `
                <h5>${escapeHtml(tr.customer_name || "-")}</h5>
                <hr>
                ${rows}
                <hr>
                <button id="btnSaveKomisi" class="btn btn-primary w-100" onclick="saveKomisi(${id})" disabled>Simpan Komisi</button>
            `;

            validateAllBlocksInitial();
            checkAllKomisiValid();

        })
        .catch(err => {
            console.error(err);
            document.getElementById("summaryBody").innerHTML = `<p class="text-danger">Gagal memuat ringkasan: ${escapeHtml(err.message)}</p>`;
        });
    }

    function validateAllBlocksInitial() {
        const blocks = document.querySelectorAll(".item-block");

        blocks.forEach(block => {
            let price = Number(block.querySelector(".item-price").dataset.raw);

            let total = 0;
            block.querySelectorAll(".komisi-input").forEach(inp => {
                let clean = cleanNumber(inp.value);
                total += Number(clean || 0);
            });

            let info = block.querySelector(".komisi-validation");

            if (total === price) {
                info.innerHTML = `<span class="text-success fw-bold">✔ Total sesuai</span>`;
            } else {
                info.innerHTML = `
                    <span class="text-danger fw-bold">
                        ✘ Total komisi Rp ${numberWithCommas(total)} ≠ Rp ${numberWithCommas(price)}
                    </span>
                `;
            }
        });
    }

    function checkAllKomisiValid() {
        const blocks = document.querySelectorAll(".item-block");
        let allValid = true;

        blocks.forEach(block => {
            const price = Number(block.querySelector(".item-price").dataset.raw);
            let total = 0;

            block.querySelectorAll(".komisi-input").forEach(inp => {
                total += Number(cleanNumber(inp.value) || 0);
            });

            if (total !== price) {
                allValid = false;
            }
        });

        document.getElementById("btnSaveKomisi").disabled = !allValid;
    }

    function saveKomisi(transactionId) {
        let payload = [];

        document.querySelectorAll(".komisi-input").forEach(inp => {
            let detailId = inp.dataset.detail;
            let staffId  = inp.dataset.staff;
            let price    = Number(cleanNumber(inp.value)) || 0;

            payload.push({
                transaction_detail_id: detailId,
                staff_id: staffId,
                price: price
            });
        });

        fetch("<?= base_url('homepage/update_komisi') ?>", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                transaction_id: transactionId,
                data: payload
            })
        })
        .then(res => res.json())
        .then(json => {
            if (json.status) {
                alert("Komisi berhasil disimpan!");
                // optional: reload summary
                openSummary(transactionId);
            } else {
                alert("Gagal menyimpan: " + json.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert("Error koneksi");
        });
    }

    // helper kecil untuk menghindari XSS dari data yang berasal dari backend
    function escapeHtml(unsafe) {
        if (unsafe === null || unsafe === undefined) return "";
        return String(unsafe)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // pastikan ada fungsi numberWithCommas yang aman
    function numberWithCommas(x) {
        x = Number(x) || 0;
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

    // Lihat Struk
    function openStruk(id) {
        window.location.href = "<?= base_url('transaction/preview_struk/') ?>" + id;
    }

    document.addEventListener("input", function(e){
        if(e.target.classList.contains("komisi-input")) {

            // ambil parent item-block
            let parent = e.target.closest(".item-block");

            // ambil harga layanan
            let priceText = parent.querySelector(".item-price").dataset.raw;
            let price = Number(priceText);

            // hitung total komisi
            let total = 0;
            parent.querySelectorAll(".komisi-input").forEach(inp => {
                let clean = inp.value.replace(/\./g, "").replace(/\D/g, "");
                total += Number(clean || 0);
            });

            // elemen info validasi
            let info = parent.querySelector(".komisi-validation");

            if(total === price) {
                info.innerHTML = `<span class="text-success fw-bold">✔ Total sesuai</span>`;
            } 
            else {
                info.innerHTML = `
                    <span class="text-danger fw-bold">
                        ✘ Total komisi Rp ${numberWithCommas(total)} ≠ Rp ${numberWithCommas(price)}
                    </span>
                `;
            }

            checkAllKomisiValid();
        }
    });

    function checkAllKomisiValid() {
        const blocks = document.querySelectorAll(".item-block");
        let allValid = true;

        blocks.forEach(block => {
            let price = Number(block.querySelector(".item-price").dataset.raw);

            let total = 0;
            let inputs = block.querySelectorAll(".komisi-input");
            inputs.forEach(inp => {
                let clean = inp.value.replace(/\./g, "").replace(/\D/g, "");
                total += Number(clean || 0);
            });

            if (total !== price) {
                allValid = false;
            }
        });

        document.getElementById("btnSaveKomisi").disabled = !allValid;
    }



</script>


</html>