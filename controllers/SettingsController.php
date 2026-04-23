<?php
session_start();
require_once '../config/koneksi.php';

/** @var mysqli $conn */

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../views/login.php"); 
    exit;
}

if (isset($_POST['simpan_pengaturan'])) {
    // 1. Data Teks Web
    $site_title = mysqli_real_escape_string($conn, $_POST['site_title']);
    $site_desc = mysqli_real_escape_string($conn, $_POST['site_desc']);
    $seo_keywords = mysqli_real_escape_string($conn, $_POST['seo_keywords']);
    
    // 2. Warna Latar (Background)
    $theme_color = mysqli_real_escape_string($conn, $_POST['theme_color']);
    $header_color = mysqli_real_escape_string($conn, $_POST['header_color']);
    $footer_color = mysqli_real_escape_string($conn, $_POST['footer_color']);
    
    // 3. Warna Teks (Ini yang tadi kurang di kodingan lu)
    $text_color = mysqli_real_escape_string($conn, $_POST['text_color']);
    $header_text_color = mysqli_real_escape_string($conn, $_POST['header_text_color']);
    $footer_text_color = mysqli_real_escape_string($conn, $_POST['footer_text_color']);
    
    // 4. Jenis Font
    $font_family = mysqli_real_escape_string($conn, $_POST['font_family']);
    
    // 5. Data Kontak
    $contact_phone = mysqli_real_escape_string($conn, $_POST['contact_phone']);
    $contact_email = mysqli_real_escape_string($conn, $_POST['contact_email']);
    $contact_ig = mysqli_real_escape_string($conn, $_POST['contact_ig']);
    $contact_address = mysqli_real_escape_string($conn, $_POST['contact_address']);

    // Pastikan baris dengan ID 1 ada di tabel
    $cek = mysqli_query($conn, "SELECT id FROM settings WHERE id = 1");
    if (mysqli_num_rows($cek) == 0) {
        mysqli_query($conn, "INSERT INTO settings (id, site_title) VALUES (1, 'Citra Niaga')");
    }

    // UPDATE DATABASE SECARA KESELURUHAN
    $query_update = "UPDATE settings SET 
        site_title = '$site_title', 
        site_desc = '$site_desc', 
        seo_keywords = '$seo_keywords', 
        theme_color = '$theme_color',
        header_color = '$header_color',
        footer_color = '$footer_color',
        text_color = '$text_color',
        header_text_color = '$header_text_color',
        footer_text_color = '$footer_text_color',
        font_family = '$font_family',
        contact_phone = '$contact_phone',
        contact_email = '$contact_email',
        contact_ig = '$contact_ig',
        contact_address = '$contact_address'
        WHERE id = 1";
    
    mysqli_query($conn, $query_update);

    // =====================================
    // LOGIC HERO BANNER (Upload & Reset)
    // =====================================
    if (isset($_POST['reset_banner']) && $_POST['reset_banner'] === '1') {
        mysqli_query($conn, "UPDATE settings SET hero_banner = 'citraniagabackground.png' WHERE id = 1");
    } 
    elseif (isset($_FILES['hero_banner']) && $_FILES['hero_banner']['error'] == 0) {
        $target_dir = "../assets/img/Gallery/Area_Bangunan/"; 
        if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
        
        $file_name = $_FILES["hero_banner"]["name"];
        $file_tmp = $_FILES["hero_banner"]["tmp_name"];
        $file_size = $_FILES["hero_banner"]["size"];
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Validasi Format
        $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array($file_extension, $allowed_ext)) {
            echo "<script>alert('GAGAL: Format banner harus JPG, PNG, atau WEBP!'); window.location.href='../views/admin/settings.php';</script>"; exit;
        }

        // Validasi Ukuran (Max 2MB)
        if ($file_size > 2097152) {
            echo "<script>alert('GAGAL: Ukuran banner maksimal 2MB!'); window.location.href='../views/admin/settings.php';</script>"; exit;
        }
            
        // Validasi File Beneran Gambar atau Bukan
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file_tmp);
        finfo_close($finfo);

        if (in_array($mime, ['image/jpeg', 'image/png', 'image/webp'])) {
            $new_banner_name = "banner_" . time() . "." . $file_extension;
            $target_file = $target_dir . $new_banner_name;

            // Pindahkan dan simpan ke database
            if (move_uploaded_file($file_tmp, $target_file)) {
                mysqli_query($conn, "UPDATE settings SET hero_banner = '$new_banner_name' WHERE id = 1");
            }
        }
    }

    // Redirect balik ke form
    header("Location: ../views/admin/settings.php?success=1");
    exit;
}
?>