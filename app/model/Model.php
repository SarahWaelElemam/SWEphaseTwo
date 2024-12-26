<?php
// Ensure the correct path to Dbh.php
require_once(__DIR__ . '/../db/Dbh.php');

class Model {
    protected $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
}
?>
