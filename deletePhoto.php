<?php
session_start();
// jika tidak ada session loogin, maka kembalikan user ke halaman login
if (!isset($_SESSION["login"])) {
    header("Location: login.php");
    exit;
}

require('functions.php');

$id = $_GET["fotoID"];  // id ini yg akan mengambil data dari "id" database, nanti datanya diambil dan akan 
//  ditambahkan ke url sesuai dengan data id yg ada di database.

if (hapusFoto($id) > 0) {
    echo "
        <script>
        alert('data berhasil dihapus');
        document.location.href='index.php';
        </script>
    ";
} else {
    echo "
        <script>
            alert('data tidak berhasil dihapus');
            document.location.href='index.php';
        </script>
    ";
}
