<?php
    include("koneksiDB.php");

    $id = $_GET['id'];

    $query = "DELETE FROM produk WHERE ID = '$id'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php");
        
    } else {
        echo "Gagal menghapus data!";
    }
?>