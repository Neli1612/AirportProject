 <?php
require_once 'config.php';

$fullname = $_POST['full_name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$messageText = $_POST['messageText'];
$mdate = $_POST['mdate'];

$stmt = $conn->prepare("INSERT INTO contacts (full_name, phone, email, messageText, mdate) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $fullname, $phone, $email, $messageText, $mdate);

if ($stmt->execute()) {
    echo "Your message is sent";
} else {
    echo "Insert error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>

    
