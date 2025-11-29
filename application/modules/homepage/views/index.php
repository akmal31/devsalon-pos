<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Beranda Kasir Salon</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: #fff;
      font-family: 'Poppins', sans-serif;
    }
    .header {
      background-color: #f48fb1;
      padding: 1rem;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom-left-radius: 1rem;
      border-bottom-right-radius: 1rem;
    }
    .balance-card {
      background-color: #fff;
      margin-top: -2rem;
      padding: 1rem;
      border-radius: 1rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .quick-actions .btn {
      width: 100%;
      padding: 1rem;
      border-radius: 1rem;
    }
    .stat-box {
      border-radius: 1rem;
      background-color: #f8f9fa;
      padding: 1rem;
      text-align: center;
    }
    .transactions .card {
      border-radius: 1rem;
    }
    .bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background-color: #fff;
      border-top: 1px solid #ccc;
      display: flex;
      justify-content: space-around;
      padding: 0.5rem 0;
    }
    .bottom-nav a {
      text-decoration: none;
      color: #666;
      font-size: 0.9rem;
      text-align: center;
    }
    .bottom-nav a.active {
      color: #e91e63;
    }
  </style>
</head>
<body>

  <div class="header">
    <div>
      <h5>DevSalon</h5>
      <small>Kasir - Rina</small>
    </div>
    <div>
      <i class="fas fa-bell me-3"></i>
      <img src="https://i.pravatar.cc/40" alt="profile" class="rounded-circle">
    </div>
  </div>

  <div class="container mt-4">
    <div class="balance-card">
      <h6 class="mb-1">📅 Hari Ini</h6>
      <p class="mb-0">Transaksi: <strong>Rp 1.250.000</strong></p>
      <p class="mb-0">Pelanggan: <strong>23 orang</strong></p>
      <p class="mb-0">Stylist Hadir: <strong>5 / 7</strong></p>
    </div>

    <div class="row text-center mt-4 quick-actions">
      <div class="col-6 mb-3">
        <a href="#" class="btn btn-outline-pink"><i class="fas fa-user-plus"></i><br>Customer</a>
      </div>
      <div class="col-6 mb-3">
        <a href="#" class="btn btn-outline-pink"><i class="fas fa-cut"></i><br>Layanan</a>
      </div>
      <div class="col-6 mb-3">
        <a href="#" class="btn btn-outline-pink"><i class="fas fa-cash-register"></i><br>Transaksi</a>
      </div>
      <div class="col-6 mb-3">
        <a href="#" class="btn btn-outline-pink"><i class="fas fa-camera"></i><br>Absen</a>
      </div>
    </div>

    <div class="row text-center">
      <div class="col-6">
        <div class="stat-box mb-3">
          <div class="text-muted">Layanan Terbanyak</div>
          <strong>Hair Spa</strong>
        </div>
      </div>
      <div class="col-6">
        <div class="stat-box mb-3">
          <div class="text-muted">Stylist Teraktif</div>
          <strong>Sari</strong>
        </div>
      </div>
    </div>

    <h6 class="mt-4 mb-2">🧾 Transaksi Terakhir</h6>
    <div class="transactions">
      <div class="card mb-2">
        <div class="card-body d-flex justify-content-between">
          <div>
            <strong>Rina</strong><br>
            <small>Hair Spa</small>
          </div>
          <div class="text-danger">- Rp 250.000</div>
        </div>
      </div>
      <div class="card mb-2">
        <div class="card-body d-flex justify-content-between">
          <div>
            <strong>Dewi</strong><br>
            <small>Haircut</small>
          </div>
          <div class="text-danger">- Rp 80.000</div>
        </div>
      </div>
    </div>
  </div>

  <div class="bottom-nav">
    <a href="#" class="active"><i class="fas fa-home"></i><br>Home</a>
    <a href="#"><i class="fas fa-receipt"></i><br>Transaksi</a>
    <a href="#"><i class="fas fa-chart-bar"></i><br>Laporan</a>
    <a href="#"><i class="fas fa-users"></i><br>Karyawan</a>
    <a href="#"><i class="fas fa-cog"></i><br>Setting</a>
  </div>

</body>
</html>
