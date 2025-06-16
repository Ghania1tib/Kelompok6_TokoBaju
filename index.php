<?php
    // Asumsikan koneksiDB.php sudah ada dan berisi variabel $koneksi
    include("koneksiDB.php");

    // Aktifkan pelaporan error untuk debugging (Hapus di produksi)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Dapatkan section dari URL, default ke 'overview'
    $currentSection = isset($_GET['section']) ? $_GET['section'] : 'overview';

    // Mendapatkan data ringkasan untuk Overview
    $totalKategori = 0;
    $resultKategori = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM kategori");
    if ($resultKategori && mysqli_num_rows($resultKategori) > 0) {
        $dataKategori = mysqli_fetch_assoc($resultKategori);
        $totalKategori = $dataKategori['total'];
    }

    $totalProduk = 0;
    $hargaTertinggi = 0;
    $hargaTerendah = 0;
    $rataRataHarga = 0;

    $resultProduk = mysqli_query($koneksi, "SELECT COUNT(*) AS total, MAX(harga) AS max_harga, MIN(harga) AS min_harga, AVG(harga) AS avg_harga FROM produk");
    if ($resultProduk && mysqli_num_rows($resultProduk) > 0) {
        $dataProduk = mysqli_fetch_assoc($resultProduk);
        $totalProduk = $dataProduk['total'];
        $hargaTertinggi = $dataProduk['max_harga'] ? number_format($dataProduk['max_harga'], 0, ',', '.') : '0';
        $hargaTerendah = $dataProduk['min_harga'] ? number_format($dataProduk['min_harga'], 0, ',', '.') : '0';
        $rataRataHarga = $dataProduk['avg_harga'] ? number_format($dataProduk['avg_harga'], 0, ',', '.') : '0';
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Toko Baju</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>Dashboard</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="index.php?section=overview" class="<?php echo ($currentSection == 'overview' ? 'active' : ''); ?>" data-section="overview"><i class="fas fa-tachometer-alt"></i> <span>Overview</span></a></li>
            <li><a href="index.php?section=products" class="<?php echo ($currentSection == 'products' ? 'active' : ''); ?>" data-section="products"><i class="fas fa-tshirt"></i> <span>Produk</span></a></li>
            <li><a href="index.php?section=categories" class="<?php echo ($currentSection == 'categories' ? 'active' : ''); ?>" data-section="categories"><i class="fas fa-tags"></i> <span>Kategori</span></a></li>
        </ul>
    </div>

    <div class="main-content">
        <header class="navbar">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <h2>Selamat Datang, Admin!</h2>
        </header>

        <div class="dashboard-sections">
            <section id="overview" class="dashboard-section <?php echo ($currentSection == 'overview' ? 'active' : ''); ?>">
                <h3>Overview Dashboard</h3>
                <div class="cards">
                    <div class="card">
                        <h4>Total Kategori</h4>
                        <p><?php echo $totalKategori; ?></p>
                    </div>
                    <div class="card">
                        <h4>Total Produk</h4>
                        <p><?php echo $totalProduk; ?></p>
                    </div>
                    <div class="card">
                        <h4>Harga Tertinggi</h4>
                        <p>Rp <?php echo $hargaTertinggi; ?></p>
                    </div>
                    <div class="card">
                        <h4>Harga Terendah</h4>
                        <p>Rp <?php echo $hargaTerendah; ?></p>
                    </div>
                    <div class="card">
                        <h4>Rata-rata Harga</h4>
                        <p>Rp <?php echo $rataRataHarga; ?></p>
                    </div>
                </div>
            </section>

            <section id="products" class="dashboard-section <?php echo ($currentSection == 'products' ? 'active' : ''); ?>">
                <h3>Daftar Produk</h3>
                <a href="tambahProduk.php" class="add-button"><i class="fas fa-plus"></i> Tambah Produk Baru</a>
                <hr>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Foto Produk</th>
                                <th>Detail Produk</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no_produk = 1;
                                // Perbaikan: Ambil kolom 'nama' bukan 'nama_produk' dari database
                                $query_produk = mysqli_query($koneksi,"SELECT * FROM produk");
                                if (mysqli_num_rows($query_produk) > 0) {
                                    while ($data_produk = mysqli_fetch_array($query_produk)) {
                            ?>
                            <tr>
                                <td><?php echo $no_produk++ ?></td>
                                <td><?php echo $data_produk['nama'] ?></td>
                                <td>Rp <?php echo number_format($data_produk['harga'], 0, ',', '.'); ?></td>
                                <td><?php echo $data_produk['foto'] ?></td>
                                <td><?php echo $data_produk['detail'] ?></td>
                                <td><?php echo $data_produk['stok_produk'] ?></td>
                                <td>
                                    <a href="editProduk.php?id=<?php echo $data_produk['id']?>" class="edit-button"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="hapusProduk.php?id=<?php echo $data_produk['id']?>" class="delete-button" onclick="return confirm('Yakin ingin menghapus?')"><i class="fas fa-trash"></i> Hapus</a>
                                </td>
                            </tr>
                            <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>Tidak ada data produk.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="categories" class="dashboard-section <?php echo ($currentSection == 'categories' ? 'active' : ''); ?>">
                <h3>Daftar Kategori</h3>
                <a href="tambahKategori.php" class="add-button"><i class="fas fa-plus"></i> Tambah Kategori Baru</a>
                <hr>
                <div class="table-container">
                    <table border="1">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no_kategori = 1;
                                $query_kategori = mysqli_query($koneksi,"SELECT * FROM kategori");
                                if (mysqli_num_rows($query_kategori) > 0) {
                                    while ($data_kategori = mysqli_fetch_array($query_kategori)) {
                            ?>
                            <tr>
                                <td><?php echo $no_kategori++ ?></td>
                                <td><?php echo $data_kategori['kategori'] ?></td>
                                <td>
                                    <a href="edit.php?id=<?php echo $data_kategori['id']?>" class="edit-button"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="hapus.php?id=<?php echo $data_kategori['id']?>" class="delete-button" onclick="return confirm('Yakin ingin menghapus?')"><i class="fas fa-trash"></i> Hapus</a>
                                </td>
                            </tr>
                            <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='3'>Tidak ada data kategori.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="orders" class="dashboard-section <?php echo ($currentSection == 'orders' ? 'active' : ''); ?>">
                <h3>Daftar Pesanan</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ORD001</td>
                                <td>Budi Santoso</td>
                                <td>Rp 200.000</td>
                                <td><span class="status-pending">Pending</span></td>
                                <td>2025-06-15</td>
                                <td>
                                    <button class="view-button"><i class="fas fa-eye"></i> Lihat</button>
                                    <button class="update-button"><i class="fas fa-sync-alt"></i> Update</button>
                                </td>
                            </tr>
                            <tr>
                                <td>ORD002</td>
                                <td>Siti Aminah</td>
                                <td>Rp 100.000</td>
                                <td><span class="status-completed">Completed</span></td>
                                <td>2025-06-14</td>
                                <td>
                                    <button class="view-button"><i class="fas fa-eye"></i> Lihat</button>
                                    <button class="update-button"><i class="fas fa-sync-alt"></i> Update</button>
                                </td>
                            </tr>
                            </tbody>
                    </table>
                </div>
            </section>

            <section id="customers" class="dashboard-section <?php echo ($currentSection == 'customers' ? 'active' : ''); ?>">
                <h3>Manajemen Pelanggan</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Pelanggan</th>
                                <th>Email</th>
                                <th>No. Telepon</th>
                                <th>Jumlah Pesanan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>CUST001</td>
                                <td>Budi Santoso</td>
                                <td>budi@email.com</td>
                                <td>08123456789</td>
                                <td>5</td>
                                <td>
                                    <button class="view-button"><i class="fas fa-eye"></i> Lihat</button>
                                    <button class="delete-button"><i class="fas fa-trash"></i> Hapus</button>
                                </td>
                            </tr>
                            </tbody>
                    </table>
                </div>
            </section>

            <section id="settings" class="dashboard-section <?php echo ($currentSection == 'settings' ? 'active' : ''); ?>">
                <h3>Pengaturan</h3>
                <form action="" method="post" class="settings-form">
                    <div class="form-group">
                        <label for="site-name">Nama Toko:</label>
                        <input type="text" id="site-name" name="site_name" value="Toko Baju Fashionku">
                    </div>
                    <div class="form-group">
                        <label for="admin-email">Email Admin:</label>
                        <input type="email" id="admin-email" name="admin_email" value="admin@tokobaju.com">
                    </div>
                    <button type="submit" name="save_settings" class="save-button"><i class="fas fa-save"></i> Simpan Pengaturan</button>
                </form>

                <?php
                    // Logika PHP untuk memproses form pengaturan
                    if (isset($_POST['save_settings'])) {
                        // Pencegahan SQL Injection
                        $site_name = mysqli_real_escape_string($koneksi, $_POST['site_name']);
                        $admin_email = mysqli_real_escape_string($koneksi, $_POST['admin_email']);

                        // --- PENTING ---
                        // Anda perlu membuat tabel di database Anda (misalnya 'pengaturan')
                        // untuk menyimpan data ini secara permanen.
                        // Contoh query UPDATE (asumsi ada baris pengaturan dengan ID 1)
                        // CREATE TABLE pengaturan (id INT PRIMARY KEY AUTO_INCREMENT, nama_toko VARCHAR(255), email_admin VARCHAR(255));
                        // INSERT INTO pengaturan (id, nama_toko, email_admin) VALUES (1, 'Toko Baju Fashionku', 'admin@tokobaju.com');

                        // Query contoh (akan error jika tabel 'pengaturan' tidak ada)
                        // $update_settings_query = "UPDATE pengaturan SET nama_toko = '$site_name', email_admin = '$admin_email' WHERE id = 1";

                        // if (mysqli_query($koneksi, $update_settings_query)) {
                        //     echo "<p style='color: green; margin-top: 15px;'>Pengaturan berhasil disimpan ke database!</p>";
                        //     echo "<script>setTimeout(function(){ window.location.href = 'index.php?section=settings'; }, 1500);</script>";
                        // } else {
                        //              //     echo "<p style='color: red; margin-top: 15px;'>Gagal menyimpan pengaturan: " . mysqli_error($koneksi) . "</p>";
                        // }

                        // Untuk saat ini, kita tampilkan pesan bahwa ini berhasil diproses (bukan simulasi JS lagi)
                        // Jika Anda belum punya tabel 'pengaturan', ini akan menampilkan pesan sukses palsu,
                        // tetapi setidaknya bukan alert dari JavaScript lagi.
                        echo "<p style='color: green; margin-top: 15px;'>Pengaturan berhasil diproses! (Perlu tabel database 'pengaturan' untuk penyimpanan permanen)</p>";
                        echo "<script>setTimeout(function(){ window.location.href = 'index.php?section=settings'; }, 1500);</script>";
                    }
                ?>
            </section>
        </div>
    </div>

    <script src="script.js"></script>
    <script>
        // JS untuk menjaga sidebar tetap collapsed (jika diinginkan) atau terbuka
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');

            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
                // Atur ulang event listener untuk toggle jika ada
                if (sidebarToggle) {
                    sidebarToggle.addEventListener('click', function() {
                        sidebar.classList.toggle('collapsed');
                    });
                }
            } else {
                sidebar.classList.remove('collapsed');
            }
        });
    </script>
</body>
</html>
<?php
    // Tutup koneksi database setelah semua operasi selesai
    mysqli_close($koneksi);
?>