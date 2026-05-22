<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $userType = $_POST['userType'];
    $payment_expiry = '';
    if ($userType === 'monthly') {
        $payment_expiry = date('Y-m-d 00:00:00', strtotime('+1 month'));
    } elseif ($userType === 'yearly') {
        $payment_expiry = date('Y-m-d 00:00:00', strtotime('+1 year'));
    } elseif ($userType === 'weekly') {
        $payment_expiry = date('Y-m-d 00:00:00', strtotime('+1 week'));
    }
    $payment_status = "paid";

    $query = "UPDATE users SET payment_expiry = ?, payment_status = ? WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sss", $payment_expiry, $payment_status, $username);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        } else {
            echo "Error: " . mysqli_stmt_error($stmt);
        }
    } else {
        echo "Error: " . mysqli_error($conn);
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
    <h1>Update Payment</h1>
    <form action="" method="POST" class="card">
        <label for="username">Username: </label>
        <select id="username" name="username">
            <option hidden>Pilih Username</option>
        <?php
        $query = "SELECT username FROM users";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option>" . $row['username'] . "</option>";
            }
        }
        mysqli_close($conn);
        ?>
        </select><br><br>
        <label for="userType">Type Account:</label>
        <select id="userType" name="userType">
            <option value="0" selected hidden>Pilih Durasi Akun</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
        </select><br><br>

        <input type="submit" value="Update Pembayaran" class="regis">
    </form>
</body>

</html>