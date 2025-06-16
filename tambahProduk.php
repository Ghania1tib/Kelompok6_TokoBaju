<?php
    include("koneksiDB.php"); // Pastikan file koneksiDB.php ada dan benar

    // Aktifkan pelaporan error untuk debugging (Hapus di produksi)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Ambil daftar kategori dari database untuk dropdown
    $kategori_options = mysqli_query($koneksi, "SELECT id, kategori FROM kategori ORDER BY kategori ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Dashboard Admin</title>
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
            <li class="active"><a href="index.php?section=products"><i class="fas fa-tshirt"></i> <span>Produk</span></a></li>
            <li><a href="index.php?section=categories"><i class="fas fa-tags"></i> <span>Kategori</span></a></li>            
        </ul>
    </div>

    <div class="main-content">
        <header class="navbar">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <h2>Tambah Produk</h2>
        </header>

        <div class="dashboard-sections">
            <section id="add-product-form" class="dashboard-section active">
                <h3>Form Tambah Produk</h3>
                <form action="" method="post" class="settings-form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nama_produk">Nama Produk:</label>
                        <input type="text" id="nama_produk" name="nama_produk" required>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga:</label>
                        <input type="number" id="harga" name="harga" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="kategori_id">Kategori:</label>
                        <select id="kategori_id" name="kategori_id" required>
                            <option value="">Pilih Kategori</option>
                            <?php
                                if ($kategori_options && mysqli_num_rows($kategori_options) > 0) {
                                    while($row = mysqli_fetch_assoc($kategori_options)) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['kategori'] . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>Tidak ada kategori tersedia</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto Produk (Nama File / URL):</label>
                        <input type="text" id="foto" name="foto" placeholder="contoh: kemeja-batik.jpg">
                    </div>
                    <div class="form-group">
                        <label for="detail">Detail Produk:</label>
                        <textarea id="detail" name="detail" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="stok_produk">Stok:</label>
                        <select id="stok_produk" name="stok_produk" required>
                            <option value="tersedia">Tersedia</option>
                            <option value="habis">Habis</option>
                        </select>
                    </div>
                    <button type="submit" name="tambah" class="add-button"><i class="fas fa-plus"></i> Tambah Produk</button>
                    <a href="index.php?section=products" class="view-button"><i class="fas fa-arrow-left"></i> Kembali</a>
                </form>

                <?php
                    if(isset($_POST["tambah"])) {
                        // Pencegahan SQL Injection: Gunakan mysqli_real_escape_string()
                        $nama_produk = mysqli_real_escape_string($koneksi, $_POST["nama_produk"]);
                        $harga = mysqli_real_escape_string($koneksi, $_POST["harga"]);
                        $kategori_id = mysqli_real_escape_string($koneksi, $_POST["kategori_id"]);
                        $foto = mysqli_real_escape_string($koneksi, $_POST["foto"]);
                        $detail = mysqli_real_escape_string($koneksi, $_POST["detail"]);
                        $stok = mysqli_real_escape_string($koneksi, $_POST["stok_produk"]);

                        // Perbaikan: Ubah nama kolom 'nama_produk' menjadi 'nama' sesuai database toko_baju.sql
                        // Tambahkan 'kategori_id' ke query INSERT
                        $query = "INSERT INTO produk(nama, harga, kategori_id, foto, detail, stok_produk)
                                VALUES ('$nama_produk', '$harga', '$kategori_id', '$foto', '$detail', '$stok')";

                        if(mysqli_query($koneksi, $query)) {
                            echo "<p style='color: green; margin-top: 15px;'>Produk berhasil ditambahkan!</p>";
                            // Redirect menggunakan JavaScript setelah 1.5 detik agar pesan terlihat
                            echo "<script>setTimeout(function(){ window.location.href = 'index.php?section=products'; }, 1500);</script>";
                            // Jangan gunakan header() dan exit() bersamaan dengan setTimeout
                        } else {
                            echo "<p style='color: red; margin-top: 15px;'>Gagal menambahkan data: " . mysqli_error($koneksi) . "</p>";
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