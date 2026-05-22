<?php
date_default_timezone_set('Asia/Jakarta');

if (isset($_POST['calculate'])) {
    $atLeastOneItem = false;

    for ($i = 1; $i <= 10; $i++) {

        $itemKey = 'item' . $i;
        $quantityKey = 'qty' . $i;

        $kode = $_POST[$itemKey];
        $quantity = $_POST[$quantityKey];

        $query = "SELECT kode_barang, nama_barang, harga_beli FROM db_barang";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $namaBarang = $row['nama_barang'];
            $hargaBarang = $row['harga_beli'];
            $totalHarga = $hargaBarang * (int)$quantity;
        }

        if ($kode && $quantity) {
            $atLeastOneItem = true;

            $sql = "SELECT stock FROM db_barang WHERE kode_barang = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $kode);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result !== FALSE && $result->num_rows > 0) {
                $supplier = $_POST['supplier'];
                $timestamp = time();
                $nomorNota = date("ymdHis", $timestamp);
                $tanggalNota = date("Y/m/d", $timestamp);
                $row = $result->fetch_assoc();
                $currentStock = $row['stock'];

                $newStock = $currentStock + $quantity;

                $updateSql = "UPDATE db_barang SET stock = ? WHERE kode_barang = ?";
                $stmtUpdate = $conn->prepare($updateSql);
                $stmtUpdate->bind_param("is", $newStock, $kode);
                $stmtUpdate->execute();

                $sqlBeli = "INSERT INTO pembelian (tanggal_nota, nomor_nota, supplier, item_id, name, quantity, price, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmtBeli = $conn->prepare($sqlBeli);
                $stmtBeli->bind_param("ssssssss", $tanggalNota, $nomorNota, $supplier, $kode, $namaBarang, $quantity, $hargaBarang, $totalHarga);
                $stmtBeli->execute();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Fujiwara</title>
    <link rel="shorcut icon" href="icon.svg">
    <link rel="stylesheet" href="css/nota.css">
</head>

<body>
    <form method="post" action="">
        <table width="100%">
            <td colspan="6">
                Supplier,
                <select id="supplier" name="supplier" class="top">
                    <option selected hidden>Pilih Supplier</option>
                    <?php
                    $query = "SELECT nama_supplier FROM db_supplier ORDER BY nama_supplier";
                    $result = mysqli_query($conn, $query);

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option>" . $row['nama_supplier'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </td>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Kode</th>
                <th width="40%">Nama Barang</th>
                <th width="6%">Qty</th>
                <th width="17%">Harga</th>
                <th width="17%">Total</th>
            </tr>

            <?php
            for ($i = 1; $i <= 10; $i++) {
                echo "<tr>";
                echo "<td>$i</td>";
                echo '<td><span id="kode_' . $i . '"></span></td>';
                echo '<td>';
                echo '<select name="item' . $i . '" id="item' . $i . '" class="select" onchange="updateHarga(' . $i . ')">';
                echo '<option value="0" selected hidden>Pilih Bahan</option>';
                $query = "SELECT kode_barang, nama_barang, harga_beli FROM db_barang";
                $result = mysqli_query($conn, $query);
                $barangData = [];

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['kode_barang'] . "'>" . $row['nama_barang'] . "</option>";
                        $barangData[$row['kode_barang']] = [
                            'nama_barang' => $row['nama_barang'],
                            'harga_beli' => $row['harga_beli']
                        ];
                    }
                }

                $barangDataJSON = json_encode($barangData);
                echo '</select>';
                echo '</td>';
                echo '<td><input type="number" class="qty" name="qty' . $i . '" id="qty' . $i . '" placeholder="0" min="1" onchange="hitungTotal(' . $i . ')"></td>';
                echo '<td><span>Rp</span><span name="harga' . $i . '" id="harga' . $i . '"></span></td>';
                echo '<td><span>Rp</span><span name="total' . $i . '" id="total' . $i . '"></span></td>';
                echo "</tr>";
            }
            ?>
            <tr>
                <th colspan="4" class="total">Total : </th>
                <th colspan="2" class="total"><span>Rp</span><span id="totalAll"></span></th>
            </tr>
            <tr>
                <th colspan="4" class="total">Uang Muka/DP : </th>
                <th colspan="2" class="total"><input type="number" onchange="sisa()" id="dp" class="dp"></th>
            </tr>
            <tr>
                <th colspan="4" class="total">Total : </th>
                <th colspan="2" class="total"><span>Rp</span></span><span id="sisa"></span></th>
            </tr>
        </table>
        <input type="submit" name="calculate" class="convbutton" value="Simpan&#10Pembelian">
    </form>
    <script>
        const barangData = <?php echo $barangDataJSON; ?>;

        function updateHarga(row) {
            const selected = document.getElementById('item' + row);
            const harga = barangData[selected.value].harga_beli;

            document.getElementById('kode_' + row).innerText = selected.value;
            document.getElementById('harga' + row).innerText = formatToRupiah(harga);

            hitungTotal(row);
            totalAll();
            sisa();
        }

        function hitungTotal(row) {
            const qty = document.getElementById('qty' + row).value;
            const harga = document.getElementById('harga' + row).innerText;

            const parsedHarga = parseInt(harga.replace(/\./g, ''));
            const total = qty * parsedHarga;

            const formattedTotal = formatToRupiah(total);
            document.getElementById('total' + row).innerText = formattedTotal;
            totalAll();
            sisa();
        }

        function formatToRupiah(angka) {
            const formatter = new Intl.NumberFormat('id-ID');
            return formatter.format(angka);
        }

        function totalAll() {
            let totalAll = 0;

            for (let i = 1; i <= 10; i++) {
                let currentTotal = parseFloat(document.getElementById('total' + i).innerText.replace(/\./g, '').replace(',', '.')) || 0;
                totalAll += currentTotal;
            }

            document.getElementById('totalAll').innerText = formatToRupiah(totalAll);
        }

        function sisa() {
            let totalAll = parseFloat(document.getElementById('totalAll').innerText.replace(/\./g, '').replace(',', '.')) || 0;
            let dp = parseFloat(document.getElementById('dp').value.replace(/\./g, '').replace(',', '.')) || 0;

            let sisa = totalAll - dp;

            document.getElementById('sisa').innerText = formatToRupiah(sisa);
        }
    </script>
</body>

</html>