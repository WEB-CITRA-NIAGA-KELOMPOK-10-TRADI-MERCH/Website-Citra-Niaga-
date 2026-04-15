<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/koneksi.php';
/** @var mysqli $conn */

// --- PROSES LOGIN ---
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; 

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        if (md5($password) === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // PERBAIKAN: Pisahkan jalur redirect sesuai role
            if ($user['role'] === 'admin') {
                header("Location: ../views/admin/dashboard.php");
            } else {
                header("Location: ../views/index.php?notif=login_sukses");
            }
            exit;
        }
    }
    header("Location: ../views/login.php?error=1");
    exit;
}

// --- PROSES LOGOUT ---
if (isset($_GET['logout'])) {
    session_destroy();
    // Pukul rata: Semua dilempar ke Home setelah logout bawa notif
    header("Location: ../views/index.php?notif=logout_sukses");
    exit;
}

// --- PROSES REGISTRASI & AUTO-LOGIN ---
if (isset($_GET['action']) && $_GET['action'] == 'register') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; 
    
    $hashed_password = md5($password);
    $role = 'pengunjung'; 

    $query_register = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', '$role')";
    
    if (mysqli_query($conn, $query_register)) {
        $new_user_id = mysqli_insert_id($conn);
        
        $_SESSION['user_id'] = $new_user_id;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        // Pukul rata: Dilempar ke Home setelah register bawa notif
        header("Location: ../views/index.php?notif=register_sukses");
        exit;
    } else {
        echo "Gagal mendaftar: " . mysqli_error($conn);
        exit;
    }
}
?>