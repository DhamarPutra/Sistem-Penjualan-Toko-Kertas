<?php
session_start();
require 'konfig.php';
$user_id = $_SESSION['username'];
$query = "SELECT * FROM users WHERE username = '$user_id'";
$result = mysqli_query($conn, $query);

if ($result) {
    $payment = mysqli_fetch_assoc($result);

    if (strtotime($payment['payment_expiry']) < time() && $user_id != 'admin') {
        $updateQuery = "UPDATE users SET payment_status = 'expired' WHERE username = '$user_id'";
        if (mysqli_query($conn, $updateQuery)) {
            header('Location: expired.php');
            exit;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } elseif ((strtotime($payment['payment_expiry']) - time()) < (72 * 60 * 60)) {
        $warning = floor((strtotime($payment['payment_expiry']) - time()) / 86400 + 1);
        $warnMassage = '<div class="warning">!!!Masa Aktif: ' . $warning . ' Hari</div>';
    } else {
        $warnMassage = '';
    }
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM images where tipe = 'pp' AND username = '$user_id'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);

if ($row) {
    $imagePath = $row['image_path'];
}

mysqli_free_result($result);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>MataBiru</title>
    <link rel="shorcut icon" href="img/icon.svg">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/lah.css">
</head>

<body>
    <header>
        <div class="container">
            <div>
                <img src="./img/icon.svg" alt="icon" />
            </div>
            <div class="nama-toko">Fujiwara</div>
        </div>
        <div class="username">
            <img src="<?php echo $imagePath; ?>" alt="PP" class="profile-picture">
            <abbr title="<?php echo '@' . $user_id; ?>">Welcome,<br>@<?php echo substr($user_id, 0, 14); ?></abbr>
            <a href="?go=edit_pp"><img src="./img/edit.svg" class="edit_pp"></a>
        </div>
        <ul class="scroll">
            <?php
            if ($user_id == 'admin') {
                echo '<div>
                <span>Member Menu</span>
            </div>
            <li><a class="menu" href="index.php?go=nota">Nota Penjualan</a></li>
            <li><a class="menu" href="index.php?go=add_stock">Nota Pembelian</a></li>
            <li><a class="menu" href="index.php?go=penjualan">DB Penjualan</a></li>
            <li><a class="menu" href="index.php?go=pembelian">DB Pembelian</a></li>
            <li><a class="menu" href="index.php?go=stock">DB Stock</a></li>
            <li><a class="menu" href="index.php?go=invoice">Invoice</a></li>
            <li><a class="menu" href="index.php?go=piutang">Piutang</a></li>
            <div><br>
                <span>Developer Menu</span>
            </div>
            <li><a class="menu" href="index.php?go=user">User</a></li>
            <li><a class="menu" href="index.php?go=register">Registrasi</a></li>
            <li><a class="menu" href="index.php?go=update">Payment</a> </li>
            <li><a class="menu" href="index.php?go=payment">Bukti Pembayaran</a></li>';
            } elseif ($user_id === 'guest') {
                echo '<link rel="stylesheet" href="css/guest.css">
                <div>
                <span>Member Menu</span>
            </div>
            <li><a class="menu" href="index.php?go=nota">Nota Penjualan</a></li>
            <li><a class="menu" href="index.php?go=add_stock">Nota Pembelian</a></li>
            <li><a class="menu" href="index.php?go=penjualan">DB Penjualan</a></li>
            <li><a class="menu" href="index.php?go=pembelian">DB Pembelian</a></li>
            <li><a class="menu" href="index.php?go=stock">DB Stock</a></li>
            <li><a class="menu" href="index.php?go=invoice">Invoice</a></li>
            <li><a class="menu" href="index.php?go=piutang">Piutang</a></li>';
            } else {
                echo '<div>
                <span>Member Menu</span>
            </div>
            <li><a class="menu" href="index.php?go=nota">Nota Penjualan</a></li>
            <li><a class="menu" href="index.php?go=add_stock">Nota Pembelian</a></li>
            <li><a class="menu" href="index.php?go=penjualan">DB Penjualan</a></li>
            <li><a class="menu" href="index.php?go=pembelian">DB Pembelian</a></li>
            <li><a class="menu" href="index.php?go=stock">DB Stock</a></li>
            <li><a class="menu" href="index.php?go=invoice">Invoice</a></li>
            <li><a class="menu" href="index.php?go=piutang">Piutang</a></li>';
            }
            ?>
        </ul>
    </header>
    <main>
        <?php
        include 'halaman.php';
        include 'konfig.php';
        ?>
        <?php echo $warnMassage; ?>
        <form method="post" action="">
            <input type="submit" name="logout" id="logout" value="Logout" class="logout">
        </form>
    </main>
    <footer>
        &copy; 2024 Fujiwara Creative. All Rights Reserved.
    </footer>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var inputs = document.querySelectorAll('input');
        inputs.forEach(function(input) {
            input.setAttribute('autocomplete', 'off');
        });
    });
</script>

</html>