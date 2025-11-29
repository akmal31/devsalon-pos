# Salon POS & Komisi System (CodeIgniter 3 – HMVC)

Aplikasi Point of Sale (POS) untuk salon kecantikan yang mendukung pencatatan transaksi, pembagian komisi karyawan, manajemen layanan, dan cetak struk.  
Dibangun dengan **CodeIgniter 3**, arsitektur **HMVC**, dan integrasi **Thermal Bluetooth Printer**.

---

## ⭐ Fitur Utama

### 1. **Manajemen Customer**
- Input data pelanggan (nama, alamat, nomor HP)
- Riwayat transaksi pelanggan

### 2. **Manajemen Layanan & Paket**
- Tambah/edit layanan dengan harga
- Status aktif/nonaktif
- Paket treatment (bundle)

### 3. **Transaksi & POS**
- Pilih layanan dan staff
- Hitung total otomatis
- Support diskon
- Cetak struk via **thermal printer bluetooth**
- Tampilan UI responsif untuk kasir

### 4. **Sistem Komisi Staff**
- Input komisi untuk tiap staff per layanan
- Auto-format rupiah (Rp 10.000)
- Validasi otomatis:
  - **Komisi harus sama dengan total harga layanan**
  - Tombol "Simpan Komisi" otomatis enable/disable
- Summary transaksi lengkap (staff + nilai komisi)

### 5. **Multi Cabang**
- Setiap transaksi terkait cabang salon
- Pengaturan store

### 6. **Dashboard Kasir**
- List transaksi harian
- Rekap total pemasukan
- Status pembayaran

---

## 🧱 Teknologi yang Digunakan

- **CodeIgniter 3**
- **HMVC Modular Extensions**
- PHP 7+
- MySQL / MariaDB
- jQuery (format input, AJAX)
- Bootstrap
- Thermal Printer (ESC/POS)

---