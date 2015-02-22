<?php

/*
* @Title Icy
* @Author Dev Mage Tech LLC
* 
* License is distributed with the software at all times.  The license can be found in the files license.txt and license.html
* 
* Droplets is a registered trademark of Dev Mage Tech LLC
* Copyright Dev Mage Tech LLC 2015
*/

class SecureMySQL {

    private static $instance;
    private $database;
    private $host;
    private $user;
    private $password;
    private $port;
    private $connection="term";

    private function __construct() {
        require_once __DIR__.'/../System/Config.php';
        $this->database=$ICYConfig['MySQLDatabase'];
        $this->host=$ICYConfig['MySQLHost'];
        $this->user=$ICYConfig['MySQLUser'];
        $this->password=$ICYConfig['MySQLPassword'];   
        $this->port=$ICYConfig['MySQLPort'];
    }
    
    public function open(){
        $this->connection=  new mysqli($host, $user, $password, $database, $port);
        if (mysqli_connect_errno()){
            throw Exception("Failed to connect to MySQL: " . mysqli_connect_error());
        }
    }
    
    public function close(){
        mysqli_close(self::$connection);
        $this->connection="term";
    }
    
    public function queryRead($query,$types,$vars){
        if ($this->connection=="term"){
            throw Exception("No connection to MySQL Server.");
        }
        $stmt=$this->connection->prepare($query);
        $stmt->bind_param($types,$vars);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        return $row;
    }
    
    public function queryWrite($query,$types,$vars){
        if ($this->connection=="term"){
            throw Exception("No connection to MySQL Server.");
        }
        $stmt=$this->connection->prepare($query);
        $stmt->bind_param($types,$vars);
        $stmt->execute();
        return $stmt->affected_rows;
    }

    public static function getInstance() {
        if (!$this->instance) {
            $this->instance = new self();
        }
        return $this->instance;
    }

}

?>