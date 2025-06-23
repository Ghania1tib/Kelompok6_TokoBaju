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
            <li><a href="index.php?section=delete-logs"><i class="fas fa-history"></i> <span>Log Hapus Produk</span></a></li>
            <li><a href="index.php?section=product-view"><i class="fas fa-eye"></i> <span>Produk Lengkap (VIEW)</span></a></li>
            <li><a href="index.php?section=product-procedure"><i class="fas fa-tasks"></i> <span>Produk via SP</span></a></li>
            <li><a href="index.php?section=settings"><i class="fas fa-cogs"></i> <span>Pengaturan</span></a></li>
            <li><a href="#" data-section="logout"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
    </div>

    <div class="main-content">
        <header class="navbar">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <h2>Kategori</h2>
        </header>

        <div class="dashboard-sections">
            <section id="add-category-form" class="dashboard-section active">
                <h3>Tambah Kategori Baru</h3>
                <form action="" method="post" class="settings-form">
                    <div class="form-group">
                        <label for="kategori_nama">Nama Kategori:</label>
                        <input type="text" id="kategori_nama" name="kategori_nama" required>
                    </div>
                    <button type="submit" name="tambah_kategori" class="add-button"><i class="fas fa-plus"></i> Tambah Kategori</button>
                    <a href="index.php?section=categories" class="view-button"><i class="fas fa-arrow-left"></i> Kembali</a>
                </form>

                <?php
                    if(isset($_POST["tambah_kategori"])) {
                        // Perbaikan: Ambil dari input yang benar dan sanitasi
                        $kategori_input = mysqli_real_escape_string($koneksi, $_POST["kategori_nama"]);

                        // Perbaikan: Cek apakah kategori sudah ada
                        $check_query = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM kategori WHERE kategori = '$kategori_input'");
                        $check_data = mysqli_fetch_assoc($check_query);

                        if ($check_data['total'] > 0) {
                            echo "<p style='color: red; margin-top: 15px;'>Kategori \"". htmlspecialchars($kategori_input) ."\" sudah ada!</p>";
                        } else {
                            $query = "INSERT INTO kategori(kategori) VALUES ('$kategori_input')";

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
            // Contoh: Untuk mobile, paksa collapsed agar tidak mengganggu layout saat baru dibuka
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
                sidebar.classList.remove('open');
            } else {
                sidebar.classList.remove('collapsed'); // Default desktop: terbuka
            }
        });
    </script>
</body>
</html>
<?php
    mysqli_close($koneksi);
?>