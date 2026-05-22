<?php
require 'konfig.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['konfir'])) {
    // $token = "6644297167:AAFofZoGkUm2sT-_ogJIeMMILyVNlBmllvk";
    // $chat_id = "6043208642";
    $username = $_POST["username"];
    $imageType= 'bukti';

    $file_name = $_FILES['file']['name'];
    $file_temp = $_FILES['file']['tmp_name'];

    $upload_directory = "upload/pembayaran/";
    $file_path = $upload_directory . $file_name;

    if (move_uploaded_file($file_temp, $file_path)) {
        $date = date('Y-m-d H:i:s');

        if ($response !== false) {
            $insertQuery = "INSERT INTO images (username, image_path, payment_date, tipe) VALUES ('$username', '$file_path', '$date', '$imageType')";
            
            if (mysqli_query($conn, $insertQuery)) {
                header('Location: Login.php');
                // $message = "*Konfirmasi Pembayaran*%0A*====================================*%0AHalo, saya $username ingin konfirmasi bahwa saya telah melakukan pembayaran.";
                // $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=$message&parse_mode=markdown";
                // $response = file_get_contents($url);
                exit;
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "Pesan tidak terkirim.";
        }
    } else {
        echo "Gagal mengunggah file.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body align="center">
    <h1>Segera Lunasi Pembayaran</h1>
    <div>
        <p>Sudah Melunasi Pembayaran? <a href="login.php">Login</a></a></p>
        <form method="post" enctype="multipart/form-data">
            <label for="username">Username</label><br>
            <input type="text" placeholder="Username" name="username" required><br>
            
            <label for="file">Bukti Pembayaran:</label><br>
            <input type="file" name="file" required><br>
            
            <br><input type="submit" value="Konfirmasi Pembayaran" name="konfir">
        </form>
    </div>
</body>
</html>
