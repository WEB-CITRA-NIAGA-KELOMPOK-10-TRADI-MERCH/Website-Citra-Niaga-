<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/koneksi.php';
/** @var mysqli $conn */

// ==========================================
// PROSES LOGIN (WAJIB PAKAI EMAIL)
// ==========================================
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; 

    // Cari user berdasarkan EMAIL (bukan username lagi)
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        if (md5($password) === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: ../views/admin/dashboard.php");
            } else {
                header("Location: ../views/index.php?notif=login_sukses");
            }
            exit;
        }
    }
    // Kalau salah email/password
    header("Location: ../views/login.php?error=1");
    exit;
}

// ==========================================
// PROSES LOGOUT
// ==========================================
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../views/index.php?notif=logout_sukses");
    exit;
}

// ==========================================
// PROSES REGISTER PENGUNJUNG (AUTO LOGIN)
// ==========================================
if (isset($_GET['action']) && $_GET['action'] == 'register') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; 
    
    // VALIDASI: Cek apakah EMAIL sudah terdaftar? (Username boleh sama)
    $cek_email = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
    if (mysqli_num_rows($cek_email) > 0) {
        header("Location: ../views/register.php?error=email_exists");
        exit;
    }

    $hashed_password = md5($password);
    $role = 'pengunjung'; 

    // Insert data (Email ikut dimasukkan)
    $query_register = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', '$role')";
    
    if (mysqli_query($conn, $query_register)) {
        
        // 1. TANGKAP ID USER YANG BARU SAJA DISIMPAN KE DATABASE
        $new_user_id = mysqli_insert_id($conn);
        
        // 2. BUAT SESSION OTOMATIS (SISTEM MENGANGGAP USER SUDAH LOGIN)
        $_SESSION['user_id'] = $new_user_id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        // 3. LEMPAR LANGSUNG KE HALAMAN BERANDA/INDEX
        header("Location: ../views/index.php?notif=register_sukses");
        exit;

    } else {
        header("Location: ../views/register.php?error=system");
        exit;
    }
}
?>