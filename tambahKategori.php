<?php
    include("koneksiDB.php"); // Pastikan file koneksiDB.php ada dan benar

    // Aktifkan pelaporan error untuk debugging (Hapus di produksi)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori - Dashboard Admin</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <div class="sidebar collapsed">
        <div class="sidebar-header">
            <h3>Dashboard</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="index.php?section=overview"><i class="fas fa-tachometer-alt"></i> <span>Overview</span></a></li>
            <li><a href="index.php?section=products"><i class="fas fa-tshirt"></i> <span>Produk</span></a></li>
            <li class="active"><a href="index.php?section=categories"><i class="fas fa-tags"></i> <span>Kategori</span></a></li>
        </ul>
    </div>

    <div class="main-content">
        <header class="navbar">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <h2>Tambah Kategori</h2>
        </header>

        <div class="dashboard-sections">
            <section id="add-category-form" class="dashboard-section active">
                <h3>Form Tambah Kategori</h3>
                <form action="" method="post" class="settings-form">
                    <div class="form-group">
                        <label for="kategori_nama">Nama Kategori:</label>
                        <input type="text" id="kategori_nama" name="kategori_nama" required>
                    </div>
                    <button type="submit" name="tambah" class="add-button"><i class="fas fa-plus"></i> Tambah Kategori</button>
                    <a href="index.php?section=categories" class="view-button"><i class="fas fa-arrow-left"></i> Kembali</a>
                </form>

                <?php
                    if(isset($_POST["tambah"])) {
                        // Pencegahan SQL Injection: Gunakan mysqli_real_escape_string
                        $nama_kategori = mysqli_real_escape_string($koneksi, $_POST["kategori_nama"]);
                        
                        // Periksa apakah kategori sudah ada
                        $check_query = mysqli_query($koneksi, "SELECT * FROM kategori WHERE kategori = '$nama_kategori'");
                        if (mysqli_num_rows($check_query) > 0) {
                            echo "<p style='color: red; margin-top: 15px;'>Kategori '$nama_kategori' sudah ada!</p>";
                        } else {
                            $query = "INSERT INTO kategori(kategori) VALUES ('$nama_kategori')";

                            if(mysqli_query($koneksi, $query)) {
                                echo "<p style='color: green; margin-top: 15px;'>Kategori berhasil ditambahkan!</p>";
                                // Redirect menggunakan JavaScript setelah 1.5 detik agar pesan terlihat
                                echo "<script>setTimeout(function(){ window.location.href = 'index.php?section=categories'; }, 1500);</script>";
                                // Jangan gunakan header() dan exit() bersamaan dengan setTimeout,
                                // karena header() akan langsung me-redirect sebelum pesan terlihat.
                                // header("Location: index.php?section=categories");
                                // exit();
                            } else {
                                echo "<p style='color: red; margin-top: 15px;'>Gagal menambahkan data: " . mysqli_error($koneksi) . "</p>";
                            }
                        }
                    }
                ?>
            </section>
        </div>
    </div>
    <script src="script.js"></script>
    <script>
        // JS untuk sidebar (dari kode sebelumnya)
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
                sidebar.classList.remove('open');
            } else {
                sidebar.classList.remove('collapsed');
            }
        });
    </script>
</body>
</html>
<?php
    mysqli_close($koneksi); // Tutup koneksi database
?>