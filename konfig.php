<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kasir";

/*
$servername = "sql202.infinityfree.com";
$username = "if0_35345181";
$password = "s4uU2fu4DQdQsBD";
$dbname = "if0_35345181_matabiru";
*/

$conn = new mysqli($servername, $username, $password, $dbname);

date_default_timezone_set('Asia/Jakarta');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>