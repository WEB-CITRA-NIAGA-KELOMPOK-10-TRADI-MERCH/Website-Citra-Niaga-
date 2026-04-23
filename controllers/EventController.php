<?php
session_start();
require_once '../config/koneksi.php';

/** @var mysqli $conn */

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../views/login.php"); 
    exit;
}

// ==========================================
// TAMBAH EVENT BARU
// ==========================================
if (isset($_POST['tambah_event'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    $image_name = "";

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/img/Events/"; 
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $file_name = $_FILES["image"]["name"];
        $file_tmp = $_FILES["image"]["tmp_name"];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        if (!in_array($file_extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            echo "<script>alert('GAGAL: Format poster harus JPG, PNG, atau WEBP!'); window.location.href='../views/admin/events.php';</script>"; exit;
        }

        $image_name = "event_" . time() . "." . $file_extension;
        move_uploaded_file($file_tmp, $target_dir . $image_name);
    }

    mysqli_query($conn, "INSERT INTO events (title, description, start_date, end_date, image) VALUES ('$title', '$description', '$start_date', '$end_date', '$image_name')");
    header("Location: ../views/admin/events.php?success=add");
    exit;
}

// ==========================================
// EDIT EVENT (FITUR BARU)
// ==========================================
if (isset($_POST['edit_event'])) {
    $id = (int)$_POST['id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $start_date = mysqli_real_escape_string($conn, $_POST['start_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['end_date']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/img/Events/"; 
        $file_name = $_FILES["image"]["name"];
        $file_tmp = $_FILES["image"]["tmp_name"];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        if (!in_array($file_extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            echo "<script>alert('GAGAL: Format poster harus JPG, PNG, atau WEBP!'); window.location.href='../views/admin/events.php';</script>"; exit;
        }

        $new_image = "event_" . time() . "." . $file_extension;
        if(move_uploaded_file($file_tmp, $target_dir . $new_image)) {
            // Hapus gambar lama biar hosting lu ga penuh
            $cek = mysqli_query($conn, "SELECT image FROM events WHERE id = $id");
            if ($r = mysqli_fetch_assoc($cek)) {
                $old_file = $target_dir . $r['image'];
                if (!empty($r['image']) && file_exists($old_file)) { unlink($old_file); }
            }
            mysqli_query($conn, "UPDATE events SET title='$title', description='$description', start_date='$start_date', end_date='$end_date', image='$new_image' WHERE id=$id");
        }
    } else {
        // Kalau ga upload gambar baru, update teksnya aja
        mysqli_query($conn, "UPDATE events SET title='$title', description='$description', start_date='$start_date', end_date='$end_date' WHERE id=$id");
    }
    
    header("Location: ../views/admin/events.php?success=edit");
    exit;
}

// ==========================================
// HAPUS EVENT
// ==========================================
if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $cek = mysqli_query($conn, "SELECT image FROM events WHERE id = $id");
    if ($r = mysqli_fetch_assoc($cek)) {
        $old_file = "../assets/img/Events/" . $r['image'];
        if (!empty($r['image']) && file_exists($old_file)) { unlink($old_file); }
    }
    mysqli_query($conn, "DELETE FROM events WHERE id = $id");
    header("Location: ../views/admin/events.php?success=delete");
    exit;
}
?>