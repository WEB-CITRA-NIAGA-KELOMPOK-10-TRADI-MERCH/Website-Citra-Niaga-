<?php
class HistoryModel {
    /** @var mysqli */
    private $db;

    public function __construct(mysqli $conn) {
        $this->db = $conn;
    }

    public function getAllHistory() {
        $query = "SELECT * FROM history ORDER BY year DESC";
        return mysqli_query($this->db, $query);
    }

    public function getHistoryById(int $id) {
        $id = (int)$id;
        $query = "SELECT * FROM history WHERE id = $id";
        $result = mysqli_query($this->db, $query);
        return mysqli_fetch_assoc($result);
    }

    public function cekEksistensi(int $id) {
        $id = (int)$id;
        $query = "SELECT id FROM history WHERE id = $id";
        $result = mysqli_query($this->db, $query);
        return mysqli_num_rows($result) > 0;
    }
}
?>