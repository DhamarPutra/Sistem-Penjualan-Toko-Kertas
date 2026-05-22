<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['lunasi'])) {
    $invoices_to_lunasi = $_POST["invoices_to_lunasi"];

    foreach ($invoices_to_lunasi as $nomorNota) {
        $update_status_sql = "UPDATE penjualan SET status_pembayaran = 'Ter-Lunasi' WHERE nomor_nota = $nomorNota";
        if ($conn->query($update_status_sql)) {
            header('Location: index.php?go=invoice');
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fujiwara</title>
    <link rel="shortcut icon" href="icon.svg">
    <link rel="stylesheet" href="css/db.css">
</head>

<body>
    <h1><strong>Invoice</strong></h1>

    <form method="post" action="">
        <input type="text" name="keyword" placeholder="Masukkan Nomor Nota">
        <button type="submit">Cari</button>
        <?php
        echo '<table>
        <tr>
        <th width="10%">No.Nota</th>
        <th width="10%">Nama</th>
        <th width="20%">Nama Barang</th>
        <th width="5%">Qty</th>
        <th width="10%">Total</th>
        <th width="1%"><img src="./img/ceklist.svg"></th>
        </tr>
        <tr>
        <span></span>
        </tr>';
        if (isset($_POST['keyword'])) {
            $keyword = $_POST['keyword'];

            $query = "SELECT * FROM penjualan JOIN db_barang ON penjualan.item_id = db_barang.kode_barang WHERE penjualan.nomor_nota LIKE '%$keyword%'";
        } else {
            $query = "SELECT penjualan.id, penjualan.tanggal_nota, penjualan.nomor_nota, penjualan.nama, penjualan.item_id, db_barang.nama_barang, penjualan.quantity, penjualan.total_price, penjualan.status_pembayaran FROM penjualan JOIN db_barang ON penjualan.item_id = db_barang.kode_barang WHERE penjualan.status_pembayaran = 'Belum' ORDER BY penjualan.id ASC, penjualan.item_id ASC";
        }

        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>
                <td align="center">' . $row["nomor_nota"] . '</td>
                <td align="center">' . substr($row["nama"], 0, 10) . '</td>
                <td align="center">' . $row["nama_barang"] . '</td>
                <td align="center">' . $row["quantity"] . '</td>
                <td align="center">' . $row["total_price"] . '</td>
                <td align="center">
                    <input type="checkbox" name="invoices_to_lunasi[]" value="' . $row["nomor_nota"] . '">
                </td>
                </tr>';
            }
        }

        echo '</table>';
        echo '<br><button type="submit" name="lunasi">Lunasi Pembayaran</button>';
        ?>
    </form>
</body>

</html>