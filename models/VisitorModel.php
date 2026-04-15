<?php
class VisitorModel {
    /** @var mysqli */
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function getLandingStats() {
        $kios = mysqli_num_rows(mysqli_query($this->conn, "SELECT id FROM kios"));
        $reviews = mysqli_num_rows(mysqli_query($this->conn, "SELECT id FROM reviews"));
        $visitors = mysqli_num_rows(mysqli_query($this->conn, "SELECT id FROM visitors"));
        
        $ratingQuery = mysqli_query($this->conn, "SELECT AVG(rating) as avg_rating FROM reviews");
        $ratingData = mysqli_fetch_assoc($ratingQuery);
        $rating = $ratingData['avg_rating'] ? number_format($ratingData['avg_rating'], 1) : "0.0";
        
        return [
            'visitors' => $visitors > 0 ? $visitors : 150, 
            'kios' => $kios,
            'reviews' => $reviews,
            'rating' => $rating
        ];
    }
}
?>