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
                <h2 class="title">Semua Perawatan</h2>
                <a href="#" onclick="openAddModal()">
                    <ion-icon name="add-circle-outline"></ion-icon> Tambah
                </a>

            </div>

            <input type="text" id="searchInput" class="form-control" placeholder="Cari perawatan..."> <br>

            <div class="transactions" id="servicesContainer">
                <p class="text-center">
                    <ion-icon name="telescope-outline" style="font-size: 60px;"></ion-icon><br>- Belum ada Perawatan -
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
                    <h5 class="modal-title">Hapus Perawatan</h5>
                </div>
                <div class="modal-body">
                    Anda yakin akan menghapus Perawatan ini?
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
<!-- Modal Edit Perawatan -->
<div class="modal fade" id="DialogEdit" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="formEditService" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Perawatan</h5>
            </div>

            <div class="modal-body">

                <input type="hidden" id="edit_id">

                <div class="form-group mb-2">
                    <label>Nama Perawatan</label>
                    <input type="text" class="form-control" id="edit_name" required>
                </div>

                <div class="form-group mb-2">
                    <label>Durasi (menit)</label>
                    <input type="number" class="form-control" id="edit_duration" required>
                </div>

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

<!-- Modal Tambah Perawatan -->
<div class="modal fade" id="DialogAdd" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <form id="formAddService" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Perawatan</h5>
            </div>

            <div class="modal-body">

                <div class="form-group mb-2">
                    <label>Nama Perawatan</label>
                    <input type="text" class="form-control" id="add_name" required>
                </div>

                <div class="form-group mb-2">
                    <label>Durasi (menit)</label>
                    <input type="number" class="form-control" id="add_duration" required>
                </div>

                <div class="form-group mb-2">
                    <label>Harga</label>
                    <input type="text" class="form-control" id="add_price" required>
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
    const searchInput = document.getElementById('searchNama');
    const servicesContainer = document.getElementById('servicesContainer');

    function formatRibuan(angka) {
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function bersihkanAngka(angka) {
        return angka.replace(/\./g, "");
    }

    // Formatter angka
    function numberWithCommas(x) {
        x = x.toString().replace(/\D/g, "");
        return x.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Load services by keyword
    function loadServices(keyword = "") {
        fetch('<?= base_url("services/searchServices") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ keyword: keyword })
        })
        .then(res => res.json())
        .then(data => {

            console.log("DATA:", data); // DEBUG

            let html = '';

            if(Array.isArray(data) && data.length > 0){
                data.forEach(tr => {
                    html += `
                        <div class="item d-flex justify-content-between align-items-center">
                            <a class="flex-grow-1">
                                <div class="detail">
                                    <div>
                                        <strong>${tr.name}</strong>
                                        <p>Durasi: ${tr.duration} menit</p>
                                        <strong>Rp. ${numberWithCommas(tr.price)}</strong>
                                    </div>
                                </div>
                            </a>
                            <ion-icon name="create-outline" style="font-size: 24px; color:#0d6efd; margin-left:10px;" onclick="openEditModal(${tr.id})"></ion-icon>
                            <ion-icon name="trash-outline" style="font-size: 24px; color:#dc3545; margin-left:10px;" onclick="openDeleteModal(${tr.id})"></ion-icon>
                        </div>
                    `;
                });
            } else {
                html = '<p class="text-center"><ion-icon name="telescope-outline" style="font-size: 60px;"></ion-icon><br>- Belum ada Perawatan -</p>';
            }

            servicesContainer.innerHTML = html;
        })
        .catch(err => console.error(err));
    }


    // Realtime search
    document.getElementById("searchInput").addEventListener("keyup", function() {
        loadServices(this.value);
    });

    // Load awal
    document.addEventListener("DOMContentLoaded", function() {
        loadServices(""); // panggil tanpa filter → munculkan semua
    });


    
    // Simpan ID Services yang mau dihapus
    let deleteId = null;

    // Klik icon hapus → tampilkan modal
    function openDeleteModal(id) {
        deleteId = id;
        var myModal = new bootstrap.Modal(document.getElementById('DialogDelete'));
        myModal.show();
    }

    document.getElementById('btnDeleteConfirm').addEventListener('click', function(e){
        e.preventDefault();

        if (!deleteId) return;

        fetch("<?= base_url('services/delete') ?>/" + deleteId, {
            method: "POST"
        })
        .then(res => {
            if (!res.ok) throw new Error("Network error");
            return res.json();
        })
        .then(data => {

            if (data.status) {
                // Refresh list
                loadServices(); // refresh list
                showSuccessModal("Perawatan berhasil dihapus!");
                const editModal = bootstrap.Modal.getInstance(document.getElementById('DialogDelete'));
                editModal.hide();
            } 
            else {
                showSuccessModal("Gagal menghapus data.", "Error");
            }
        })
        .catch(err => {
            console.error(err);
            showSuccessModal("Terjadi kesalahan server", "Error");
        });
    });

function openEditModal(id) {
    fetch('<?= base_url("services/getServiceById") ?>', {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ id: id })
    })
    .then(res => res.json())
    .then(data => {

        // Isi input form
        document.getElementById("edit_id").value = data.id;
        document.getElementById("edit_name").value = data.name;
        document.getElementById("edit_duration").value = data.duration;

        // Format harga jadi 120.000
        document.getElementById("edit_price").value = data.price
            .toString()
            .replace(/\B(?=(\d{3})+(?!\d))/g, ".");

        // Cek user group
        const user_group = <?= $this->session->userdata('logged_in')['USER_GROUP_ID']; ?>;

        if (user_group == 1) {
            // Owner → tampilkan dropdown outlet
            document.getElementById("outletGroup").style.display = "block";

            // Isi outlet_id ke dropdown
            document.getElementById("edit_outlet_id").value = data.outlet_id;
        } else {
            // Non-owner → sembunyikan
            document.getElementById("outletGroup").style.display = "none";
        }

        // Pasang auto-format untuk harga (HARUS DI DALAM SINI)
        const priceInput = document.getElementById("edit_price");
        priceInput.addEventListener("input", function () {
            let value = this.value.replace(/\D/g, ""); // bersihkan bukan angka
            this.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        });

        // Show modal edit
        new bootstrap.Modal(document.getElementById("DialogEdit")).show();
    })
    .catch(err => {
        console.error(err);
        alert("Gagal memuat data layanan!");
    });
}


    // SUBMIT EDIT
    document.getElementById("formEditService").addEventListener("submit", function(e){
        e.preventDefault();

        const formData = new URLSearchParams({
            id: document.getElementById("edit_id").value,
            name: document.getElementById("edit_name").value,
            duration: document.getElementById("edit_duration").value,
        });

        // Tambah outlet jika owner
        const user_group = <?= $this->session->userdata('logged_in')['USER_GROUP_ID']; ?>;
        if (user_group == 1) {
            formData.append("outlet_id", document.getElementById("edit_outlet_id").value);
        }

        let price = bersihkanAngka(document.getElementById("edit_price").value);
        formData.append("price", price);

        fetch('services/updateService', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status) {
                showSuccessModal("Layanan berhasil diperbarui!");
                loadServices(); // refresh list
                const editModal = bootstrap.Modal.getInstance(document.getElementById('EditServiceModal'));
                editModal.hide();
            } else {
                alert(data.message);
            }
        });
    });

    function showSuccessModal(message = "Berhasil!", title = "Success", type = "success") {
        // Pastikan semua modal sebelumnya tertutup
        safeHideModal("DialogEdit");
        safeHideModal("DialogDelete");

        // Elemen-elemen modal
        const modalTitle = document.getElementById("successTitle");
        const modalMessage = document.getElementById("successMessage");
        const modalIcon = document.querySelector("#DialogIconedSuccess .modal-icon ion-icon");

        // Set konten
        modalTitle.textContent = title;
        modalMessage.textContent = message;

        // Ubah icon & warna
        if (type === "success") {
            modalIcon.setAttribute("name", "checkmark-circle");
            modalIcon.parentElement.classList.remove("text-danger");
            modalIcon.parentElement.classList.add("text-success");
        } else {
            modalIcon.setAttribute("name", "close-circle");
            modalIcon.parentElement.classList.remove("text-success");
            modalIcon.parentElement.classList.add("text-danger");
        }

        // Tampilkan modal
        const modalEl = document.getElementById("DialogIconedSuccess");
        const modal = new bootstrap.Modal(modalEl);
        modal.show();

        // Opsional: auto-hide setelah 1.5 detik
        /*
        setTimeout(() => {
            modal.hide();
        }, 1500);
        */
    }

    // Hide modal helper (safety)
    function safeHideModal(modalId) {
        const el = document.getElementById(modalId);
        if (!el) return;
        const inst = bootstrap.Modal.getInstance(el);
        if (inst) {
            inst.hide();
        } else {
            // jika modal belum diinstansiasi tapi punya class 'show', remove classes and backdrop
            if (el.classList.contains('show')) {
                el.classList.remove('show');
                el.style.display = 'none';
                // remove backdrop if exists
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(b => b.parentNode && b.parentNode.removeChild(b));
                document.body.classList.remove('modal-open');
            }
        }
    } 

    function openAddModal() {
        // kosongkan field
        document.getElementById("add_name").value = "";
        document.getElementById("add_duration").value = "";
        document.getElementById("add_price").value = "";

        const user_group = <?= $this->session->userdata('logged_in')['USER_GROUP_ID']; ?>;

        if (user_group == 1) {
            document.getElementById("addOutletGroup").style.display = "block";

            fetch('<?= base_url("services/getOutlets") ?>')
            .then(res => res.json())
            .then(list => {
                let opts = "";
                list.forEach(o => {
                    opts += `<option value="${o.id}">${o.outlet}</option>`;
                });
                document.getElementById("add_outlet_id").innerHTML = opts;
            });
        } else {
            document.getElementById("addOutletGroup").style.display = "none";
        }

        // Auto-format harga
        const priceInput = document.getElementById("add_price");
        priceInput.addEventListener("input", function () {
            let val = this.value.replace(/\D/g, "");
            this.value = val.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        });

        new bootstrap.Modal(document.getElementById("DialogAdd")).show();
    }

    document.getElementById("formAddService").addEventListener("submit", function(e){
        e.preventDefault();

        const formData = new URLSearchParams({
            name: document.getElementById("add_name").value,
            duration: document.getElementById("add_duration").value,
            price: bersihkanAngka(document.getElementById("add_price").value),
        });

        const user_group = <?= $this->session->userdata('logged_in')['USER_GROUP_ID']; ?>;
        if (user_group == 1) {
            formData.append("outlet_id", document.getElementById("add_outlet_id").value);
        }

        fetch('<?= base_url("services/addService") ?>', {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status) {
                showSuccessModal("Perawatan baru berhasil ditambahkan!");

                loadServices();

                const modal = bootstrap.Modal.getInstance(document.getElementById("DialogAdd"));
                modal.hide();
            } else {
                showSuccessModal(data.message, "Error", "error");
            }
        });
    });

</script>

</html>