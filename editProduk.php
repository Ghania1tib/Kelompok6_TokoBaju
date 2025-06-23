<?php
    include("koneksiDB.php"); // Pastikan file koneksiDB.php ada dan benar

    // Aktifkan pelaporan error untuk debugging (Hapus di produksi)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $id_produk_edit = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : null;

    if (!$id_produk_edit) {
        // Jika ID tidak ada, redirect kembali ke daftar produk
        header("Location: index.php?section=products");
        exit();
    }

    // Ambil data produk yang akan diedit
    $query_produk = mysqli_query($koneksi, "SELECT * FROM produk WHERE id = '$id_produk_edit'");
    $data_produk = mysqli_fetch_assoc($query_produk);

    if (!$data_produk) {
        // Jika produk tidak ditemukan, redirect kembali
        header("Location: index.php?section=products");
        exit();
    }

    // Ambil daftar kategori dari database untuk dropdown
    $query_kategori_options = mysqli_query($koneksi, "SELECT id, kategori FROM kategori ORDER BY kategori ASC");
    $kategori_options_html = "";
    if ($query_kategori_options && mysqli_num_rows($query_kategori_options) > 0) {
        while ($data_kategori_opt = mysqli_fetch_array($query_kategori_options)) {
            $selected = ($data_kategori_opt['id'] == $data_produk['kategori_id']) ? 'selected' : '';
            $kategori_options_html .= "<option value='" . htmlspecialchars($data_kategori_opt['id']) . "' $selected>" . htmlspecialchars($data_kategori_opt['kategori']) . "</option>";
        }
    } else {
        $kategori_options_html = "<option value=''>Tidak ada kategori tersedia</option>";
    }

    $message = ""; // Untuk pesan sukses/gagal

    if (isset($_POST['update_produk'])) {
        // Pencegahan SQL Injection untuk semua input
        $nama_produk_input = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
        $kategori_id_input = mysqli_real_escape_string($koneksi, $_POST['kategori_id']);
        $harga_input = mysqli_real_escape_string($koneksi, $_POST['harga']);
        $detail_input = mysqli_real_escape_string($koneksi, $_POST['detail']);
        $stok_input = mysqli_real_escape_string($koneksi, $_POST['stok_produk']);

        $foto_lama = $data_produk['foto']; // Ambil nama foto lama dari database

        // Penanganan upload foto baru
        $foto_nama_db = $foto_lama; // Default menggunakan foto lama
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $target_dir = "uploads/";
            $foto_nama_original = basename($_FILES["foto"]["name"]);
            $file_ext = strtolower(pathinfo($foto_nama_original, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($file_ext, $allowed_ext)) {
                $message = "<p style='color: red; margin-top: 15px;'>Hanya file JPG, JPEG, PNG, GIF yang diizinkan.</p>";
            } else {
                // Hapus foto lama jika ada dan file baru berhasil diupload
                if (!empty($foto_lama) && file_exists($target_dir . $foto_lama)) {
                    unlink($target_dir . $foto_lama);
                }

                $foto_nama_db = uniqid('produk_') . '.' . $file_ext;
                $target_file = $target_dir . $foto_nama_db;

                if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                    // Foto baru berhasil diupload dan nama file unik siap disimpan
                } else {
                    $message = "<p style='color: red; margin-top: 15px;'>Gagal mengunggah foto baru.</p>";
                    $foto_nama_db = $foto_lama; // Kembali ke foto lama jika gagal upload
                }
            }
        } else if (isset($_POST['hapus_foto']) && $_POST['hapus_foto'] == '1') {
            // Logika untuk menghapus foto (jika ada checkbox/button "hapus foto")
            if (!empty($foto_lama) && file_exists($target_dir . $foto_lama)) {
                unlink($target_dir . $foto_lama);
            }
            $foto_nama_db = ''; // Kosongkan nama foto di database
        }


        // Query UPDATE produk (perhatikan nama kolom 'nama')
        $query_update = "UPDATE produk SET
                            nama = '$nama_produk_input',
                            kategori_id = '$kategori_id_input',
                            harga = '$harga_input',
                            foto = '$foto_nama_db',
                            detail = '$detail_input',
                            stok_produk = '$stok_input'
                         WHERE id = '$id_produk_edit'";

        if (mysqli_query($koneksi, $query_update)) {
            $message = "<p style='color: green; margin-top: 15px;'>Produk berhasil diupdate!</p>";
            // Perbarui data_produk agar form menampilkan data terbaru setelah update
            $query_produk_updated = mysqli_query($koneksi, "SELECT * FROM produk WHERE id = '$id_produk_edit'");
            $data_produk = mysqli_fetch_assoc($query_produk_updated); // Refresh data
        } else {
            $message = "<p style='color: red; margin-top: 15px;'>Gagal update data: " . mysqli_error($koneksi) . "</p>";
        }
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Dashboard Admin</title>
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
            <section id="edit-product-form" class="dashboard-section active">
                <h3>Edit Produk: <?php echo htmlspecialchars($data_produk['nama']); ?></h3>
                <form action="" method="post" enctype="multipart/form-data" class="settings-form">
                    <div class="form-group">
                        <label for="nama_produk">Nama Produk:</label>
                        <input type="text" id="nama_produk" name="nama_produk" value="<?php echo htmlspecialchars($data_produk['nama']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="kategori_id">Kategori:</label>
                        <select id="kategori_id" name="kategori_id" required>
                            <?php echo $kategori_options_html; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga:</label>
                        <input type="number" id="harga" name="harga" step="0.01" min="0" value="<?php echo htmlspecialchars($data_produk['harga']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto Produk Saat Ini:</label>
                        <?php if (!empty($data_produk['foto'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($data_produk['foto']); ?>" alt="Foto Produk" style="max-width: 150px; height: auto; display: block; margin-bottom: 10px;">
                            <input type="checkbox" name="hapus_foto" value="1"> <label for="hapus_foto">Hapus Foto Ini</label>
                        <?php else: ?>
                            <p>Tidak ada foto produk saat ini.</p>
                        <?php endif; ?>
                        <label for="foto_baru">Unggah Foto Baru (opsional):</label>
                        <input type="file" id="foto_baru" name="foto">
                    </div>
                    <div class="form-group">
                        <label for="detail">Detail Produk:</label>
                        <textarea id="detail" name="detail" rows="4"><?php echo htmlspecialchars($data_produk['detail']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="stok_produk">Stok:</label>
                        <select id="stok_produk" name="stok_produk" required>
                            <option value="tersedia" <?php echo ($data_produk['stok_produk'] == 'tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                            <option value="habis" <?php echo ($data_produk['stok_produk'] == 'habis') ? 'selected' : ''; ?>>Habis</option>
                        </select>
                    </div>
                    <button type="submit" name="update_produk" class="save-button"><i class="fas fa-save"></i> Update Produk</button>
                    <a href="index.php?section=products" class="view-button"><i class="fas fa-arrow-left"></i> Kembali</a>
                </form>

                <?php echo $message; // Tampilkan pesan sukses/gagal di sini ?>
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
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('collapsed');
            });
        }
    </script>
</body>
</html>
<?php
    mysqli_close($koneksi); // Tutup koneksi database
?>