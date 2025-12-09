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

    <ul class="listview image-listview inset">
        <?php foreach($cart as $item): ?>
            <li>
                <a href="#" class="item">
                    <div class="in">
                        <div>
                            <b><?= $item['name'] ?></b>
                            <?= $item['type'] === 'product' ? '' : '<header>Pegang: ' . implode(", ", array_map(function($kid) use ($karyawanMap) {
                                return $karyawanMap[$kid] ?? '-';
                            }, $item['karyawan'])) . '</header>' ?>
                            <footer>
                                <?= $item['qty'] ?> × Rp <?= number_format($item['price'], 0, ',', '.') ?>
                                <?= $item['discount'] > 0 ? " — Diskon {$item['discount']}%" : "" ?>
                            </footer>
                            <b>Total: Rp <?= number_format($item['total'],0,',','.') ?></b>
                        </div>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <hr>
    <div class="section" style="text-align:center;color:black;">
        <div class="card p-2">
            <b>Total Pembayaran Keseluruhan:</b>
            <span style="font-size: 20px; font-weight: bold;">
                Rp <?= number_format($grandTotal, 0, ',', '.') ?>
            </span>
        </div>
    </div>
    <hr>
    <div class="section mb-2">
    <div class="section-title">Customer</div>
        <div class="card">
            <div class="card-body">
                <div class="form-group boxed" id="phone">
                    <div class="input-wrapper">
                        <label class="label">Nomor HP</label>
                        <input type="text" class="form-control" id="inputPhone">
                    </div>
                </div>

                <a href="#" id="btnCheckMember" class="btn btn-primary btn-block">Cek Member</a>

                <!-- Table hasil cek -->
                <table id="memberTable" style="width:100%; font-size:15px; margin-top:10px; line-height:1.6; display:none;">
                    <tr>
                        <td style="text-align:left;">Nama</td>
                        <td style="text-align:right; font-weight:bold;" id="memberName"></td>
                    </tr>
                    <tr>
                        <td style="text-align:left;">Nomor HP</td>
                        <td style="text-align:right; font-weight:bold;" id="memberPhone"></td>
                    </tr>
                </table>

                <!-- Form input customer baru -->
                <div id="newCustomerForm" style="display:none; margin-top:10px;">
                    <div class="form-group">
                        <label>Nama <span style="color:red;">*</span></label>
                        <input type="text" class="form-control" id="newName">
                    </div>
                    <div class="form-group">
                        <label>Nomor HP <span style="color:red;">*</span></label>
                        <input type="text" class="form-control" id="newPhone">
                    </div>
                    <div class="form-group">
                        <label>Birthday</label>
                        <input type="date" class="form-control" id="newBirthday">
                    </div>
                    <button id="saveCustomer" class="btn btn-success btn-block">Simpan Customer</button>
                </div>
            </div> <!-- card-body -->
        </div> <!-- card -->
    </div> <!-- section -->
    <hr>
    <div class="section mb-2">
        <div class="section-title">Pembayaran</div>
            <div class="card">
                <div class="card-body">
                    <form>
                        <div class="form-group boxed">
                            <div class="input-wrapper">
                                <label class="label" for="select4b">Pembayaran menggunakan ?</label>
                                <select class="form-control custom-select" id="select4b">
                                    <option value="">Pilih Metode Pembayaran</option>
                                    <option value="cash">Cash</option>
                                    <option value="qris">QRIS</option>
                                    <option value="card">Debit/Credit Card</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group boxed" id="groupJumlah" style="display:none;">
                            <div class="input-wrapper">
                                <label class="label">Jumlah uang yang dibayarkan</label>
                                <input type="text" class="form-control" id="inputBayar">
                            </div>
                        </div>
                        <div class="form-group boxed" id="groupKembali" style="display:none;">
                            <div class="input-wrapper">
                                <label class="label">Kembali</label>
                                <input type="text" class="form-control" id="inputKembali" disabled>
                                <input type="hidden" id="kembalianPost">
                            </div>
                        </div>
                        <div class="form-group boxed" id="groupTips" style="display:none;">
                            <div class="input-wrapper">
                                <label class="label">Tips (opsional)</label>
                                <input type="text" class="form-control" id="inputTips">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="section mb-2">
            <div class="buttons mt-0">
                    <input type="hidden" name="memberId" id="memberId">
                    <a href="#" id="btnCheckout" class="btn btn-primary btn-block">Konfirmasi Pembayaran</a>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view("partial/v_menu_bottom"); ?>
<?php $this->load->view("partial/v_script_bottom"); ?>

<script>
    // ==============================
    // VARIABEL GLOBAL
    // ==============================
    const grandTotal     = <?= $grandTotal ?>;
    const cart           = <?= json_encode($cart) ?>;

    // Elements
    const selectPayment  = document.getElementById("select4b");
    const groupJumlah    = document.getElementById("groupJumlah");
    const groupKembali   = document.getElementById("groupKembali");
    const groupTips      = document.getElementById("groupTips");

    const inputBayar     = document.getElementById("inputBayar");
    const inputKembali   = document.getElementById("inputKembali");
    const kembalianPost   = document.getElementById("kembalianPost");
    const inputTips      = document.getElementById("inputTips");

    const btnCheckout    = document.getElementById("btnCheckout");
    const btnCheckMember    = document.getElementById("btnCheckMember");

    const saveCustomerBtn  = document.getElementById("saveCustomer");
    const newNameInput     = document.getElementById("newName");
    const newPhoneInput    = document.getElementById("newPhone");
    const newBirthdayInput = document.getElementById("newBirthday");

    const memberTable      = document.getElementById("memberTable");
    const memberNameTd     = document.getElementById("memberName");
    const memberPhoneTd    = document.getElementById("memberPhone");
    const memberIdInput    = document.getElementById("memberId");
    const newCustomerForm  = document.getElementById("newCustomerForm");


    // ==============================
    // HELPER FORMAT NUMBER
    // ==============================
    const formatNumber = (x) =>
        x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    const cleanNumber = (x) =>
        parseInt((x || "").toString().replace(/\./g, "")) || 0;


    // ==============================
    // ENABLE / DISABLE BUTTON
    // ==============================
    function setCheckoutEnabled(status) {
        if (status) {
            btnCheckout.removeAttribute("disabled");
            btnCheckout.style.opacity = "1";
            btnCheckout.style.pointerEvents = "auto";
        } else {
            btnCheckout.setAttribute("disabled", true);
            btnCheckout.style.opacity = "0.5";
            btnCheckout.style.pointerEvents = "none";
        }
    }

    // Default: disabled
    setCheckoutEnabled(false);


    // ==============================
    // EVENT: METODE PEMBAYARAN
    // ==============================
    selectPayment.addEventListener("change", function () {
        const metode = this.value;

        // Tidak memilih
        if (!metode || metode === "") {
            groupJumlah.style.display  = "none";
            groupKembali.style.display = "none";
            groupTips.style.display    = "none";
            setCheckoutEnabled(false);
            return;
        }

        // CASH
        if (metode === "cash") {
            groupJumlah.style.display  = "block";
            groupKembali.style.display = "block";
            groupTips.style.display    = "none";

            inputBayar.value   = "";
            inputKembali.value = "";
            kembalianPost.value = "";
            setCheckoutEnabled(false);
        }

        // NON CASH → QRIS / CARD
        else {
            groupJumlah.style.display  = "none";
            groupKembali.style.display = "none";
            groupTips.style.display    = "block";

            inputTips.value = "";
            setCheckoutEnabled(true);
        }
    });


    // ==============================
    // EVENT: HITUNG CASH & KEMBALI
    // ==============================
    inputBayar.addEventListener("keyup", function () {
        let bayar = cleanNumber(inputBayar.value);
        inputBayar.value = formatNumber(bayar);

        if (bayar < grandTotal) {
            inputKembali.value = "Kurang";
            kembalianPost.value = "Kurang";
            setCheckoutEnabled(false);
            return;
        }

        inputKembali.value = "Rp " + formatNumber(bayar - grandTotal);
        kembalianPost.value = formatNumber(bayar - grandTotal);
        setCheckoutEnabled(true);
    });


    // ==============================
    // EVENT: FORMAT TIPS
    // ==============================
    inputTips.addEventListener("keyup", function () {
        const nilai = cleanNumber(inputTips.value);
        inputTips.value = nilai > 0 ? formatNumber(nilai) : "";
    });


    // ==============================
    // EVENT: KONFIRMASI PEMBAYARAN
    // ==============================
    btnCheckout.addEventListener("click", function(e){
        e.preventDefault();

        const metode    = selectPayment.value;
        const tips      = cleanNumber(inputTips.value);
        const bayar     = cleanNumber(inputBayar.value);
        const kembalian = cleanNumber(kembalianPost.value);
        const memberId  = memberIdInput.value || null;

        if(cart.length === 0){
            alert('Cart masih kosong!');
            return;
        }

        if(!metode){
            alert('Pilih metode pembayaran!');
            return;
        }

        if(metode === 'cash' && bayar < grandTotal){
            alert('Uang bayar kurang!');
            return;
        }

        fetch('<?= base_url("homepage/simpanTransaksi") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                cart_data: JSON.stringify(cart),
                memberId: memberId,
                metode_bayar: metode,
                tips: tips,
                uang_bayar: bayar,
                kembalian: kembalian
            })
        })
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success'){
                alert('Transaksi berhasil! ID: '+res.transaction_id);
                window.location.href = '<?= base_url("transaction/preview_struk/") ?>'+res.transaction_id;
            } else {
                alert('Gagal menyimpan transaksi: '+res.message);
            }
        })
        .catch(err => console.error(err));
    });


// ==============================
// CEK MEMBER
// ==============================
btnCheckMember.addEventListener("click", function(e){
    e.preventDefault();

    const phone = inputPhone.value.trim();
    if(phone === '') {
        alert('Nomor HP harus diisi!');
        return;
    }

    fetch('<?= base_url("homepage/cekCustomer") ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ phone: phone })
    })
    .then(res => res.json())
    .then(res => {
        if(res) {
            memberIdInput.value = res.id;
            memberNameTd.textContent = res.name;
            memberPhoneTd.textContent = res.phone;
            memberTable.style.display = "table";
            newCustomerForm.style.display = "none";
        } else {
            memberTable.style.display = "none";
            newPhoneInput.value = phone;
            newCustomerForm.style.display = "block";
        }
    })
    .catch(err => console.error(err));
});


// ==============================
// SIMPAN CUSTOMER BARU
// ==============================
// Simpan customer baru
saveCustomerBtn.addEventListener("click", function(e){
    e.preventDefault();

    const name     = newNameInput.value.trim();
    const phone    = newPhoneInput.value.trim();
    const birthday = newBirthdayInput.value; // optional

    if(name === '' || phone === ''){
        alert('Nama dan Nomor HP harus diisi!');
        return;
    }

    // Kirim ke backend
    fetch('<?= base_url("homepage/simpanCustomer") ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ name: name, phone: phone, birthday: birthday })
    })
    .then(res => res.json())
    .then(res => {
        if(res.status === 'success'){
            // Isi table
            memberNameTd.textContent = res.name;
            memberPhoneTd.textContent = res.phone;
            memberTable.style.display = "table";

            // Simpan ID customer di hidden input
            memberIdInput.value = res.id;

            // Sembunyikan form input baru
            newCustomerForm.style.display = "none";
        } else {
            alert('Gagal menyimpan customer!');
        }
    })
    .catch(err => console.error(err));
});

</script>

</body>
</html>
