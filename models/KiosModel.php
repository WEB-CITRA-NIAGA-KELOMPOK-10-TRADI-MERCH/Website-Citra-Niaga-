<?php
class KiosModel {
    /** @var mysqli */
    private $db;

    public function __construct(mysqli $conn) {
        $this->db = $conn;
    }

    public function getAllKios() {
        return mysqli_query($this->db, "SELECT * FROM kios ORDER BY name ASC");
    }
}
?>