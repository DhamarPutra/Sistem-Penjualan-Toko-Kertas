<?php
session_start();

if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

if (isset($_POST['guest'])) {
    $username = 'guest';
    $password = 'guest';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require 'konfig.php';
    if (isset($_POST['guest'])) {
        $username = 'guest';
        $password = 'guest';
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];
    }

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit;
        } else {
            echo "Password salah.";
        }
    } else {
        echo "Username tidak ditemukan.";
    }

    mysqli_close($conn);
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
    <h2>Login</h2>
    <form action="" method="POST">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" autocomplete="off"><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password"><br>

        <input type="submit" value="Masuk">
        <input type="submit" value="Guest" name="guest">
    </form>
</body>

</html>