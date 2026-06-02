<?php
$servername = "mysql";
$username   = ini_get('mysqli.default_user');
$password   = ini_get('mysqli.default_pw');
$database   = $_DBNAME ?? 'planten';
 
try {
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        error_log($conn->connect_error);
        exit("Connection DB failed");
    }
} catch (Exception $e) {
    error_log($e);
    exit("Connection DB failed");
}
 
return $conn;
 
?>