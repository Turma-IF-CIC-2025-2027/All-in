<?php
$conn = new mysqli("localhost", "usr21", "dacic2020", "usr21");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (isset($_POST["new_username"]) && isset($_POST["new_email"]) && isset($_POST["new_password"])) {
    $userPassword = password_hash($_POST["new_password"], PASSWORD_DEFAULT);
    $userName = $_POST["new_username"];
    $userEmail = $_POST["new_email"];   
}






?>
