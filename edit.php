<?php
    include("koneksiDB.php");

    $id = isset($_GET["id"]) ? mysqli_real_escape_string($koneksi, $_GET["id"]) : null;

    if (!$id) {
        header("Location: index.php?section=categories");
        exit();
    }

    $query = mysqli_query($koneksi,"SELECT * FROM kategori WHERE ID = '$id' ");
    $data = mysqli_fetch_array($query);

    if (!$data) {
        header("Location: index.php?section=categories");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori - Dashboard Admin</title>
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
            <section id="edit-category-form" class="dashboard-section active">
                <h3>Edit Kategori: <?php echo htmlspecialchars($data['kategori']); ?></h3>
                <form action="" method="post" class="settings-form">
                    <div class="form-group">
                        <label for="kategori_nama">Kategori:</label>
                        <input type="text" id="kategori_nama" name="kategori_nama" value="<?php echo htmlspecialchars($data['kategori'])?>" required>
                    </div>

                    <button type="submit" name="update_kategori" class="save-button"><i class="fas fa-save"></i> Update Data</button>
                    <a href="index.php?section=categories" class="view-button"><i class="fas fa-arrow-left"></i> Kembali</a>
                </form>

                <?php
                    if (isset($_POST['update_kategori'])) {
                        // Perbaikan: Ambil dari input yang benar
                        $kategori_baru = mysqli_real_escape_string($koneksi, $_POST['kategori_nama']);

                        $query_update = "UPDATE kategori SET kategori = '$kategori_baru' WHERE ID = '$id' ";

                        if(mysqli_query($koneksi, $query_update)) {
                            echo "<p style='color: green; margin-top: 15px;'>Kategori berhasil diupdate!</p>";
                            echo "<script>setTimeout(function(){ window.location.href = 'index.php?section=categories'; }, 1500);</script>";
                        } else {
                            echo "<p style='color: red; margin-top: 15px;'>Gagal update data: " . mysqli_error($koneksi) . "</p>";
                        }
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