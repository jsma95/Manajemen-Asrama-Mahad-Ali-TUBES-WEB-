<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}
include "config.php";

$query = "SELECT * FROM tb_kamar ORDER BY lantai, nama_kamar";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Kamar Asrama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h3 class="mb-3">Daftar Semua Kamar Asrama</h3>
    <a href="dashboard.php" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Kamar</th>
                <th>Lantai</th>
                <th>Kapasitas</th>
                <th>Terisi</th>
                <th>Sisa</th>
                <th>Harga / Tahun</th>
                <th>Fasilitas</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) :
            $sisa = $row['kapasitas'] - $row['terisi'];
        ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['nama_kamar']); ?></td>
                <td><?= $row['lantai']; ?></td>
                <td><?= $row['kapasitas']; ?></td>
                <td><?= $row['terisi']; ?></td>
                <td>
                    <?= $sisa; ?>
                    <?= $sisa > 0 ? '<span class="badge bg-success ms-1">Tersedia</span>' : '<span class="badge bg-danger ms-1">Penuh</span>'; ?>
                </td>
                <td>Rp <?= number_format($row['harga_pertahun'], 0, ',', '.'); ?></td>
                <td><?= nl2br(htmlspecialchars($row['fasilitas'])); ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
