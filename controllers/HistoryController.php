<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/koneksi.php';
/** @var mysqli $conn */

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../views/login.php"); 
    exit;
}

if (isset($_POST['add'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    mysqli_query($conn, "INSERT INTO history (title, year, content) VALUES ('$title', '$year', '$content')");
    header("Location: ../views/admin/history.php?success=1");
    exit;
}

if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $year = mysqli_real_escape_string($conn, $_POST['year']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    mysqli_query($conn, "UPDATE history SET title='$title', year='$year', content='$content' WHERE id=$id");
    header("Location: ../views/admin/history.php?success=1");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM history WHERE id=$id");
    header("Location: ../views/admin/history.php?success=1");
    exit;
}
?>