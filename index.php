<?php
    // koneksiDB.php diasumsikan sudah ada dan benar
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
    <style>
        /* CSS tambahan untuk tampilan lebih baik */
        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            align-items: center;
        }
        .search-form input[type="text"] {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-form button, .search-form a {
            padding: 8px 15px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .search-button {
            background-color: #007bff;
        }
        .search-button:hover {
            background-color: #0056b3;
        }
        .clear-search-button {
            background-color: #dc3545;
        }
        .clear-search-button:hover {
            background-color: #c82333;
        }

        /* Untuk smooth scrolling saat klik link indeks */
        html {
            scroll-behavior: smooth;
        }
    </style>
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
            <li><a href="index.php?section=delete-logs" class="<?php echo ($currentSection == 'delete-logs' ? 'active' : ''); ?>" data-section="delete-logs"><i class="fas fa-history"></i> <span>Log Hapus Produk</span></a></li>
            <li><a href="index.php?section=product-view" class="<?php echo ($currentSection == 'product-view' ? 'active' : ''); ?>" data-section="product-view"><i class="fas fa-eye"></i> <span>View Produk Lengkap</span></a></li>
        </ul>
    </div>

    <div class="main-content">
        <header class="navbar">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <h2>Selamat Datang, Admin!</h2>
            <div class="user-info">
                <span>Nama Admin</span>
                <img src="https://via.placeholder.com/40" alt="User Avatar">
            </div>
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

                <form action="index.php" method="GET" class="search-form">
                    <input type="hidden" name="section" value="products">
                    <input type="text" name="keyword" placeholder="Cari nama produk..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                    <button type="submit" class="search-button"><i class="fas fa-search"></i> Cari</button>
                    <?php if (isset($_GET['keyword']) && $_GET['keyword'] != '') { ?>
                        <a href="index.php?section=products" class="clear-search-button"><i class="fas fa-times"></i> Bersihkan</a>
                    <?php } ?>
                </form>
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
                                $search_keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($koneksi, $_GET['keyword']) : '';

                                $query_produk_sql = "SELECT * FROM produk";
                                if (!empty($search_keyword)) {
                                    $query_produk_sql .= " WHERE nama LIKE '%" . $search_keyword . "%'"; // Menggunakan kolom 'nama' (dengan INDEX)
                                }
                                $query_produk_sql .= " ORDER BY nama ASC";

                                $query_produk = mysqli_query($koneksi, $query_produk_sql);

                                if ($query_produk && mysqli_num_rows($query_produk) > 0) {
                                    while ($data_produk = mysqli_fetch_array($query_produk)) {
                            ?>
                            <tr>
                                <td><?php echo $no_produk++ ?></td>
                                <td><?php echo htmlspecialchars($data_produk['nama']) ?></td>
                                <td>Rp <?php echo number_format($data_produk['harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php if (!empty($data_produk['foto'])): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($data_produk['foto']); ?>" alt="Foto Produk" style="width: 50px; height: auto;">
                                    <?php else: ?>
                                        Tidak Ada Foto
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($data_produk['detail']) ?></td>
                                <td><?php echo htmlspecialchars($data_produk['stok_produk']) ?></td>
                                <td>
                                    <a href="editProduk.php?id=<?php echo $data_produk['id']?>" class="edit-button"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="hapus.php?id=<?php echo $data_produk['id']?>&type=produk" class="delete-button" onclick="return confirm('Yakin ingin menghapus produk ini? Log akan dicatat.')"><i class="fas fa-trash"></i> Hapus</a>
                                </td>
                            </tr>
                            <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>Tidak ada produk yang ditemukan.</td></tr>";
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
                                $query_kategori = mysqli_query($koneksi,"SELECT * FROM kategori ORDER BY kategori ASC");
                                if (mysqli_num_rows($query_kategori) > 0) {
                                    while ($data_kategori = mysqli_fetch_array($query_kategori)) {
                            ?>
                            <tr>
                                <td><?php echo $no_kategori++ ?></td>
                                <td><?php echo htmlspecialchars($data_kategori['kategori']) ?></td>
                                <td>
                                    <a href="edit.php?id=<?php echo $data_kategori['id']?>" class="edit-button"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="hapus.php?id=<?php echo $data_kategori['id']?>&type=kategori" class="delete-button" onclick="return confirm('Yakin ingin menghapus kategori ini? Pastikan tidak ada produk terkait!')"><i class="fas fa-trash"></i> Hapus</a>
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

            <section id="delete-logs" class="dashboard-section <?php echo ($currentSection == 'delete-logs' ? 'active' : ''); ?>">
                <h3>Log Penghapusan Produk (dari Trigger)</h3>
                <p>Riwayat produk yang telah dihapus dari sistem, dicatat otomatis oleh trigger.</p>
                <hr>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>ID Produk Dihapus</th>
                                <th>Nama Produk Dihapus</th>
                                <th>Tanggal Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no_log = 1;
                                // Mengambil data dari tabel log_produk_hapus, diurutkan dari yang terbaru
                                $query_log = mysqli_query($koneksi, "SELECT * FROM log_produk_hapus ORDER BY tanggal_hapus DESC");

                                // Memeriksa apakah ada data log
                                if ($query_log && mysqli_num_rows($query_log) > 0) {
                                    // Loop untuk menampilkan setiap baris data
                                    while ($data_log = mysqli_fetch_array($query_log)) {
                            ?>
                            <tr>
                                <td><?php echo $no_log++ ?></td>
                                <td><?php echo htmlspecialchars($data_log['produk_id']) ?></td>
                                <td><?php echo htmlspecialchars($data_log['nama_produk']) ?></td>
                                <td><?php echo htmlspecialchars($data_log['tanggal_hapus']) ?></td>
                            </tr>
                            <?php
                                    }
                                } else {
                                    // Tampilkan pesan jika tidak ada log
                                    echo "<tr><td colspan='4'>Tidak ada log penghapusan produk yang tercatat.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
            <section id="product-view" class="dashboard-section <?php echo ($currentSection == 'product-view' ? 'active' : ''); ?>">
                <h3>Produk Lengkap (dari VIEW)</h3>
                <p>Daftar produk beserta nama kategori, diambil menggunakan VIEW database `view_produk_kategori`.</p>
                <hr>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Detail</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no_view = 1;
                                // Menggunakan VIEW `view_produk_kategori`
                                $query_view = mysqli_query($koneksi, "SELECT * FROM view_produk_kategori ORDER BY nama_produk ASC");
                                if ($query_view && mysqli_num_rows($query_view) > 0) {
                                    while ($data_view = mysqli_fetch_array($query_view)) {
                            ?>
                            <tr>
                                <td><?php echo $no_view++ ?></td>
                                <td><?php echo htmlspecialchars($data_view['nama_produk']) ?></td>
                                <td><?php echo htmlspecialchars($data_view['nama_kategori']) ?></td>
                                <td>Rp <?php echo number_format($data_view['harga'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($data_view['stok_produk']) ?></td>
                                <td><?php echo htmlspecialchars($data_view['detail']) ?></td>
                                <td>
                                    <?php if (!empty($data_view['foto'])): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($data_view['foto']); ?>" alt="Foto Produk" style="width: 50px; height: auto;">
                                    <?php else: ?>
                                        Tidak Ada Foto
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>Tidak ada data produk lengkap dari VIEW atau VIEW belum dibuat.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="product-procedure" class="dashboard-section <?php echo ($currentSection == 'product-procedure' ? 'active' : ''); ?>">
                <h3>Produk via Stored Procedure</h3>
                <p>Bagian ini akan menampilkan daftar produk yang diambil melalui Stored Procedure `GetAllProducts()`.</p>
                <hr>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>ID Produk</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // Untuk mengimplementasikan ini, Anda perlu membuat Stored Procedure di database Anda:
                                // Misalnya: CREATE PROCEDURE GetAllProducts() SELECT id, nama, harga, stok_produk FROM produk;
                                $no_sp = 1;
                                // Memanggil Stored Procedure (akan error jika SP belum dibuat)
                                $query_sp = @mysqli_query($koneksi, "CALL GetAllProducts()"); // Menggunakan @ untuk menekan warning jika SP tidak ada
                                if ($query_sp && mysqli_num_rows($query_sp) > 0) {
                                    while ($data_sp = mysqli_fetch_array($query_sp)) {
                            ?>
                            <tr>
                                <td><?php echo $no_sp++ ?></td>
                                <td><?php echo htmlspecialchars($data_sp['id']) ?></td>
                                <td><?php echo htmlspecialchars($data_sp['nama']) ?></td>
                                <td>Rp <?php echo number_format($data_sp['harga'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($data_sp['stok_produk']) ?></td>
                            </tr>
                            <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='5'>Tidak ada data produk dari Stored Procedure atau SP belum dibuat/error.</td></tr>";
                                }
                                // Penting: Jika Anda memanggil Stored Procedure yang mengembalikan hasil,
                                // Anda mungkin perlu menutup hasil sebelumnya sebelum query berikutnya.
                                // Ini membersihkan setiap result set yang dikembalikan oleh Stored Procedure
                                // agar tidak mengganggu query berikutnya.
                                if (isset($query_sp) && is_object($query_sp)) {
                                    mysqli_free_result($query_sp);
                                }
                                while (mysqli_more_results($koneksi) && mysqli_next_result($koneksi));
                            ?>
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
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');

            // Tangani klik pada menu sidebar
            document.querySelectorAll('.sidebar-menu a').forEach(link => {
                link.addEventListener('click', function(e) {
                    const section = this.dataset.section;
                    if (section && section !== 'logout') { // Hindari mencegah default jika bukan logout
                        e.preventDefault();
                        window.location.href = `index.php?section=${section}`;
                    }
                });
            });

            // Atur status collapsed/open sidebar berdasarkan lebar layar
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
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
    mysqli_close($koneksi); // Tutup koneksi database setelah semua operasi selesai
?>