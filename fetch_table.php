<?php
require_once 'config.php';

$table = isset($_GET['table']) ? $_GET['table'] : '';
$allowed = array('airline', 'arrivalflights', 'arrivals', 'departureflights', 'departures',
 'destination', 'flightstatus', 'contacts', 'employee');

if (!in_array($table, $allowed)) {
  echo "Invalid table.";
  exit;
}

$result = mysqli_query($conn, "SELECT * FROM `$table`");

echo "<h2>" . htmlspecialchars($table) . "</h2>";
echo "<table border='1'><tr>";
$fields = array();

while ($field = mysqli_fetch_field($result)) {
  $fields[] = $field->name;
  echo "<th>" . htmlspecialchars($field->name) . "</th>";
}
echo "<th>Actions</th></tr>";

mysqli_data_seek($result, 0);
while ($row = mysqli_fetch_assoc($result)) {
  echo "<tr>";
  foreach ($fields as $field) {
    echo "<td>" . htmlspecialchars($row[$field]) . "</td>";
  }
  echo "<td>
    <button onclick='editRow(this)'>Edit</button>
    <button onclick='deleteRow(this)'>Delete</button>
  </td></tr>";
}
echo "</table>";
echo "<br><button onclick='insertRow()' >Insert New</button>";
?>


