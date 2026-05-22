<?php
$user_id = $_SESSION['username'];

function updatePP($file_path, $user_id, $conn)
{
    $sql = "UPDATE images SET image_path = ? WHERE username = ? AND tipe = 'pp'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $file_path, $user_id);
    $stmt->execute();
}

function deleteOldPP($user_id, $conn)
{
    $pp_sql = "SELECT image_path FROM images WHERE username = ? AND tipe = 'pp'";
    $stmt = $conn->prepare($pp_sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $old_file_path = $row['image_path'];
        if (file_exists($old_file_path)) {
            unlink($old_file_path);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $pp_sql = "SELECT username, image_path FROM images WHERE username = ? AND tipe = 'pp'";
    $stmt = $conn->prepare($pp_sql);
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $file_name = $_FILES['upload']['name'];
        $file_temp = $_FILES['upload']['tmp_name'];
        if (!empty($file_name)) {
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $new_file_name = $user_id . "." . $file_extension;

            $upload_directory = "upload/profile-picture/";
            $file_path = $upload_directory . $new_file_name;

            deleteOldPP($user_id, $conn);
            if (move_uploaded_file($file_temp, $file_path)) {
                updatePP($file_path, $user_id, $conn);
                header('Location: index.php?go=edit_pp');
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/db.css">
</head>

<body>
    <form action="" method="post" enctype="multipart/form-data">
        <h1>Ubah Foto Profil</h1>
        <div class="preview">
            <img id="preview" class="preview" src="./img/upload-here.svg">
        </div><br>
        <input type="file" id="upload" name="upload" require hidden>
        <button type="submit" name="update">Update Profile Picture</button>
    </form>
</body>
<script>
    document.getElementById('upload').addEventListener('change', function() {
        var file = this.files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    });
    document.querySelector('.preview').addEventListener('click', function() {
        document.getElementById('upload').click();
    });

    document.getElementById('upload').addEventListener('change', function() {
        var file = this.files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
        }
        reader.readAsDataURL(file);
    });
</script>

</html>