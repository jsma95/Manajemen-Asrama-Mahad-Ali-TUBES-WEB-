<?php
session_start();
include "config.php";

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $username_safe = mysqli_real_escape_string($koneksi, $username);

    $sql    = "SELECT * FROM tb_user WHERE username = '$username_safe' LIMIT 1";
    $result = mysqli_query($koneksi, $sql);
    $user   = mysqli_fetch_assoc($result);

    if ($user && $password === $user['password']) {
        $_SESSION['id_user']  = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

       if (strtolower($user['role']) === 'admin') {
            header("Location: admin_pemesanan.php");
        } else {
            header("Location: dashboard.php");
        }
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Asrama Ma'had Aly UINAM</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CUSTOM STYLE -->
    <style>
        /* video background container */
        .video-bg {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        /* video styling */
        .video-bg video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(60%);
        }

        /* card login */
        .login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(4px);
            border-radius: 16px;
            padding: 28px;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.18);
        }

        .btn-green {
            background: #00843D;
            color: white;
            font-weight: 600;
        }
        .btn-green:hover {
            background: #00994d;
            color: white;
        }
    </style>
</head>

<body>

<!-- VIDEO BACKGROUND -->
<div class="video-bg">
    <video autoplay muted loop>
        <source src="video_uin.mp4" type="video/mp4">
        Browser anda tidak mendukung video HTML5.
    </video>
</div>

<!-- LOGIN CARD -->
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="login-card">

        <h4 class="text-center mb-4" style="color:#00843D; font-weight:700;">
            Login Asrama Ma'had Aly
        </h4>

        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-green w-100 py-2">
                Masuk
            </button>
        </form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
