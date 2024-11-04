<?php
    Class ConnectionManager{
        public function getConnection(){
        
        $host     = 'transformers.mysql.database.azure.com';
        $port     = '3306';
        $dbname   = 'employeedb';
        $username = 'user';
        $password = 'username0!';

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        return $pdo;
        }
    }


?>