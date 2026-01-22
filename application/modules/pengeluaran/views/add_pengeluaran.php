<!doctype html>
<html lang="en">
    <?php $this->load->view("partial/v_html_header"); ?>
<body>
    <?php $this->load->view("partial/v_loader"); ?>
   <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#sidebarPanel">
                <ion-icon name="menu-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">
            <img src="<?php echo base_url(); ?>assets/img/loading-icon.png" alt="logo" class="logo">
        </div>
    </div>
    <!-- * App Header -->

    <!-- App Capsule -->
    <div id="appCapsule" class="full-height">
        <div class="section mt-2 mb-2">
            <div class="section-title">Input Pengeluaran</div>
            <div class="card">
                <div class="card-body">
                    <form>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="text4b">Barang/Jasa yang dibeli</label>
                                <input type="text" class="form-control" id="text4b" placeholder="Masukkan Barang/Jasa yang dibeli">
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="date4b">Tanggal</label>
                                <input type="date" class="form-control" id="date4b" placeholder="Pilih Tanggal">
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="select4b">Metode Pembayaran</label>
                                <select class="form-control custom-select" id="select4b">
                                    <option value="cash">Cash</option>
                                    <option value="transfer">Transfer</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="select4c">Tipe</label>
                                <select class="form-control custom-select" id="select4c">
                                    <option value="ambil laci">Setoran Cash</option>
                                    <option value="pengeluaran">Pembelian</option>
                                    <option value="tip">Tukar Tip</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="price4b">Nominal</label>
                                <input type="text" class="form-control" id="price4b" inputmode="numeric" placeholder="Masukkan Nominal Harga">
                                <i class="clear-input">
                                    <ion-icon name="close-circle"></ion-icon>
                                </i>
                            </div>
                        </div>
                        <a href="#" id="btnCheckout" class="btn btn-primary btn-block">Simpan Pengeluaran</a>
                    </form>

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
    document.addEventListener("DOMContentLoaded", function () {

        // Set default date = hari ini
        const today = new Date().toISOString().split("T")[0];
        document.getElementById("date4b").value = today;

        // Auto format ribuan (input text)
        const priceInput = document.getElementById("price4b");
        priceInput.addEventListener("input", function () {
            let cursor = this.selectionStart;
            let raw = this.value.replace(/\D/g, "");
            if (!raw) {
                this.value = "";
                return;
            }
            this.value = raw.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            this.setSelectionRange(cursor, cursor);
        });


        // Submit data
        document.getElementById("btnCheckout").addEventListener("click", async function (e) {
            e.preventDefault();
            let barang  = document.getElementById("text4b").value.trim();
            let tanggal = document.getElementById("date4b").value;
            let metode  = document.getElementById("select4b").value;
            let tipe    = document.getElementById("select4c").value;
            let nominal = document.getElementById("price4b").value.replace(/\./g, "").trim();

            if (!barang || !tanggal || !metode) {
                alert("Semua field wajib diisi!");
                return;
            }

            if (isNaN(nominal) || Number(nominal) <= 0) {
                alert("Nominal tidak valid");
                return;
            }

            try {
                const response = await fetch('<?= base_url("pengeluaran/save") ?>', {
                    method: "POST",
                    headers: {"Content-Type": "application/x-www-form-urlencoded;charset=UTF-8"},
                    body: new URLSearchParams({barang,tanggal,metode,tipe,nominal})
                });

                const res = await response.json();

                if (res.status) {
                    alert("Pengeluaran berhasil disimpan!");
                    window.location.href = '<?= base_url("pengeluaran") ?>';
                } else {
                    alert("Gagal menyimpan: " + res.message);
                }
            } catch (err) {
                console.error(err);
                alert("Terjadi kesalahan server");
            }
        });
    });
</script>

</html>