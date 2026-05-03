<?php

$servername = "127.0.0.1";
$username = "root";
$password = "asharimad12";
$dbname = "restaurant_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>