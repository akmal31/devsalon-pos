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
        <div class="section mt-1">
            <div class="card">
                <div class="card-body">
                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <input type="text" class="form-control" id="searchService" placeholder="Cari Perawatan">
                            <i class="clear-input">
                                <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle"><template shadowrootmode="open"><div class="icon-inner"><svg xmlns="http://www.w3.org/2000/svg" class="ionicon s-ion-icon" viewBox="0 0 512 512"><title>Close Circle</title><path d="M256 48C141.31 48 48 141.31 48 256s93.31 208 208 208 208-93.31 208-208S370.69 48 256 48zm75.31 260.69a16 16 0 11-22.62 22.62L256 278.63l-52.69 52.68a16 16 0 01-22.62-22.62L233.37 256l-52.68-52.69a16 16 0 0122.62-22.62L256 233.37l52.69-52.68a16 16 0 0122.62 22.62L278.63 256z"></path></svg></div></template></ion-icon>
                            </i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section mt-1">
            <div class="section-title">List Services</div>
        </div>

        <ul id="serviceList" class="listview image-listview inset"></ul>

        <!-- Modal dinamis -->
        <div class="modal fade dialogbox" id="serviceModal" data-bs-backdrop="static" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content" id="modalContent">
                    <!-- isi modal nanti diisi JS -->
                </div>
            </div>
        </div>

        <!-- Cookies Box -->
        <div id="cookiesbox" class="offcanvas offcanvas-bottom cookies-box show" tabindex="-1" data-bs-scroll="true"
            data-bs-backdrop="false" style="visibility: visible;">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">Ringkasan Pembayaran</h5>
            </div>
            <div class="offcanvas-body">
                <div id="summaryList">Belum Ada Perawatan yang dipilih</div>
                <div id="summaryTotal"></div>
                <div class="buttons">
                    <a href="#" id="btnCheckout" class="btn btn-primary btn-block">Lanjut Pembayaran</a>
                </div>
            </div>
        </div>
        <!-- * Cookies Box -->


    </div>
    <!-- * App Capsule -->

    <!-- App Bottom Menu -->
    <?php $this->load->view("partial/v_menu_bottom"); ?>
    <!-- * App Bottom Menu -->

    <?php $this->load->view("partial/v_script_bottom"); ?>

</body>

<script>
    let allServices = [];
    let allKaryawan = <?= json_encode($all_karyawan) ?>;
    let cart = [];


    // 1. Ambil data services + packages dari API
    async function loadServices() {
        let res = await fetch("<?=base_url('homepage/api_services_and_packages')?>");
        let json = await res.json();
        let services = json.data.services;
        let packages = json.data.packages;

        // ubah packages biar compatible dengan list
        packages = packages.map(p => ({
            id: + p.package_id,
            name: p.name + " (Package)",
            price: p.price,
            type: 'package',
            raw: p
        }));

        // services tambahkan type
        services = services.map(s => ({
            ...s,
            type: 'service'
        }));

        // gabungkan
        allServices = [...services, ...packages];

        renderList(allServices);
    }

    loadServices();

    // 2. Render list service
    function renderList(data) {
        let html = "";
        data.forEach(item => {
            // ambil price dengan fallback
            const rawPrice = item.price ?? item.total_price ?? 0;
            html += `
            <li>
                <a href="#" class="item" onclick="openServiceModal('${item.type}', ${item.id})">
                    <div class="in">
                        <div>${item.name}</div>
                        <span class="text-muted">Rp. ${numberWithCommas(rawPrice)}</span>
                    </div>
                </a>
            </li>`;
        });

        document.getElementById("serviceList").innerHTML = html;
    }

    function openPackageModal(id) {
        const pkg = mergedList.find(i => i.type === "package" && i.id == id);
        if (!pkg) return;

        document.getElementById("modalTitle").innerHTML = pkg.name;
        document.getElementById("modalPrice").innerHTML = "Rp " + numberWithCommas(pkg.price);

        let detail = "";
        pkg.services.forEach(s => {
            detail += `<li>${s.name} — Rp ${numberWithCommas(s.price)}</li>`;
        });

        document.getElementById("modalServices").innerHTML = detail;

        new bootstrap.Modal(document.getElementById("DialogService")).show();
    }



    // 3. Formatter angka
    function numberWithCommas(x) {
        if (x === null || x === undefined) return "0";

        // jika objek (bukan primitive), coba ambil property price / total_price
        if (typeof x === "object") {
            x = x.price ?? x.total_price ?? "";
        }

        // konversi ke string
        let s = (typeof x === "number") ? String(x) : (x || "").toString();

        // hapus semua karakter bukan angka (jika mau dukung desimal, ubah regex)
        s = s.replace(/\D/g, "");
        if (s === "") return "0";

        // sisip titik ribuan
        return s.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // 4. Fitur search
    document.getElementById("searchService").addEventListener("keyup", function () {
        let q = this.value.toLowerCase();
        let filtered = allServices.filter(s => s.name.toLowerCase().includes(q));
        renderList(filtered);
    });

    // 5. Buka modal dengan data dinamis
    function openServiceModal(type,id) {
        let s = allServices.find(x => x.id == id && x.type == type);
        

        // generate dropdown karyawan
        let karyawanOptions = allKaryawan.map(k => 
            `<option value="${k.id}">${k.name}</option>`
        ).join("");

        let html = `
            <div class="modal-header">
                <h5 class="modal-title">${s.name}</h5>
            </div>
            <form>
                <div class="modal-body text-start mb-2">

                    <div class="form-group basic">
                        <label class="label">Yang Mengerjakan</label>
                        <select name="karyawan" class="form-control" multiple>
                            ${allKaryawan.map(k => `<option value="${k.id}">${k.name}</option>`).join("")}
                        </select>
                    </div>

                    <div class="form-group basic">
                        <label class="label">Quantity</label>
                        <input name="qty" type="text" class="form-control" value="1">
                    </div>

                    <div class="form-group basic">
                        <label class="label">Harga</label>
                        <input name="price" type="text" class="form-control" value="${numberWithCommas(s.price)}">
                    </div>

                    <div class="form-group basic">
                        <label class="label">Diskon (%)</label>
                        <input name="discount" type="text" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-text-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-text-primary" onclick="addToCart(${s.id})" data-bs-dismiss="modal">Lanjutkan</button>
                </div>
            </form>
        `;

        document.getElementById("modalContent").innerHTML = html;

        // Show modal
        var modal = new bootstrap.Modal(document.getElementById('serviceModal'));
        modal.show();

        // add event listeners untuk update total
        setupTotalCalculator();
    }


    function setupTotalCalculator() {
        const qty = document.getElementById("modalQty");
        const harga = document.getElementById("modalHarga");
        const diskon = document.getElementById("modalDiskon");
        const total = document.getElementById("modalTotal");

        function hitung() {
            let hrg = parseInt(harga.value.replace(/\./g, "")) || 0;
            let q = parseInt(qty.value) || 1;
            let d = parseInt(diskon.value) || 0;

            let subtotal = hrg * q;
            let potongan = subtotal * (d / 100);
            let hasil = subtotal - potongan;

            total.value = numberWithCommas(hasil);
        }

        qty.addEventListener("input", hitung);
        harga.addEventListener("input", hitung);
        diskon.addEventListener("input", hitung);

        hitung(); // calculate first time
    }

    function addToCart(id) {
        let s = allServices.find(x => x.id == id);

        // Ambil values
        let qty = document.querySelector("#modalContent input[name='qty']").value.trim();
        let price = document.querySelector("#modalContent input[name='price']").value.trim();
        let discount = document.querySelector("#modalContent input[name='discount']").value.trim();
        
        // Ambil semua karyawan yang dipilih
        let karyawan = [];
        document.querySelectorAll("#modalContent select[name='karyawan'] option:checked")
            .forEach(opt => karyawan.push(opt.value));

        // =============================
        //        VALIDASI INPUT
        // =============================

        // Karyawan wajib
        if (karyawan.length === 0) {
            alert("Pilih minimal 1 karyawan.");
            return;
        }

        // Qty wajib & harus angka > 0
        if (qty === "" || isNaN(qty) || parseInt(qty) <= 0) {
            alert("Quantity harus diisi dan harus lebih dari 0.");
            return;
        }

        // Harga wajib & harus angka > 0
        let priceRaw = price.replace(/\./g, ""); // remove dots
        if (priceRaw === "" || isNaN(priceRaw) || parseInt(priceRaw) <= 0) {
            alert("Harga harus diisi dan harus lebih dari 0.");
            return;
        }

        // Diskon boleh kosong → dianggap 0
        if (discount === "" || isNaN(discount)) {
            discount = 0;
        }

        // =============================
        //        HITUNG TOTAL
        // =============================

        qty = parseInt(qty);
        priceRaw = parseInt(priceRaw);
        discount = parseInt(discount);

        let total = priceRaw * qty * (1 - (discount / 100));

        // =============================
        //      SIMPAN KE CART
        // =============================

        cart.push({
            id,
            name: s.name,
            qty,
            type: s.type,
            price: priceRaw,
            discount,
            total,
            karyawan // array
        });

        // Render ulang ringkasan
        renderCart();
    }

    function renderCart() {
        let box = document.getElementById("summaryList");
        let totalBox = document.getElementById("summaryTotal");

        if (cart.length === 0) {
            box.innerHTML = `Belum Ada Perawatan yang dipilih`;
            totalBox.innerHTML = "";
            return;
        }

        let html = `<ul class="listview image-listview">`;
        let grandTotal = 0;

        cart.forEach((item, index) => {
            grandTotal += item.total;

            html += `
            <li>
                <a href="#" class="item">
                    <div class="in">
                        <div>
                            <header>${item.name}</header>
                            Pegang: ${item.karyawan.map(id => getKaryawanName(id)).join(", ")}
                            <footer>${item.qty} × Rp ${numberWithCommas(item.price)} — Diskon ${item.discount}%</footer>
                            <b>Total: Rp ${numberWithCommas(item.total)}</b>
                        </div>

                        <span class="remove-item" onclick="removeItem(${index})"
                            style="font-size: 24px; font-weight: bold; color: red; margin-left: 10px;">
                            ×
                        </span>
                    </div>
                </a>
            </li>
            `;
        });

        html += `</ul>`;
        box.innerHTML = html;

        totalBox.innerHTML = `
            <div style="padding: 10px; font-size: 18px; font-weight: bold;">
                Total Pembayaran: Rp ${numberWithCommas(grandTotal)}
            </div>
        `;
    }

    function getKaryawanName(id) {
        let k = allKaryawan.find(x => x.id == id);
        return k ? k.name : "-";
    }

    function removeItem(index) {
        cart.splice(index, 1);
        renderCart();
    }

    document.getElementById("btnCheckout").addEventListener("click", function () {
        if (cart.length === 0) {
            alert("Belum ada service yang dipilih.");
            return;
        }

        // Buat form dinamis
        let form = document.createElement("form");
        form.method = "POST";
        form.action = "<?= base_url('homepage/ringkasan_transaksi') ?>";

        // Kirim cart sebagai JSON
        let input = document.createElement("input");
        input.type = "hidden";
        input.name = "cart_data";
        input.value = JSON.stringify(cart); // cart dikirim sebagai JSON string
        form.appendChild(input);

        document.body.appendChild(form);
        form.submit();
    });

</script>

</html>