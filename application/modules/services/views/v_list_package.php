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
        <div class="section mt-4">
            <div class="section-heading d-flex justify-content-between align-items-center">
                <h2 class="title">Semua Paket Perawatan</h2>
                <a href="#" onclick="openAddPackageModal()">
                    <ion-icon name="add-circle-outline"></ion-icon> Tambah
                </a>

            </div>

            <input type="text" id="searchInput" class="form-control" placeholder="Cari paket..."> <br>

            <div class="transactions" id="packageContainer">
                <p class="text-center">
                    <ion-icon name="telescope-outline" style="font-size: 60px;"></ion-icon><br>- Belum ada Paket Perawatan -
                </p>
            </div>
        </div>

    </div>
    <!-- * App Capsule -->

    <!-- App Bottom Menu -->
    <?php $this->load->view("partial/v_menu_bottom"); ?>
    <!-- * App Bottom Menu -->

    <?php $this->load->view("partial/v_script_bottom"); ?>
    <!-- Modal Hapus -->
    <div class="modal fade" id="DialogDelete" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Paket Perawatan</h5>
                </div>
                <div class="modal-body">
                    Anda yakin akan menghapus Paket Perawatan ini?
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a href="#" class="btn btn-text-danger" data-bs-dismiss="modal">
                            <ion-icon name="close-outline"></ion-icon>
                            Tidak
                        </a>

                        <a href="#" class="btn btn-text-primary" id="btnDeleteConfirm">
                            <ion-icon name="checkmark-outline"></ion-icon>
                            Yakin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Modal Edit Paket Perawatan -->
<div class="modal fade" id="DialogEdit" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="formEditPackage" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Paket Perawatan</h5>
            </div>

            <div class="modal-body">

                <input type="hidden" id="edit_id">
                <div class="form-group mb-2">
                    <label>Nama Paket Perawatan</label>
                    <input type="text" class="form-control" id="edit_name" required>
                </div>
                <div id="edit_selectedServicesContainer"></div>
                <div class="form-group mb-2">
                    <label>Harga</label>
                    <input type="text" class="form-control" id="edit_price" required>
                </div>
                <!-- Outlet hanya muncul untuk owner -->
                <div class="form-group mb-2" id="outletGroup" style="display:none">
                    <label>Outlet</label>
                    <select class="form-control" id="edit_outlet_id"></select>
                </div>

            </div>

            <div class="modal-footer">
                <div class="btn-inline">
                    <a class="btn btn-text-danger" data-bs-dismiss="modal">
                        <ion-icon name="close-outline"></ion-icon>
                        Batal
                    </a>

                    <button type="submit" class="btn btn-text-primary">
                        <ion-icon name="checkmark-outline"></ion-icon>
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Paket Perawatan -->
<div class="modal fade" id="DialogAddPackage" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="formAddPackage" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Paket Perawatan</h5>
            </div>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label>Nama Paket</label>
                    <input type="text" class="form-control" id="add_name" required>
                </div>
                <!-- GANTI Durasi → Pilih Perawatan -->
                <div class="form-group mb-2">
                    <label>Pilih Perawatan</label>
                    <select class="form-control" id="add_services" multiple required></select>
                    <small class="text-muted">Pilih 1 atau lebih perawatan</small>
                </div>
                <!-- List Perawatan Terpilih -->
                <div id="selectedServicesContainer"></div>
                <div class="form-group mb-2 mt-3">
                    <label>Total Harga Paket</label>
                    <input type="text" class="form-control" id="add_price" readonly>
                </div>
                <!-- Outlet → hanya tampil untuk owner -->
                <div class="form-group mb-2" id="addOutletGroup" style="display:none">
                    <label>Outlet</label>
                    <select class="form-control" id="add_outlet_id"></select>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn btn-text-danger" data-bs-dismiss="modal">
                        <ion-icon name="close-outline"></ion-icon> Batal
                    </a>
                    <button type="submit" class="btn btn-text-primary">
                        <ion-icon name="checkmark-outline"></ion-icon> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="modal fade dialogbox" id="DialogIconedSuccess" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <!-- ICON -->
            <div id="successIconWrapper" class="modal-icon">
                <ion-icon id="successIcon" name="checkmark-circle"></ion-icon>
            </div>

            <div class="modal-header">
                <h5 class="modal-title" id="successTitle">Success</h5>
            </div>

            <div class="modal-body" id="successMessage">
                Your request was successful.
            </div>

            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn" data-bs-dismiss="modal">CLOSE</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

<script>

    document.addEventListener("DOMContentLoaded", function() {
        loadPackages();
    });
   
    // Load semua layanan ke dropdown
    function loadAllServices() {
        fetch('<?= base_url("services/searchServices") ?>')
            .then(res => res.json())
            .then(data => {
                let opt = "";
                data.forEach(s => {
                    opt += `<option value="${s.id}" data-price="${s.price}">${s.name}</option>`;
                });

                document.getElementById("add_services").innerHTML = opt;
            });
    }

    function openAddPackageModal() {
        loadAllServices();
        new bootstrap.Modal(document.getElementById("DialogAddPackage")).show();
    }

    const serviceSelect = document.getElementById("add_services");
    const container = document.getElementById("selectedServicesContainer");
    const inputTotal = document.getElementById("add_price");

    // format ribuan
    function formatRibuan(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function bersih(x){
        return x.replace(/\./g, "");
    }

    serviceSelect.addEventListener("change", function() {
        container.innerHTML = "";
        inputTotal.value = "";

        const selected = [...this.selectedOptions];

        selected.forEach(opt => {
            const id = opt.value;
            const name = opt.textContent;
            const price = opt.dataset.price;

            container.innerHTML += `
                <div class="card p-2 mb-2">
                    <strong>${name}</strong>
                    Harga asli : Rp ${formatRibuan(price)}
                    <input type="text" class="form-control mt-1 service-price" 
                        data-id="${id}"
                        value="${formatRibuan(price)}">
                </div>
            `;
        });

        // Pasang event listener untuk setiap input harga
        updateServicePriceEvents();
        hitungTotal();
    });

    function updateServicePriceEvents() {
        document.querySelectorAll(".service-price").forEach(inp => {
            inp.addEventListener("input", function() {
                let val = this.value.replace(/\D/g, "");
                this.value = formatRibuan(val);
                hitungTotal();
            });
        });
    }

    // Hitung total
    function hitungTotal() {
        let total = 0;
        document.querySelectorAll(".service-price").forEach(inp => {
            total += parseInt(bersih(inp.value || "0"));
        });
        inputTotal.value = formatRibuan(total);
    }

    document.getElementById("formAddPackage").addEventListener("submit", function(e){
        e.preventDefault();

        const name = document.getElementById("add_name").value;

        const prices = {};
        document.querySelectorAll(".service-price").forEach(inp => {
            prices[inp.dataset.id] = bersih(inp.value);
        });

        const payload = new URLSearchParams({
            name: name,
            services: JSON.stringify(prices),
            price: bersih(document.getElementById("add_price").value)
        });

        fetch("<?= base_url('services/addPackage') ?>", {
            method: "POST",
            body: payload
        })
        .then(res => res.json())
        .then(data => {
            if (data.status) {
                showSuccessModal("Paket berhasil ditambahkan!");
                loadPackages();
            } else {
                showSuccessModal("Gagal menambah paket!", "Error", "error");
            }
        });
    });

    function loadPackages() {
        fetch("<?= base_url('services/searchPackage') ?>")
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById("packageContainer");

                if (!data.length) {
                    container.innerHTML = `
                        <p class="text-center">
                            <ion-icon name="telescope-outline" style="font-size: 60px;"></ion-icon><br>
                            - Belum ada Paket Perawatan -
                        </p>`;
                    return;
                }

                let html = "";
                data.forEach(p => {

                    // --- generate list perawatan di dalam paket --- //
let serviceList = "";
if (p.services && p.services.length > 0) {
    serviceList = `
        <ul class="mt-1" style="padding-left:16px; margin:0; font-size: 14px;">
            ${p.services.map(s => `
                <li>${s.name} — Rp ${formatRibuan(s.price)}</li>
            `).join("")}
        </ul>
    `;
} else {
    serviceList = `<small class="text-muted" style="font-size: 13px;">(Tidak ada perawatan)</small>`;
}

html += `
    <div class="item d-flex justify-content-between align-items-center py-2" style="padding-right: 10px;">

        <div class="flex-grow-1">
            <strong style="color: black;">${p.name}</strong>
            <h4 style="color:black;" class="mb-1">
                Harga: Rp ${formatRibuan(p.price)}
            </h4>
            ${serviceList}
        </div>

        <div class="d-flex align-items-center">

            <a href="#" onclick="openEditPackage(${p.package_id})"
               style="font-size: 26px; line-height: 1;">
                <ion-icon name="create-outline"></ion-icon>
            </a>&nbsp

            <a href="#" onclick="openDeletePackage(${p.package_id})"
               style="font-size: 26px; line-height: 1;">
                <ion-icon name="trash-outline" class="text-danger"></ion-icon>
            </a>

        </div>

    </div>
`;

                });

                container.innerHTML = html;
            });
    }

    let deletePackageId = null;

    function openDeletePackage(id) {
        deletePackageId = id;
        new bootstrap.Modal(document.getElementById("DialogDelete")).show();
    }

    document.getElementById('btnDeleteConfirm').addEventListener('click', function (e) {
        e.preventDefault();
        if (!deletePackageId) return;
        fetch("<?= base_url('services/deletePackage') ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "id=" + deletePackageId
        })
        .then(res => res.json())
        .then(data => {
            const delModal = bootstrap.Modal.getInstance(document.getElementById('DialogDelete'));
            delModal.hide();

            if (data.status) {
                showSuccessModal("Paket berhasil dihapus!");
                loadPackages();
            } else {
                showSuccessModal("Gagal menghapus paket!", "Error", "error");
            }
        });
    });

    function openEditPackage(id) {
        fetch("<?= base_url('services/getPackage/') ?>" + id)
            .then(res => res.json())
            .then(p => {
                document.getElementById("edit_id").value = p.package_id;
                document.getElementById("edit_name").value = p.name;

                // Render perawatan
                renderEditServices(p.services);

                // Hitung total
                hitungEditTotal();

                new bootstrap.Modal(document.getElementById("DialogEdit")).show();
            });
    }

    function hitungEditTotal() {
        let total = 0;
        document.querySelectorAll(".edit-service-price").forEach(inp => {
            total += parseInt(bersih(inp.value || "0"));
        });

        document.getElementById("edit_price").value = formatRibuan(total);
    }

    function updateEditServiceEvents() {
        document.querySelectorAll(".edit-service-price").forEach(inp => {
            inp.addEventListener("input", function () {
                let val = this.value.replace(/\D/g, "");
                this.value = formatRibuan(val);
                hitungEditTotal();
            });
        });
    }

    function renderEditServices(services) {
        const container = document.getElementById("edit_selectedServicesContainer");
        container.innerHTML = "";

        services.forEach(s => {
            container.innerHTML += `
                <div class="card p-2 mb-2">
                    <strong>${s.name}</strong>
                    Harga asli : Rp ${formatRibuan(s.original_price)}
                    <input type="text" class="form-control mt-1 edit-service-price"
                        data-id="${s.id}"
                        value="${formatRibuan(s.price)}">
                </div>
            `;
        });

        updateEditServiceEvents();
    }

    document.getElementById("formEditPackage").addEventListener("submit", function (e) {
        e.preventDefault();

        const id = document.getElementById("edit_id").value;

        const prices = {};
        document.querySelectorAll(".edit-service-price").forEach(inp => {
            prices[inp.dataset.id] = bersih(inp.value);
        });

        const payload = new URLSearchParams({
            name: document.getElementById("edit_name").value,
            price: bersih(document.getElementById("edit_price").value),
            services: JSON.stringify(prices)
        });

        fetch("<?= base_url('services/updatePackage/') ?>" + id, {
            method: "POST",
            body: payload
        })
            .then(res => res.json())
            .then(data => {
                const mdl = bootstrap.Modal.getInstance(document.getElementById("DialogEdit"));
                mdl.hide();

                if (data.status) {
                    showSuccessModal("Paket berhasil diperbarui!");
                    loadPackages();
                } else {
                    showSuccessModal("Gagal memperbarui paket!", "Error", "error");
                }
            });
    });

    function safeHideModal(modalId) {
        const el = document.getElementById(modalId);
        if (!el) return;
        const inst = bootstrap.Modal.getInstance(el);
        if (inst) inst.hide();
        else {
            if (el.classList.contains('show')) {
                el.classList.remove('show');
                el.style.display = 'none';
                document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
                document.body.classList.remove('modal-open');
            }
        }
    }

    function showSuccessModal(message = "Berhasil!", title = "Success", type = "success", autoCloseMs = 0) {
        // tutup modal sebelumnya bila ada
        safeHideModal("DialogEdit");
        safeHideModal("DialogDelete");
        safeHideModal("DialogAdd");      // optional
        safeHideModal("DialogAddPackage"); // optional

        // set teks & icon
        const titleEl = document.getElementById("successTitle");
        const msgEl   = document.getElementById("successMessage");
        const iconEl  = document.querySelector("#DialogIconedSuccess #successIcon");

        if (titleEl) titleEl.textContent = title;
        if (msgEl) msgEl.textContent = message;

        if (iconEl) {
            if (type === "success") {
                iconEl.setAttribute("name","checkmark-circle");
                iconEl.parentElement.classList.remove("text-danger");
                iconEl.parentElement.classList.add("text-success");
            } else {
                iconEl.setAttribute("name","close-circle");
                iconEl.parentElement.classList.remove("text-success");
                iconEl.parentElement.classList.add("text-danger");
            }
        }

        const modalEl = document.getElementById("DialogIconedSuccess");
        if (!modalEl) {
            alert(message); // fallback
            return;
        }

        // ensure fade class for nice backdrop animation
        modalEl.classList.add("fade");

        const modal = new bootstrap.Modal(modalEl, { backdrop: true });
        modal.show();

        // optional auto close
        if (autoCloseMs && Number(autoCloseMs) > 0) {
            setTimeout(() => modal.hide(), Number(autoCloseMs));
        }
    }

    // Optional: reload list when success modal fully hidden
    document.getElementById("DialogIconedSuccess")?.addEventListener("hidden.bs.modal", function() {
        // adjust depending on current page: packages/services/etc
        // e.g. if on package page:
        if (typeof loadPackages === "function") loadPackages();
        if (typeof loadServices === "function") loadServices("");
    });    

</script>

</html>