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
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function checkLogin($email, $password) {
        $query = "SELECT id FROM users WHERE email = ? AND password = ?";
        $stmt = mysqli_prepare($this->conn, $query);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $email, $password);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {
                return true;
            }
        }
        
        return false;
    }
}
?>