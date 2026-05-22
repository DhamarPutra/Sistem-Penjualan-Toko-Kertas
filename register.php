<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $userType = $_POST['userType'];
    $payment_expiry = '';
    if ($userType === 'monthly') {
        $payment_expiry = date('Y-m-d 00:00:00', strtotime('+1 month'));
    } elseif ($userType === 'yearly') {
        $payment_expiry = date('Y-m-d 00:00:00', strtotime('+1 year'));
    } elseif ($userType === 'weekly') {
        $payment_expiry = date('Y-m-d 00:00:00', strtotime('+1 week'));
    } elseif ($userType === 'trial') {
        $payment_expiry = date('Y-m-d 00:00:00', strtotime('+3 day'));
    }
    $payment_status = "paid";
    $imageType = 'pp';

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo 'Username sudah digunakan.';
    } else {
        $query = "INSERT INTO users (username, password, payment_expiry, payment_status) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);

        $file_name = $_FILES['file']['name'];
        $file_temp = $_FILES['file']['tmp_name'];
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_file_name = $username . "." . $file_extension;

        $upload_directory = "upload/profile-picture/";
        $file_path = $upload_directory . $new_file_name;

        if (move_uploaded_file($file_temp, $file_path)) {
            $date = date('Y-m-d H:i:s');
            $insertQuery = "INSERT INTO images (username, image_path, payment_date, tipe) VALUES ('$username', '$file_path', '$date', '$imageType')";

            if (mysqli_query($conn, $insertQuery)) {
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ssss", $username, $password, $payment_expiry, $payment_status);

                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_close($stmt);
                        mysqli_close($conn);
                        echo 'Username berhasil didaftarkan.';
                    } else {
                        echo "Error: " . mysqli_stmt_error($stmt);
                    }
                } else {
                    echo "Error: " . mysqli_error($conn);
                }
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MataBiru</title>
    <link rel="stylesheet" href="css/register.css">
</head>

<body>
    <h1>Registrasi</h1>
    <form action="" method="POST" enctype="multipart/form-data" class="card">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br>

        <label for="file">Profile Picture:</label>
        <input type="file" name="file" required><br><br>

        <label for="userType">Type Account:</label>
        <select id="userType" name="userType">
            <option value="0" selected hidden>Pilih Durasi Akun</option>
            <option value="trial">Trial</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
        </select><br><br>

        <input type="submit" value="Daftarkan" class="regis">
    </form>
</body>

</html>