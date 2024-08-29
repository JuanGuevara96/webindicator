<?php
class Dbconfig {
    protected $serverName = 'localhost';
    protected $userName = 'exelco';
    protected $passCode = 'Exelco123*';
    protected $dbName = 'dbwebindicator';

    public $conn = null;

    // function Dbconfig() {
    //     $this -> serverName = 'localhost';
    //     $this -> userName = 'root';
    //     $this -> passCode = 'pass';
    //     $this -> dbName = 'dbase';
    // }

    function __construct() {
        $this->conn = mysqli_connect($this->serverName,$this->userName,$this->passCode,$this->dbName);
        if ($this->conn->connect_error) {
            echo "Fail".$this->conn->connect_error;
        }
        else
            echo "success";
    }

}
?>