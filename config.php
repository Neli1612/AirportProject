<?php
$host = 'localhost';     
$user = 'root';          
$pass = '';              
$dbname = 'airport_db';

$conn = new mysqli($host, $user, $pass, $dbname);


if ($conn->connect_error) {
    die("No connection: " . $conn->connect_error);
}

?>