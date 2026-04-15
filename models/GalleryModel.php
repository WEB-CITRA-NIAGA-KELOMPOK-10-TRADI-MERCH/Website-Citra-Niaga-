<?php
class GalleryModel {
    /** @var mysqli */
    private $db;

    public function __construct(mysqli $conn) {
        $this->db = $conn;
    }

    public function getAllGallery() {
        return mysqli_query($this->db, "SELECT * FROM gallery ORDER BY id DESC");
    }
}
?>