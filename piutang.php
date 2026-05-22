<?php
$sql = "SELECT penjualan.id, penjualan.tanggal_nota, penjualan.nomor_nota, penjualan.nama, penjualan.item_id, db_barang.nama_barang, penjualan.quantity, penjualan.total_price FROM penjualan JOIN db_barang ON penjualan.item_id = db_barang.kode_barang WHERE penjualan.status_pembayaran = 'Belum' OR penjualan.status_pembayaran = 'Ter-Lunasi' ORDER BY penjualan.id ASC, penjualan.item_id ASC";
$result = $conn->query($sql);
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
    <h1><strong>Data Piutang</strong></h1>

    <form method="post" action="">
    <?php
    if ($result->num_rows > 0) {
        echo '<table>
        <tr>
        <th width="10%">No.Nota</th>
        <th width="10%">Nama</th>
        <th width="20%">Nama Barang</th>
        <th width="5%">Qty</th>
        <th width="10%">Total</th>
        </tr>
        <tr>
        <span></span>
        </tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>
            <td align="center">'. $row["nomor_nota"] .'</td>
            <td align="center">'. substr($row["nama"], 0, 10) .'</td>
            <td align="center">'. $row["nama_barang"] .'</td>
            <td align="center">'. $row["quantity"] .'</td>
            <td align="center">'. $row["total_price"] .'</td>
            </tr>';
        }

        echo '</table>';
    }
    ?>
    </form>
</body>
</html>
