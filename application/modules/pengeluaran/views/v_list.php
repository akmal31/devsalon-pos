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
                <h2 class="title">Semua Pengeluaran</h2>
                <a href="<?=base_url().'pengeluaran/add_pengeluaran';?>"><ion-icon name="add-circle-outline"></ion-icon> Tambah</a>
            </div>
            <div class="section-heading">
                <input type="date" class="form-control" id="filter_tanggal">
            </div>
            <div class="transactions" id="transactionsContainer"><p class="text-center"><ion-icon name="telescope-outline" style="font-size: 60px;"></ion-icon><br>- Belum ada pengeluaran -</p></div>
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
                    <h5 class="modal-title">Hapus Pengeluaran</h5>
                </div>
                <div class="modal-body">
                    Anda yakin akan menghapus pengeluaran? Ini akan berpengaruh kepada total cash yang ada.
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
    <!-- Modal Approve -->
    <div class="modal fade" id="DialogApprove" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Pengeluaran</h5>
                </div>
                <div class="modal-body">
                    Anda yakin akan menyetujui pengeluaran?
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a href="#" class="btn btn-text-danger" data-bs-dismiss="modal">
                            <ion-icon name="close-outline"></ion-icon>
                            Tidak
                        </a>

                        <a href="#" class="btn btn-text-primary" id="btnApproveConfirm">
                            <ion-icon name="checkmark-outline"></ion-icon>
                            Yakin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>    


</body>

<script>
    const filterTanggal = document.getElementById('filter_tanggal');
    const transactionsContainer = document.getElementById('transactionsContainer');

    // Formatter angka
    function numberWithCommas(x) {
        // Ubah ke string dan bersihkan semua yang bukan angka
        x = x.toString().replace(/\D/g, "");

        // Kasih titik ribuan
        return x.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Set default ke hari ini
    const today = new Date().toISOString().split('T')[0];
    filterTanggal.value = today;

    const statusLabel = {
        1: '<span class="text-warning">Pengajuan</span>',
        2: '<span class="text-success">Approve</span>',
        3: '<span class="text-danger">Reject</span>'
    };

   const tipePengeluaran = {
        'pengeluaran': '<span class="text-primary">Pengeluaran</span>',
        'ambil laci': '<span class="text-info">Setoran Cash</span>',
        'tip': '<span class="text-info">Tukar Tip</span>'
    };

    // Function load transaksi
    function loadPengeluaran(tanggal) {
        fetch('<?= base_url("pengeluaran/filterPengeluaran") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ date: tanggal })
        })
        .then(res => res.json())
        .then(data => {
            let html = '';

            if(data.length > 0){
                data.forEach(tr => {
                    html += `
                        <div class="item d-flex justify-content-between align-items-center">

                            <a class="flex-grow-1">
                                <div class="detail">
                                    <div>
                                        <strong>${tr.name}</strong>
                                        <strong>Rp. ${numberWithCommas(tr.total_price)}</strong>
                                        <p>
                                            ${new Date(tr.created_at).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'})}
                                            ${new Date(tr.created_at).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'})}
                                            <br>
                                            ${tipePengeluaran[tr.tipe] ?? '<span class="text-secondary">Tidak diketahui</span>'}
                                            <br>
                                            ${statusLabel[tr.status] ?? '<span class="text-secondary">Tidak diketahui</span>'}
                                        </p>
                                    </div>
                                </div>
                            </a>

                            <!-- ICON Approve -->
                            <ion-icon 
                                name="checkmark-circle-outline" 
                                style="font-size: 24px; color:green; margin-left:10px;"
                                onclick="openApproveModal(${tr.id})">
                            </ion-icon>
                            <!-- ICON HAPUS -->
                            <ion-icon 
                                name="trash-outline" 
                                style="font-size: 24px; color:#dc3545; margin-left:10px;"
                                onclick="openDeleteModal(${tr.id})">
                            </ion-icon>

                        </div>
                    `;
                });
            } else {
                html = '<p class="text-center"><ion-icon name="telescope-outline" style="font-size: 60px;"></ion-icon><br>- Belum ada pengeluaran -</p>';
            }

            transactionsContainer.innerHTML = html;
        })
        .catch(err => console.error(err));
    }

    // Load default transaksi hari ini
    loadPengeluaran(today);

    // Event: filter tanggal
    filterTanggal.addEventListener('change', function() {
        loadPengeluaran(this.value);
    });
    
    // Simpan ID pengeluaran yang mau dihapus
    let deleteId = null;

    // Klik icon hapus → tampilkan modal
    function openDeleteModal(id) {
        deleteId = id;
        var myModal = new bootstrap.Modal(document.getElementById('DialogDelete'));
        myModal.show();
    }

    // Klik icon approve → tampilkan modal
    function openApproveModal(id) {
        approveId = id;
        var myModal = new bootstrap.Modal(document.getElementById('DialogApprove'));
        myModal.show();
    }    

    // Klik tombol "Yakin"
    document.getElementById('btnDeleteConfirm').addEventListener('click', function(e){
        e.preventDefault();
        
        if (!deleteId) return;

        fetch("<?= base_url('pengeluaran/delete/') ?>" + deleteId, {
            method: "POST"
        })
        .then(res => res.json())
        .then(data => {
            if (data.status) {

                // Tutup modal
                const modalEl = document.getElementById('DialogDelete');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();

                // Alert sukses
                alert("Pengeluaran berhasil dihapus!");

                // Reload list pengeluaran
                loadPengeluaran(filterTanggal.value);

            } else {
                alert("Gagal menghapus data.");
            }
        })
        .catch(err => alert("Terjadi kesalahan server"));
    });

    let approveId = null;
    // Klik tombol "Yakin Approve"
    document.getElementById('btnApproveConfirm').addEventListener('click', function(e){
        e.preventDefault();

        if (!approveId) return;

        fetch("<?= base_url('pengeluaran/approve/') ?>" + approveId, {
            method: "POST"
        })
        .then(res => res.json())
        .then(data => {
            if (data.status) {

                // Tutup modal
                const modalEl = document.getElementById('DialogApprove');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();

                alert("Pengeluaran berhasil di-approve!");

                // Reload list
                loadPengeluaran(filterTanggal.value);

            } else {
                alert(data.message || "Gagal approve data.");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Terjadi kesalahan server");
        });
    });


</script>


</html>