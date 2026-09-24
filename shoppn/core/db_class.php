<?php
class Database {
    protected $conn;
    
    public function __construct() {
        require_once 'db_cred.php';
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->conn->connect_error) {
            $errorMsg = "Connection failed: " . $this->conn->connect_error;
            error_log($errorMsg);
            die($errorMsg);
        }
    }
}
?>
