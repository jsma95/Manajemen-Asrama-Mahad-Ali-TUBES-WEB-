<?php
session_start();
include "config.php"; // di sini harus ada $koneksi

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ambil dari form (USERNAME + PASSWORD)
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // amankan input
    $u = mysqli_real_escape_string($koneksi, $username);
    $p = mysqli_real_escape_string($koneksi, $password);

    // cari user berdasarkan username
    $sql    = "SELECT * FROM tb_user WHERE username = '$u' LIMIT 1";
    $result = mysqli_query($koneksi, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        // bandingkan dengan kolom "password" di database
        if ($p === $user['password']) {
            // login sukses → set session
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama']    = $user['nama'];    // untuk ditampilkan
            $_SESSION['role']    = $user['role'];    // 'user' / 'admin'

            // redirect sesuai role
            if ($user['role'] === 'admin') {
                header("Location: admin_pemesanan.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;
        }
    }

    // kalau gagal di salah satu langkah di atas:
    $error = "Username atau password salah";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Asrama Ma'had Aly UINAM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow" style="max-width: 400px; width: 100%;">
        <div class="card-body">
            <h4 class="card-title text-center mb-4">Login Asrama Ma'had Aly</h4>

            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input
                        type="text"
                        name="username"
                        id="username"
                        class="form-control"
                        placeholder="Masukkan username Anda"
                        required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="Masukkan password Anda"
                        required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
