<?php
include 'konfig.php';
$user_id = $_SESSION['username'];
$selectedPage = isset($_GET['go']) ? $_GET['go'] : '';
if ($user_id == "admin") {
    switch ($selectedPage) {
        case 'nota':
            include("nota.php");
            break;
        case 'penjualan':
            include("penjualan.php");
            break;
        case 'pembelian':
            include("pembelian.php");
            break;
        case 'add_stock':
            include("input_barang.php");
            break;
        case 'stock':
            include("db_stok.php");
            break;
        case 'invoice':
            include("invoice.php");
            break;
        case 'piutang':
            include("piutang.php");
            break;
        case 'edit_pp':
            include("edit_pp.php");
            break;
        case 'edit_barang':
            include("edit_barang.php");
            break;
        case 'add_barang':
            include("add_barang.php");
            break;
        case 'user':
            include("user.php");
            break;
        case 'register':
            include("register.php");
            break;
        case 'update':
            include("update.php");
            break;
        case 'payment':
            include("payment.php");
            break;
        case 'edit_user':
            include("edit_user.php");
            break;
        default:
            include("nota.php");
            break;
    }
} else {
    switch ($selectedPage) {
        case 'nota':
            include("nota.php");
            break;
        case 'penjualan':
            include("penjualan.php");
            break;
        case 'pembelian':
            include("pembelian.php");
            break;
        case 'add_stock':
            include("input_barang.php");
            break;
        case 'stock':
            include("db_stok.php");
            break;
        case 'invoice':
            include("invoice.php");
            break;
        case 'piutang':
            include("piutang.php");
            break;
        case 'edit_pp':
            include("edit_pp.php");
            break;
        case 'edit_barang':
            include("edit_barang.php");
            break;
        case 'add_barang':
            include("add_barang.php");
            break;
        default:
            include("nota.php");
            break;
    }
}
