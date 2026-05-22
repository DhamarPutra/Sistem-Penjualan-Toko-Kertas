<?php
$sql = "SELECT nama_barang, SUM(stock) as total_stock, kode_barang, id, harga_beli, harga_jual FROM db_barang GROUP BY nama_barang, kode_barang, id ORDER BY id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Fujiwara</title>
    <link rel="shortcut icon" href="icon.svg">
    <link rel="stylesheet" href="css/db.css">
</head>

<body>
    <h1><strong>Data Stock</strong></h1>
<form method="post">
    <?php
    if (isset($_POST['submit'])) {
        foreach ($_POST['checkbox'] as $id) {
            $query = "DELETE FROM db_barang WHERE id = $id";
            mysqli_query($conn, $query);
            header('Location: index.php?go=stock');
        }
    }
    if ($result->num_rows > 0) {
        $totalStock = 0;

        echo '<table>
        <tr>
        <th width="1%">No</th>
        <th width="10%">Kode</th>
        <th width="20%">Nama Barang</th>
        <th width="15%">Harga Beli</th>
        <th width="15%">Harga Jual</th>
        <th width="1%">Stock</th>
        <th width="1%">Edit</th>
        <th width="1%"><img src="./img/ceklist.svg" alt=""></th>
        </tr>
        <tr>
        <span></span>
        </tr>';

        while (($row = $result->fetch_assoc())) {
            echo '<tr>
            <td align="center">'. $row['id'] .'</td>
            <td align="center">'. $row['kode_barang'] .'</td>
            <td align="center">' . $row['nama_barang'] . '</td>
            <td align="center">'. $row['harga_beli'] .'</td>
            <td align="center">'. $row['harga_jual'] .'</td>
            <td align="center">' . $row['total_stock'] . '</td>
            <td align="center"><a href="index.php?go=edit_barang&id='. $row['id'] .'"><img src="./img/edit.svg" class="edit_pp"></a></td>
            <td><input type="checkbox" name="checkbox[]" value="' . $row['id'] . '"></td>
            </tr>';

            $totalStock += $row['total_stock'];
        }
        echo '</table><br>';
    } else {
        echo 'No products available.';
    }
    $conn->close();
    ?>
    <input type="submit" name="submit" value="Delete Barang" class="regis">
</form><br>
<button class="convbutton" onclick="window.location.href='index.php?go=add_barang'">Tambah Barang</button>
</body>

</html>