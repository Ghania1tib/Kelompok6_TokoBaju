<?php
    include("koneksiDB.php"); // Pastikan file koneksiDB.php ada dan benar

    // Aktifkan pelaporan error untuk debugging (Hapus di produksi)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Ambil data kategori dari database untuk dropdown
    $query_kategori = mysqli_query($koneksi, "SELECT id, kategori FROM kategori ORDER BY kategori ASC");
    $kategori_options_html = "<option value=''>Pilih Kategori</option>";
    if ($query_kategori && mysqli_num_rows($query_kategori) > 0) {
        while ($data_kategori = mysqli_fetch_array($query_kategori)) {
            $kategori_options_html .= "<option value='" . htmlspecialchars($data_kategori['id']) . "'>" . htmlspecialchars($data_kategori['kategori']) . "</option>";
        }
    } else {
        $kategori_options_html = "<option value=''>Tidak ada kategori tersedia</option>";
    }
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
            <h3>Admin Toko Baju</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="index.php?section=overview"><i class="fas fa-tachometer-alt"></i> <span>Overview</span></a></li>
            <li class="active"><a href="index.php?section=products"><i class="fas fa-tshirt"></i> <span>Produk</span></a></li>
            <li><a href="index.php?section=categories"><i class="fas fa-tags"></i> <span>Kategori</span></a></li>
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
            <h2>Produk</h2>
        </header>

        <div class="dashboard-sections">
            <section id="add-product-form" class="dashboard-section active">
                <h3>Tambah Produk Baru</h3>
                <form action="" method="post" enctype="multipart/form-data" class="settings-form">
                    <div class="form-group">
                        <label for="nama_produk">Nama Produk:</label>
                        <input type="text" id="nama_produk" name="nama_produk" required>
                    </div>
                    <div class="form-group">
                        <label for="kategori_id">Kategori:</label>
                        <select id="kategori_id" name="kategori_id" required>
                            <?php echo $kategori_options_html; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga:</label>
                        <input type="number" id="harga" name="harga" step="0.01" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto Produk:</label>
                        <input type="file" id="foto" name="foto">
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
                        // Pencegahan SQL Injection
                        $nama_produk_input = mysqli_real_escape_string($koneksi, $_POST["nama_produk"]);
                        $kategori_id_input = mysqli_real_escape_string($koneksi, $_POST["kategori_id"]); // Ambil kategori ID
                        $harga_input = mysqli_real_escape_string($koneksi, $_POST["harga"]);
                        $detail_input = mysqli_real_escape_string($koneksi, $_POST["detail"]);
                        $stok_input = mysqli_real_escape_string($koneksi, $_POST["stok_produk"]);

                        // Penanganan upload foto
                        $foto_nama_db = ''; // Default kosong jika tidak ada foto diunggah
                        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                            $target_dir = "uploads/"; // Pastikan folder 'uploads' ada dan bisa ditulis (chmod 777 atau sesuai kebutuhan)
                            if (!is_dir($target_dir)) {
                                mkdir($target_dir, 0777, true); // Buat folder jika belum ada
                            }
                            $foto_nama_original = basename($_FILES["foto"]["name"]);
                            $file_ext = strtolower(pathinfo($foto_nama_original, PATHINFO_EXTENSION));
                            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

                            // Validasi tipe file sederhana
                            if (!in_array($file_ext, $allowed_ext)) {
                                echo "<p style='color: red; margin-top: 15px;'>Hanya file JPG, JPEG, PNG, GIF yang diizinkan.</p>";
                            } else {
                                // Buat nama file unik untuk menghindari timpa file
                                $foto_nama_db = uniqid('produk_') . '.' . $file_ext;
                                $target_file = $target_dir . $foto_nama_db;

                                if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                                    // File berhasil diupload, nama file unik siap disimpan ke database
                                } else {
                                    echo "<p style='color: red; margin-top: 15px;'>Gagal mengunggah foto.</p>";
                                    $foto_nama_db = ''; // Reset jika gagal upload
                                }
                            }
                        }

                        // PERBAIKAN PENTING: Gunakan nama kolom 'nama' dan 'kategori_id'
                        $query = "INSERT INTO produk(nama, kategori_id, harga, foto, detail, stok_produk)
                                VALUES ('$nama_produk_input', '$kategori_id_input', '$harga_input', '$foto_nama_db', '$detail_input', '$stok_input')";

                        if(mysqli_query($koneksi, $query)) {
                            echo "<p style='color: green; margin-top: 15px;'>Produk berhasil ditambahkan!</p>";
                            // PERBAIKAN PENTING: Redirect ke index.php?section=products
                            header("Location: index.php?section=products");
                            exit(); // Penting untuk menghentikan eksekusi script
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
    mysqli_close($koneksi);
?>