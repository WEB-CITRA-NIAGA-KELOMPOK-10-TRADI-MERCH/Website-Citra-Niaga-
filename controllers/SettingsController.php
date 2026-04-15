<?php
session_start();
require_once '../config/koneksi.php';
/** @var mysqli $conn */
require_once '../models/SettingsModel.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../views/index.php");
    exit;
}

if (isset($_POST['update_settings'])) {
    $settingsModel = new SettingsModel($conn);
    
    $site_title = $_POST['site_title'];
    $site_desc = $_POST['site_desc'];
    $theme_color = $_POST['theme_color'];
    $seo_keywords = $_POST['seo_keywords'];
    
    $hero_banner = null;

    if (isset($_FILES['hero_banner']) && $_FILES['hero_banner']['error'] == 0) {
        $target_dir = "../assets/img/Bangunan/"; 
        $file_name = time() . "_" . basename($_FILES["hero_banner"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if(in_array($imageFileType, ['jpg', 'jpeg', 'png', 'webp'])) {
            if (move_uploaded_file($_FILES["hero_banner"]["tmp_name"], $target_file)) {
                $hero_banner = $file_name; 
            }
        }
    }

    if ($settingsModel->updateSettings($site_title, $site_desc, $theme_color, $seo_keywords, $hero_banner)) {
        header("Location: ../views/admin/settings.php?success=1");
    } else {
        header("Location: ../views/admin/settings.php?error=1");
    }
    exit;
}
?>