<?php
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "db_asrama";

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass);
    if(mysqli_connect_errno()){
        echo "Koneksi gagal: ".mysqli_connect_error();
       }
    $sql = "CREATE DATABASE $dbname";
    if(mysqli_query($conn, $sql)){
        echo "Database $dbname berhasil dibuat";
    }else{
        echo "Gagal membuat database: ".mysqli_error($conn);
    }
    mysqli_close($conn);
?>