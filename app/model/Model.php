<?php

require_once("../../db/Dbh.php");
abstract class Model{
    protected $db;
    protected $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    public function connect(){
        if(null === $this->conn ){
            $this->db = new Dbh();
        }
        return $this->db;
    }
}
?>