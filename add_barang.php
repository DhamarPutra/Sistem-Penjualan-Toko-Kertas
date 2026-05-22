<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_barang = $_POST['kode'];
    $nama_barang = $_POST['nama'];
    $harga_beli = $_POST['beli'];
    $harga_jual = $_POST['jual'];
    $stock = 0;

    $sql = "INSERT INTO db_barang (kode_barang, nama_barang, harga_beli, harga_jual, stock) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssss", $kode_barang, $nama_barang, $harga_jual, $harga_beli, $stock);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Barang</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <form action="" method="post" class="card">
        <h1>Tambah Barang</h1>
        <label for="kode">Kode Barang: </label><br>
        <input type="text" name="kode" id="kode" required><br><br>
        <label for="nama">Nama Barang: </label><br>
        <input type="text" name="nama" id="nama" required><br><br>
        <label for="beli">Harga Beli: </label><br>
        <input type="text" name="beli" id="beli" required><br><br>
        <label for="jual">Harga Jual: </label><br>
        <input type="text" name="jual" id="jual" required><br><br>
        <input type="submit" name="add" value="Simpan" class="regis">
    </form>
</body>
</html>