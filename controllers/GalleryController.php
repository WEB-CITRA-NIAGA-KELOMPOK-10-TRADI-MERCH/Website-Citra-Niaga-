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
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    
    $image = mysqli_real_escape_string($conn, $_POST['image_url'] ?? '');
    
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    mysqli_query($conn, "INSERT INTO gallery (title, category, image, description) VALUES ('$title', '$category', '$image', '$description')");
    header("Location: ../views/admin/gallery.php?success=1");
    exit;
}

if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    
    $image = mysqli_real_escape_string($conn, $_POST['image_url'] ?? '');
    
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    mysqli_query($conn, "UPDATE gallery SET title='$title', category='$category', image='$image', description='$description' WHERE id=$id");
    header("Location: ../views/admin/gallery.php?success=1");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM gallery WHERE id=$id");
    header("Location: ../views/admin/gallery.php?success=1");
    exit;
}
?>