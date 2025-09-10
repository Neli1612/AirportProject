<?php
session_start();
require_once 'config.php';

$id_emp = mysqli_real_escape_string($conn, $_POST['id_emp']);
$password = $_POST['emp_password'];

$sql = "SELECT * FROM employee WHERE id_emp = '$id_emp' LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    if (password_verify($password, $row['emp_password'])) {
        
        $_SESSION['logged_in'] = true;
        $_SESSION['id_emp'] = $row['id_emp'];
        $_SESSION['emp_name'] = $row['emp_name']; 
        header("Location: emp_homepage.php");
        exit;
    } else {
        echo " Wrong password.";
    }
} else {
    echo " No such employee.";
}

?>