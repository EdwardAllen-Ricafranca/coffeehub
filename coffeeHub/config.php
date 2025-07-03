<?php
// config.php - MySQLi version
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'CoffeeShopDB';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>