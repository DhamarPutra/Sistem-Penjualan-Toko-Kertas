<?php
function returnStock($item_id, $quantity)
{
    global $conn;

    $sql = "UPDATE db_barang SET stock = stock - ? WHERE kode_barang = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $quantity, $item_id);

    if ($stmt->execute()) {
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $invoices_to_delete = $_POST["invoices_to_delete"];
    foreach ($invoices_to_delete as $invoice_id) {
        $isStockReturned = false;
        if (!$isStockReturned) {
            $invoice_info_sql = "SELECT id, item_id, quantity FROM pembelian WHERE id = ?";
            $stmt = $conn->prepare($invoice_info_sql);
            $stmt->bind_param("s", $invoice_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $invoice_info = $result->fetch_assoc();
                $id = $invoice_info['id'];
                $item_id = $invoice_info['item_id'];
                $quantity = $invoice_info['quantity'];

                returnStock($item_id, $quantity);

                $delete_sql = "DELETE FROM pembelian WHERE id = ?";
                $stmt = $conn->prepare($delete_sql);
                $stmt->bind_param("s", $invoice_id);

                if ($stmt->execute()) {
                    $isStockReturned = true;
                }
            }
        }
    }
}


$sql = "SELECT pembelian.id, pembelian.tanggal_nota, pembelian.nomor_nota, pembelian.supplier, pembelian.item_id, db_barang.nama_barang, pembelian.quantity, pembelian.total_price FROM pembelian JOIN db_barang ON pembelian.item_id = db_barang.kode_barang ORDER BY pembelian.id ASC, pembelian.item_id ASC";
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
    <h1><strong>Data Pembelian</strong></h1>

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
        <th width="1%"><img src="./img/edit.svg"></th>
        </tr>
        <tr>
        <span></span>
        </tr>';

            while ($row = $result->fetch_assoc()) {
                echo '<tr>
            <td align="center">' . $row["nomor_nota"] . '</td>
            <td align="center">' . substr($row["supplier"], 0, 10) . '</td>
            <td align="center">' . $row["nama_barang"] . '</td>
            <td align="center">' . $row["quantity"] . '</td>
            <td align="center">' . $row["total_price"] . '</td>
            <td align="center">
                <input type="checkbox" name="invoices_to_delete[]" value="' . $row["id"] . '">
            </td>
            </tr>';
            }

            echo '</table>';
            echo '<br><button type="submit" name="delete">Delete Selected</button>';
        }
        ?>
    </form>
</body>

</html>