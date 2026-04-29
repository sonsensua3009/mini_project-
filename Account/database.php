
<?php
class Database
{
    private $servername = "localhost";
    private $username = "root";
    private $password = "ServBay.dev";
    private $dbname = "mini_project";
    public $conn;
    public function __construct()
    {
        
        $this->conn = new mysqli(
            $this->servername,
            $this->username,
            $this->password,
            $this->dbname
        );
        if ($this->conn->connect_error) {
            die("Kết nối thất bại". $this->conn->connect_error);
}
 
}
}



?>