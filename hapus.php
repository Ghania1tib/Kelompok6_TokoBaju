<?php
    include("koneksiDB.php"); // Pastikan file koneksiDB.php ada

    // Aktifkan pelaporan error untuk debugging (Hapus di produksi)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Ambil ID dan tipe dari parameter GET
    $id_hapus = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : '';
    $type = isset($_GET['type']) ? $_GET['type'] : '';

    if (empty($id_hapus) || empty($type)) {
        echo "<script>alert('Error: Parameter ID atau tipe penghapusan tidak lengkap.'); window.location.href='index.php';</script>";
        exit();
    }

    $query_delete = "";
    $redirect_section = "overview"; // Default redirect
    $pesan_gagal = "Gagal menghapus data.";

    if ($type == 'produk') {
        // Ketika produk dihapus, trigger after_delete_produk akan otomatis mencatatnya
        $query_delete = "DELETE FROM produk WHERE id = '$id_hapus'";
        $redirect_section = "products";
        $pesan_gagal = "Gagal menghapus produk: " . mysqli_error($koneksi);
    } else if ($type == 'kategori') {
        // PERHATIAN: Pastikan tidak ada produk yang terkait dengan kategori ini sebelum dihapus
        $check_produk_query = mysqli_query($koneksi, "SELECT COUNT(*) AS total_produk FROM produk WHERE kategori_id = '$id_hapus'");
        if ($check_produk_query) {
            $data_produk_count = mysqli_fetch_assoc($check_produk_query);
            if ($data_produk_count['total_produk'] > 0) {
                echo "<script>alert('Gagal menghapus kategori: Terdapat produk yang masih terhubung dengan kategori ini. Harap hapus produk terkait terlebih dahulu.'); window.location.href='index.php?section=categories';</script>";
                exit(); // Hentikan eksekusi
            }
        } else {
            echo "<script>alert('Error saat memeriksa produk terkait: " . mysqli_error($koneksi) . "'); window.location.href='index.php?section=categories';</script>";
            exit();
        }

        $query_delete = "DELETE FROM kategori WHERE id = '$id_hapus'";
        $redirect_section = "categories";
        $pesan_gagal = "Gagal menghapus kategori: " . mysqli_error($koneksi);
    } else {
        echo "<script>alert('Error: Tipe penghapusan tidak valid.'); window.location.href='index.php';</script>";
        exit();
    }

    if (!empty($query_delete)) {
        if (mysqli_query($koneksi, $query_delete)) {
            echo "<script>alert('Data berhasil dihapus!'); window.location.href='index.php?section=" . $redirect_section . "';</script>";
            exit();
        } else {
            echo "<script>alert('Error: " . $pesan_gagal . "'); window.location.href='index.php?section=" . $redirect_section . "';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Error: Query penghapusan tidak terbentuk.'); window.location.href='index.php';</script>";
        exit();
    }

    mysqli_close($koneksi); // Tutup koneksi database
?>