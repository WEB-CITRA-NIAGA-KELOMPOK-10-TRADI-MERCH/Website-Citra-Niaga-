<?php
session_start();
require_once '../config/koneksi.php';
/** @var mysqli $conn */
require_once '../models/ReviewsModel.php';

$reviewsModel = new ReviewsModel($conn);

if (isset($_POST['tambah'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../views/login.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $visitor_name = mysqli_real_escape_string($conn, $_POST['visitor_name']);
    $rating = (int)$_POST['rating'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    $visit_date = date('Y-m-d'); 

    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../views/index.php';
    
    $separator = (parse_url($referer, PHP_URL_QUERY) == NULL) ? '?' : '&';

    if ($reviewsModel->cekSudahReview($user_id)) {
        header("Location: " . $referer . $separator . "notif=sudah_review");
        exit;
    } else {
        $query_insert = "INSERT INTO reviews (user_id, visitor_name, rating, comment, visit_date) 
                         VALUES ('$user_id', '$visitor_name', '$rating', '$comment', '$visit_date')";
        
        if (mysqli_query($conn, $query_insert)) {
            header("Location: " . $referer . $separator . "notif=review_sukses");
        } else {
            echo "Gagal mengirim ulasan: " . mysqli_error($conn);
        }
        exit;
    }
}

if (isset($_GET['hapus']) || isset($_POST['hapus'])) {
    if (isset($_GET['hapus'])) {
        $id = $_GET['hapus'];
    } else {
        $id = $_POST['id']; 
    }
    
    $id = mysqli_real_escape_string($conn, $id);
    mysqli_query($conn, "DELETE FROM reviews WHERE id='$id'");
    
    header("Location: ../views/admin/reviews.php?deleted=1");
    exit;
}

header("Location: ../views/index.php");
exit;
?>