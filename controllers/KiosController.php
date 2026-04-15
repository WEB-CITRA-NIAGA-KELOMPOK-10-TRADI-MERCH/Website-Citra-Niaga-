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
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $business_type = mysqli_real_escape_string($conn, $_POST['business_type']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $contact_phone = mysqli_real_escape_string($conn, $_POST['contact_phone']);
    $image = mysqli_real_escape_string($conn, $_POST['image'] ?? '');
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    mysqli_query($conn, "INSERT INTO kios (name, business_type, location, contact_phone, image, description) VALUES ('$name', '$business_type', '$location', '$contact_phone', '$image', '$description')");
    header("Location: ../views/admin/kios.php?success=1");
    exit;
}

if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $business_type = mysqli_real_escape_string($conn, $_POST['business_type']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $contact_phone = mysqli_real_escape_string($conn, $_POST['contact_phone']);
    $image = mysqli_real_escape_string($conn, $_POST['image'] ?? '');
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    mysqli_query($conn, "UPDATE kios SET name='$name', business_type='$business_type', location='$location', contact_phone='$contact_phone', image='$image', description='$description' WHERE id=$id");
    header("Location: ../views/admin/kios.php?success=1");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($conn, "DELETE FROM kios WHERE id=$id");
    header("Location: ../views/admin/kios.php?success=1");
    exit;
}
?>