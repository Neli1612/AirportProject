<?php
require_once 'config.php';

$action = isset($_POST['action']) ? $_POST['action'] : '';
$table = isset($_POST['table']) ? $_POST['table'] : '';
$allowed = array('airline', 'arrivalflights', 'arrivals', 'departureflights', 'departures',
 'destination', 'flightstatus', 'contacts', 'employee');
 
if (!in_array($table, $allowed)) {
  exit;
}

if ($action == 'insert') {
  $keys = array();
  $values = array();
  foreach ($_POST as $k => $v) {
    if ($k != 'action' && $k != 'table') {
      $keys[] = "`$k`";
      if ($k == 'emp_password') {
    $v = password_hash($v, PASSWORD_DEFAULT);
      } 
      $values[] = "'" . mysqli_real_escape_string($conn, $v) . "'";
    }
  }
  $sql = "INSERT INTO `$table` (" . implode(",", $keys) . ") VALUES (" . implode(",", $values) . ")";
  mysqli_query($conn, $sql);

} elseif ($action == 'update') {
  $updates = array();
  $pk = '';
  $pk_val = '';
  foreach ($_POST as $k => $v) {
    if ($k != 'action' && $k != 'table') {
      if ($pk == '') {
        $pk = $k;
        $pk_val = mysqli_real_escape_string($conn, $v);
      } else {
        if ($k == 'emp_password' && !empty($v)) {
          $v = password_hash($v, PASSWORD_DEFAULT);
        }
        $updates[] = "`$k` = '" . mysqli_real_escape_string($conn, $v) . "'";
      }
    }
  }
  $sql = "UPDATE `$table` SET " . implode(",", $updates) . " WHERE `$pk` = '$pk_val'";
  mysqli_query($conn, $sql);

} elseif ($action == 'delete') {
  $pk = mysqli_real_escape_string($conn, $_POST['pk']);
  $pkField = 'id'; // Приеми, че всяка таблица има `id`
  $sql = "DELETE FROM `$table` WHERE `$pkField` = '$pk'";
  mysqli_query($conn, $sql);
}

?>
