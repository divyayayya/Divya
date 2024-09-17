<?php
    Class ConnectionManager{
        public function getConnection(){
        
        $host     = 'localhost';
        $port     = '3306';
        $dbname   = 'finance_tracking';
        $username = 'root';
        $password = '';

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        return $pdo;
        }
    }


?>