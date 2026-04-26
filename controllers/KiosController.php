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

function handleUpload() {
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/img/Kios/"; 
        
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_name = $_FILES["image"]["name"];
        $file_tmp = $_FILES["image"]["tmp_name"];
        $file_size = $_FILES["image"]["size"];
        
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        if (!in_array($file_extension, $allowed_extensions)) {
            echo "<script>alert('GAGAL: Format file tidak didukung! Hanya boleh JPG, JPEG, PNG, atau WEBP.'); window.location.href='../views/admin/kios.php';</script>";
            exit;
        }

        if ($file_size > 1048576) {
            echo "<script>alert('GAGAL: Ukuran gambar terlalu besar! Maksimal upload adalah 1 MB.'); window.location.href='../views/admin/kios.php';</script>";
            exit;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file_tmp);
        finfo_close($finfo);
        
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($mime_type, $allowed_mimes)) {
            echo "<script>alert('GAGAL: File terdeteksi palsu / bukan gambar asli!'); window.location.href='../views/admin/kios.php';</script>";
            exit;
        }

        $new_file_name = time() . "_" . uniqid() . "." . $file_extension;
        $target_file = $target_dir . $new_file_name;

        if (move_uploaded_file($file_tmp, $target_file)) {
            return $target_file; 
        }
    }
    return false;
}

if (isset($_POST['add']) || isset($_POST['tambah'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $business_type = mysqli_real_escape_string($conn, $_POST['business_type']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $contact_phone = mysqli_real_escape_string($conn, $_POST['contact_phone']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    $image = '';
    $uploaded = handleUpload();
    if ($uploaded) {
        $image = mysqli_real_escape_string($conn, $uploaded);
    }

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
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    $image = mysqli_real_escape_string($conn, $_POST['old_image'] ?? '');
    
    $uploaded = handleUpload();
    if ($uploaded) {
        $image = mysqli_real_escape_string($conn, $uploaded);
    }

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