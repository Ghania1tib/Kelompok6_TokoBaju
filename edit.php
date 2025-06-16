<?php
    include("koneksiDB.php");

    $id = $_GET["id"];
    $query = mysqli_query($koneksi,"SELECT*FROM kategori WHERE ID = '$id' ");
    $data = mysqli_fetch_array($query);
?>

<h2>Update Kategori</h2>
<hr>
<form action="" method="post">
    <table>
        <tr>
            <td>Kategori</td>
            <td>:</td>
            <td><input type="text" name="kategori" value="<?php echo $data['kategori']?>"></td>
        </tr>
        
        <tr>
            <td colspan="3" align="center">
                <input type="submit" name="update" value="Update Data">
            </td>
        </tr>
    </table>
</form>
<?php
    if (isset($_POST['update'])) {
        $kategori = $_POST['kategori'];

        $query = "UPDATE kategori SET kategori = '$kategori'
                  WHERE ID = '$id' ";

        if(mysqli_query($koneksi, $query)) {    
            header("Location: index.php");
        } else {
            echo "Gagal update data!";
    
        }
    }
?>