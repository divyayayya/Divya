<?php

spl_autoload_register(
    function ($class){
        require_once  "$class.php";
    }
);

require_once 'ConnectionManager.php';

session_start();

?>