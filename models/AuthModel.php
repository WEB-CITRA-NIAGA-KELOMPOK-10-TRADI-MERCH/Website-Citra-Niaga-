<?php
class AuthModel {
    /** @var mysqli $conn */
    private $conn;

    /**
     * @param mysqli $db
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function checkLogin($username, $password) {
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = mysqli_query($this->conn, $query);
        return mysqli_num_rows($result) > 0;
    }
}
?>