<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}
include "config.php";

$id_user = $_SESSION['id_user'];
$id_pemesanan = $_GET['id'];

// Ambil pemesanan milik user ini
$sql = "SELECT * FROM tb_pemesanan 
        WHERE id_pemesanan = $id_pemesanan 
          AND id_user = $id_user 
        LIMIT 1";
$res = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_assoc($res);

if (!$data || $data['status'] != 'aktif') {
    die("Pemesanan tidak ditemukan atau sudah tidak bisa diubah.");
}

// Ambil kamar yang masih ada sisa + kamar yg sekarang dipesan (biar tetap muncul)
$sqlKamar = "SELECT k.*, (k.kapasitas - k.terisi) AS sisa 
             FROM tb_kamar k";
$kamarRes = mysqli_query($koneksi, $sqlKamar);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pemesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h3 class="mb-3">Edit Pemesanan Kamar</h3>
    <a href="pemesanan.php" class="btn btn-secondary mb-3">← Kembali ke Pemesanan</a>

    <div class="card p-4">
        <form action="pemesanan_update.php" method="POST">
            <input type="hidden" name="id_pemesanan" value="<?= $data['id_pemesanan']; ?>">

            <div class="mb-3">
                <label for="id_kamar" class="form-label">Pilih Kamar</label>
                <select name="id_kamar" id="id_kamar" class="form-select" required>
                    <?php while ($row = mysqli_fetch_assoc($kamarRes)) : 
                        $sisa = $row['kapasitas'] - $row['terisi'];
                        $selected = $row['id_kamar'] == $data['id_kamar'] ? 'selected' : '';
                        ?>
                        <option value="<?= $row['id_kamar']; ?>" <?= $selected; ?>>
                            <?= $row['nama_kamar']; ?> 
                            (Lantai <?= $row['lantai']; ?>, Sisa: <?= $sisa; ?>, 
                            Harga: Rp <?= number_format($row['harga_pertahun'],0,',','.'); ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="catatan" class="form-label">Catatan</label>
                <textarea name="catatan" id="catatan" class="form-control" rows="3"><?= htmlspecialchars($data['catatan']); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Pemesanan</button>
        </form>
    </div>
</div>

</body>
</html>
