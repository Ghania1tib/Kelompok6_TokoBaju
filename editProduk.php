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
    $kategori_options = mysqli_query($koneksi, "SELECT id, kategori FROM kategori ORDER BY kategori ASC");

    $message = ""; // Untuk pesan sukses/gagal

    if (isset($_POST['update_produk'])) {
        // Pencegahan SQL Injection untuk semua input
        $nama_produk_baru = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
        $harga_baru = mysqli_real_escape_string($koneksi, $_POST['harga']);
        $kategori_id_baru = mysqli_real_escape_string($koneksi, $_POST['kategori_id']);
        $foto_baru = mysqli_real_escape_string($koneksi, $_POST['foto']);
        $detail_baru = mysqli_real_escape_string($koneksi, $_POST['detail']);
        $stok_baru = mysqli_real_escape_string($koneksi, $_POST['stok_produk']);

        // Query UPDATE produk
        // Perbaikan: Pastikan nama kolom sesuai dengan database (nama, harga, kategori_id, foto, detail, stok_produk)
        $query_update = "UPDATE produk SET
                            nama = '$nama_produk_baru',
                            harga = '$harga_baru',
                            kategori_id = '$kategori_id_baru',
                            foto = '$foto_baru',
                            detail = '$detail_baru',
                            stok_produk = '$stok_baru'
                         WHERE id = '$id_produk_edit'";

        if (mysqli_query($koneksi, $query_update)) {
            $message = "<p style='color: green; margin-top: 15px;'>Produk berhasil diupdate!</p>";
            // Perbarui data_produk agar form menampilkan data terbaru setelah update
            $data_produk['nama'] = $nama_produk_baru;
            $data_produk['harga'] = $harga_baru;
            $data_produk['kategori_id'] = $kategori_id_baru;
            $data_produk['foto'] = $foto_baru;
            $data_produk['detail'] = $detail_baru;
            $data_produk['stok_produk'] = $stok_baru;

            // Redirect setelah 1.5 detik
            echo "<script>setTimeout(function(){ window.location.href = 'index.php?section=products'; }, 1500);</script>";
        } else {
            $message = "<p style='color: red; margin-top: 15px;'>Gagal update produk: " . mysqli_error($koneksi) . "</p>";
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
            <h2>Edit Produk</h2>
        </header>

        <div class="dashboard-sections">
            <section id="edit-product-form" class="dashboard-section active">
                <h3>Form Edit Produk</h3>
                <form action="" method="post" class="settings-form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nama_produk">Nama Produk:</label>
                        <input type="text" id="nama_produk" name="nama_produk" value="<?php echo htmlspecialchars($data_produk['nama']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga:</label>
                        <input type="number" id="harga" name="harga" step="0.01" min="0" value="<?php echo htmlspecialchars($data_produk['harga']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="kategori_id">Kategori:</label>
                        <select id="kategori_id" name="kategori_id" required>
                            <option value="">Pilih Kategori</option>
                            <?php
                                if ($kategori_options && mysqli_num_rows($kategori_options) > 0) {
                                    while($row = mysqli_fetch_assoc($kategori_options)) {
                                        // Pilih kategori yang saat ini terkait dengan produk
                                        $selected = ($row['id'] == $data_produk['kategori_id']) ? 'selected' : '';
                                        echo "<option value='" . $row['id'] . "' " . $selected . ">" . htmlspecialchars($row['kategori']) . "</option>";
                                    }
                                } else {
                                    echo "<option value=''>Tidak ada kategori tersedia</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto Produk (Nama File / URL):</label>
                        <input type="text" id="foto" name="foto" value="<?php echo htmlspecialchars($data_produk['foto']); ?>" placeholder="contoh: kemeja-batik.jpg">
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