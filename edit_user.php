<?php
if (isset($_POST['delete'])) {
    $username = mysqli_real_escape_string($conn, $_POST['user']);

    $query = "DELETE FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $imageQuery = "DELETE FROM images WHERE username = ? AND tipe = 'pp'";
        $stmt = $conn->prepare($imageQuery);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        header('Location: index.php?go=user');
        exit;
    }
}

if (isset($_GET['id'])) {
    $id_user = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        $user_baru = $_POST['user'];
        $password_baru = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $query_update = "UPDATE users SET username = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($query_update);
        $stmt->bind_param('sss', $user_baru, $password_baru, $id_user);
        $stmt->execute();

        header('Location: index.php?go=user');
        exit;
    }

    $query_select = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query_select);
    $stmt->bind_param('s', $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo 'User tidak ditemukan.';
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="css/register.css">
</head>

<body>
    <h1>Edit User</h1>
    <form action="" method="POST" class="card">
        <label for="user">Username:</label><br>
        <input type="text" name="user" value="<?php echo $user['username']; ?>"><br><br>

        <label for="password">Password</label><br>
        <input type="text" name="password"><br><br>

        <input type="submit" name="submit" value="Simpan Perubahan" class="regis">
        <input type="submit" name="delete" value="Hapus User" class="regis">
    </form>
</body>

</html>