<?php
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "db_asrama";

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    if(mysqli_connect_errno()){
        echo "Koneksi gagal: ".mysqli_connect_error();
       }
    $sql = "CREATE TABLE tb_pemesanan (
            id_pemesanan INT AUTO_INCREMENT PRIMARY KEY,
            id_user INT NOT NULL,
            id_kamar INT NOT NULL,
            tanggal_pesan DATE NOT NULL,
            status ENUM('aktif','batal') DEFAULT 'aktif',
            catatan TEXT,
            FOREIGN KEY (id_user) REFERENCES tb_user(id_user),
            FOREIGN KEY (id_kamar) REFERENCES tb_kamar(id_kamar)
            )";
    
    if(mysqli_query($conn, $sql)){
        echo "tabel $dbname berhasil dibuat";
    }else{
        echo "Gagal membuat database: ".mysqli_error($conn);
    }
    mysqli_close($conn);
?>