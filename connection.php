<?php
$servername = "localhost";
$username = "webadmin";
$password = "Admin123!";
$dbname = "ims480";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// 1. Force PHP web server to use Philippine Time
date_default_timezone_set('Asia/Manila');

// 2. Force MySQL database to stamp new records in Philippine Time (UTC+8)
$conn->query("SET time_zone = '+08:00'");
?>