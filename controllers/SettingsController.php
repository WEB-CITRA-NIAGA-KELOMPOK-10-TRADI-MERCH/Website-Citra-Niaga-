<?php
session_start();
require_once '../config/koneksi.php';
require_once '../models/SettingsModel.php'; 

/** @var mysqli $conn */

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../views/login.php"); 
    exit;
}

if (isset($_POST['simpan_pengaturan'])) {
    $settingsModel = new SettingsModel($conn);

    $cek = mysqli_query($conn, "SELECT id FROM settings WHERE id = 1");
    if (mysqli_num_rows($cek) == 0) {
        mysqli_query($conn, "INSERT INTO settings (id, site_title) VALUES (1, 'Citra Niaga')");
    }

    $hero_banner = null;

    if (isset($_POST['reset_banner']) && $_POST['reset_banner'] === '1') {
        $hero_banner = 'citraniagabackground.png';
    } 
    elseif (isset($_FILES['hero_banner']) && $_FILES['hero_banner']['error'] == 0) {
        $target_dir = "../assets/img/Gallery/Area_Bangunan/"; 
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $file_name = $_FILES["hero_banner"]["name"];
        $file_tmp = $_FILES["hero_banner"]["tmp_name"];
        $file_size = $_FILES["hero_banner"]["size"];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($file_extension, $allowed_ext)) {
            echo "<script>alert('GAGAL: Format banner harus JPG, PNG, atau WEBP!'); window.location.href='../views/admin/settings.php';</script>"; exit;
        }

        if ($file_size > 2097152) {
            echo "<script>alert('GAGAL: Ukuran banner maksimal 2MB!'); window.location.href='../views/admin/settings.php';</script>"; exit;
        }
            
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file_tmp);
        finfo_close($finfo);

        if (in_array($mime, ['image/jpeg', 'image/png', 'image/webp'])) {
            $new_banner_name = "banner_" . time() . "." . $file_extension;
            $target_file = $target_dir . $new_banner_name;

            if (move_uploaded_file($file_tmp, $target_file)) {
                $hero_banner = $new_banner_name;
            }
        }
    }

    $settingsModel->updateSettings($_POST, $hero_banner);

    header("Location: ../views/admin/settings.php?success=1");
    exit;
}
?>