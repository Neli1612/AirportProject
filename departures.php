<?php
require_once 'config.php';
// Настройка за връзка
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$destinations = $pdo->query("SELECT id_destination, destination FROM Destination ORDER BY destination")
    ->fetchAll(PDO::FETCH_ASSOC);

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$dest = isset($_GET['dest']) ? $_GET['dest'] : '';

$whereDepartures = "DATE(scheduled_departure) = :date";

$params = [':date' => $date];

if ($dest !== '') {
    $whereDepartures .= " AND d.id_destination = :dest";
    $params[':dest'] = $dest;
}

$sql_departures = "
SELECT d.flight_number, d.scheduled_departure, d.actual_departure,
       dest.destination, s.flight_status, d.terminal, d.gate
FROM Departures d
JOIN Destination dest ON d.id_destination = dest.id_destination
JOIN FlightStatus s ON d.id_status = s.id_status
WHERE $whereDepartures
ORDER BY d.scheduled_departure ASC";

$stmt = $pdo->prepare($sql_departures);
$stmt->execute($params);
$departures = $stmt->fetchAll(PDO::FETCH_ASSOC);

//$departures = $pdo->query($sql_departures)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
<meta charset="UTF-8">
<title>Flights</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=flight_takeoff" />
<link rel="stylesheet" href="flightsStyles.css">
<!--<style>
body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
table { border-collapse: collapse; width: 100%; margin-bottom: 30px; background: #fff; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
th { background-color: #0077cc; color: white; }
h2 { color: #0077cc; }
form { margin-bottom: 20px; }
.material-symbols-outlined {
  font-variation-settings:
  'FILL' 0,
  'wght' 400,
  'GRAD' 0,
  'opsz' 40
}
</style>-->
</head>
<body>

<header>
  <div class="logo">Varna Airport</div>
  <nav>
    <ul>
      <li><a href="index.html">Home</a></li>
      <li>
        <a href="#">Flights ▾</a>
        <ul>
          <li><a href="arrivals.php">Arrivals</a></li>
          <li><a href="departures.php">Departures</a></li>
          <li><a href="#">Airlines</a></li>
        </ul>
      </li>
      <li><a href="#">Transport</a></li>
      <li><a href="#">Airport Info</a></li>
      <li><a href="#">Passengers</a></li>
      <li><a href="#">Contact</a></li>
    </ul>
  </nav>
</header>

<h2><span class="material-symbols-outlined">flight_takeoff</span> Departures</h2>

<form method="get">
    <label>Date: 
        <input type="date" name="date" value="<?= htmlspecialchars($date) ?>">
    </label>
    <label>Destination:
        <select name="dest">
            <option value="">All</option>
            <?php foreach ($destinations as $d): ?>
            <option value="<?= $d['id_destination'] ?>" <?= $dest == $d['id_destination'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($d['destination']) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </label>
    <button type="submit">Filter</button>
</form>

<table>
<thead>
<tr>
<th>Scheduled</th>
<th>Flight</th>
<th>Destination</th>
<th>Terminal</th>
<th>Gate</th>
<th>Actual departure</th>
<th>Status</th>
</tr>
</thead>
<tbody>
<?php if (count($departures) === 0): ?>
<tr><td colspan="8">No flights found</td></tr>
<?php else: ?>
<?php foreach ($departures as $row): ?>
<tr>
<td><?= htmlspecialchars($row['scheduled_departure']) ?></td>
<td><?= htmlspecialchars($row['flight_number']) ?></td>
<td><?= htmlspecialchars($row['destination']) ?></td>
<td><?= htmlspecialchars($row['terminal']) ?></td>
<td><?= htmlspecialchars($row['gate']) ?></td>
<td><?= htmlspecialchars($row['actual_departure']) ?></td>
<td><?= htmlspecialchars($row['flight_status']) ?></td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>

<footer>
  &copy; 2025 Varna Airport. All rights reserved.
</footer>

</body>
</html>
