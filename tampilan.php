<?php
    include("koneksiDB.php");
?>
<h2>Daftar Kategori</h2>
<a href="tambahKategori.php">Tambah Kategori</a>
<hr>
<table border="1">
    <tr>
        <th>No.</th>
        <th>Kategori</th>
        <th>Aksi</th>
    </tr>

    <?php
        $no = 1;
        $query = mysqli_query($koneksi,"SELECT * FROM kategori");
        while ($data = mysqli_fetch_array($query)) {
    ?>

    <tr>
        <td><?php echo $no++ ?></td>
        <td><?php echo $data['kategori'] ?></td>
        <td>
            <a href="edit.php?id=<?php echo $data['id']?>">Edit</a> |
            <a href="hapus.php?id=<?php echo $data['id']?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
        </td>
    </tr>
    <?php } ?>
</table>

<h2>Daftar Produk</h2>
<a href="tambahProduk.php">Tambah Produk</a>
<hr>
<table border="1">
    <tr>
        <th>No.</th>
        <th>Nama Produk</th>
        <th>Harga</th>
        <th>Foto Produk</th>
        <th>Detail Produk</th>
        <th>Stok</th>
        <th>Aksi</th>
    </tr>

    <?php
        $no = 1;
        $query = mysqli_query($koneksi,"SELECT * FROM produk");
        while ($data = mysqli_fetch_array($query)) {
    ?>

    <tr>
        <td><?php echo $no++ ?></td>
        <td><?php echo $data['nama_produk'] ?></td>
        <td><?php echo $data['harga'] ?></td>
        <td><?php echo $data['foto'] ?></td>
        <td><?php echo $data['detail'] ?></td>
        <td><?php echo $data['stok_produk'] ?></td>
        <td>
            <a href="edit.php?id=<?php echo $data['id']?>">Edit</a> |
            <a href="hapus.php?id=<?php echo $data['id']?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
        </td>
    </tr>
    <?php } ?>
</table>

