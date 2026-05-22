<?php
$query = "SELECT * FROM images where tipe = 'bukti'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Gambar</title>
    <link rel="stylesheet" href="css/popup.css">
    <script>
        function tampilkanPopup() {
          var popup = document.getElementById('popup');
          popup.style.display = 'flex';
        }
        
        function tutupPopup() {
          var popup = document.getElementById('popup');
          popup.style.display = 'none';
        }
    </script>
</head>
<body>
    <h1>Galeri Pembayaran</h1>
    <table>
    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td>
            <img src="<?php echo $row['image_path']; ?>" width="45" height="80" alt="Gambar" id="gambar" onclick="tampilkanPopup()">
            <div id="popup" class="popup">
                <img src="<?php echo $row['image_path']; ?>" class="popup-gambar" alt="Gambar" onclick="tutupPopup()">
            </div>
            </td>
            <td style="vertical-align: top;">
            <span>Username: @<?php echo $row['username']; ?></span><br>
            <span>Payment Date : <?php echo $row['payment_date']; ?></span><br>
            </td>
        </tr>
    <?php endwhile; ?>
    </table>
</body>
</html>
