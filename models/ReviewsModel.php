<?php
class ReviewsModel {
    /** @var mysqli */
    private $db;

    public function __construct(mysqli $conn) {
        $this->db = $conn;
    }

    public function getAllReviews() {
        return mysqli_query($this->db, "SELECT * FROM reviews ORDER BY created_at DESC");
    }

    public function cekSudahReview(string $user_id) {
        $user_id = mysqli_real_escape_string($this->db, $user_id);
        
        $query = "SELECT * FROM reviews WHERE user_id = '$user_id'";
        $result = mysqli_query($this->db, $query);
        
        if (mysqli_num_rows($result) > 0) {
            return true; 
        } else {
            return false; 
        }
    }

    public function tambahReview(string $user_id, string $visitor_name, int $rating, string $comment, string $visit_date) {
        $user_id = mysqli_real_escape_string($this->db, $user_id);
        $visitor_name = mysqli_real_escape_string($this->db, $visitor_name);
        $rating = (int) $rating;
        $comment = mysqli_real_escape_string($this->db, $comment);
        $visit_date = mysqli_real_escape_string($this->db, $visit_date);

        $query = "INSERT INTO reviews (user_id, visitor_name, rating, comment, visit_date) 
                  VALUES ('$user_id', '$visitor_name', '$rating', '$comment', '$visit_date')";
                  
        return mysqli_query($this->db, $query);
    }
}
?>