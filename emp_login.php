<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $password = $_POST['password'];

    // Променено към правилните имена на колоните
    $query = "SELECT * FROM employee WHERE id_emp='$id' AND empPassword='$password'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $_SESSION['id'] = $id;
        header("Location: emp_homepage.php");
        exit();
    } else {
        echo "Invalid id or password.";
    }
}
?>