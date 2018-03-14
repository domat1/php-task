<?php
class Connection {

    protected $host = "localhost";
    protected $dbname = "test";
    protected $user = "root";
    protected $pass = "usbw";
    protected $DBH;

    function konche() {

        try {

            return new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
        }
        catch (PDOException $e) {

            echo $e->getMessage();
        }
    }

}
?>