<?php
class Database
{
    private $host = "localhost";
    private $dbname = "mini_project";
    private $username = "root";
    private $password = "ServBay.dev";

    public $conn;

    public function connect()
    {
       $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);
       if ($this->conn->connect_error) {
        die("Kết nối thất bại". $this->conn->connect_error);
       }
      return $this->conn;
    }



}


?>