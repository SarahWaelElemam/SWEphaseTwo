<?php
session_start();
require_once('C:/xampp/htdocs/SWEPhase2/SWEPhaseTwo/app/db/Dbh.php');
abstract class Model{
    protected $db;
    protected $conn;

    public function connect(){
        if(null === $this->conn ){
            $this->db = new Dbh();
        }
        return $this->db;
    }
}
?>












