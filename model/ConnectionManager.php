<?php
    Class ConnectionManager{
        public function getConnection(){
        
        $host     = 'transformers.mysql.database.azure.com';
        $port     = '3306';
        $dbname   = 'employeedb';
        $username = 'user';
        $password = 'username0!';

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname";

        $options = [
            PDO::MYSQL_ATTR_SSL_CA => $ssl_ca,       // Enable SSL with the CA certificate
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,  // Optional: disable server certificate verification
        ];

        $pdo = new PDO($dsn, $username, $password, $options);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        return $pdo;
        }
    }


?>