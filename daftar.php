<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // $token = "6644297167:AAFofZoGkUm2sT-_ogJIeMMILyVNlBmllvk";
    // $chat_id = "6043208642";
    // $message = "*Registrasi User*%0A*====================================*%0AHalo, saya ingin mendaftar dengan:%0AUsername: *" . $username . "*%0APassword: *" . $password . "*";
    // $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=$message&parse_mode=markdown";
    // $response = file_get_contents($url);
    // header("Location: https://t.me/FujiwaraPrinting_bot");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MataBiru</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/lah.css">
</head>

<body>
    <h2>Registrasi</h2>
    <form action="" method="POST">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Daftar">
    </form>
</body>

</html>