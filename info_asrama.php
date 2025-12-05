<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include "config.php";

$nama = $_SESSION['nama'];

// Ambil daftar harga kamar
$sql = "SELECT nama_kamar, harga_pertahun, fasilitas 
        FROM tb_kamar 
        ORDER BY harga_pertahun ASC";
$result = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Informasi Asrama Ma'had Aly UINAM</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<!-- NAVBAR SAMA STYLE DENGAN HALAMAN LAIN -->
<nav class="navbar">
    <div class="navbar-left">
        <a href="dashboard.php" class="navbar-brand">
            Ma'had Aly UINAM
        </a>
    </div>

    <ul class="navbar-menu">
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="pemesanan.php">Pemesanan</a></li>
        <li><a href="pemesanan_tambah.php">Pesan Kamar</a></li>
        <li><a href="kamar_kosong.php">Kamar Kosong</a></li>
        <li><a href="info_asrama.php" class="active">Informasi Asrama</a></li>
    </ul>

    <div class="navbar-right">
        <span>👤 <?= htmlspecialchars($nama); ?></span>
        <a href="logout.php">
            <button class="navbar-logout-btn">Logout</button>
        </a>
    </div>
</nav>

<main class="page-container">

    <!-- HEADER HALAMAN -->
    <header class="page-header">
        <h1 class="page-title">Informasi Asrama Ma'had Aly UINAM</h1>
        <p class="page-subtitle">
            Ringkasan fasilitas, harga kamar, dan aturan tinggal di asrama.
        </p>
    </header>

    <!-- Informasi umum -->
    <section class="info-section">
        <h2 class="info-title">Informasi Umum Asrama</h2>
        <p class="info-text">
            Asrama Ma'had Aly UIN Alauddin Makassar adalah tempat tinggal khusus mahasiswa 
            yang ingin mendapatkan lingkungan belajar yang nyaman, disiplin, dan religius.
            Setiap penghuni mendapatkan akses fasilitas memadai serta pendampingan keagamaan
            untuk mendukung proses belajar selama kuliah.
        </p>
    </section>

    <!-- Fasilitas utama -->
    <section class="info-section">
        <h3 class="info-subtitle">Fasilitas Utama Asrama</h3>
        <ul class="info-list">
            <li>Kamar tidur bersama (2–4 orang per kamar).</li>
            <li>Kasur, lemari, dan meja belajar di setiap kamar.</li>
            <li>Akses wifi 24 jam.</li>
            <li>Air bersih dan sanitasi yang terjaga.</li>
            <li>Ruang belajar bersama.</li>
            <li>Keamanan 24 jam oleh pengelola asrama.</li>
        </ul>
    </section>

    <!-- Daftar harga -->
    <section class="info-section">
        <h3 class="info-subtitle">Daftar Harga Kamar Per Tahun</h3>

        <div class="info-table-wrapper">
            <table class="info-table">
                <thead>
                    <tr>
                        <th>Nama Kamar</th>
                        <th>Harga / Tahun</th>
                        <th>Fasilitas Khusus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama_kamar']); ?></td>
                            <td>Rp <?= number_format($row['harga_pertahun'], 0, ',', '.'); ?></td>
                            <td><?= nl2br(htmlspecialchars($row['fasilitas'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Aturan penghuni -->
    <section class="info-section">
        <h3 class="info-subtitle">Aturan & Ketentuan Penghuni Asrama</h3>
        <ol class="info-list">
            <li>Menjaga kebersihan kamar dan area bersama.</li>
            <li>Menjaga ketertiban, ketenangan, dan kedisiplinan waktu.</li>
            <li>Menghindari pelanggaran norma agama dan peraturan kampus.</li>
            <li>Melapor ke pengurus jika ditemukan kerusakan fasilitas.</li>
            <li>Pembayaran biaya asrama dilakukan setiap awal tahun akademik.</li>
        </ol>
    </section>

</main>
</body>
</html>
