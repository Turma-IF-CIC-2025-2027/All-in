<?php 
$config = require 'env.php'; 
$conn = mysqli_connect($config['host'],$config['user'],$config['pass'], $config['db']); 
if (!$conn){
    die("Falha na ligação: " . mysqli_connect_error()); 
} 
mysqli_set_charset($conn, "utf8mb4");
?>