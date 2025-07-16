<?php
require_once 'config.php';
// Настройка за връзка
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$destinations = $pdo->query("SELECT id_destination, destination FROM Destination ORDER BY destination")
    ->fetchAll(PDO::FETCH_ASSOC);

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$dest = isset($_GET['dest']) ? $_GET['dest'] : '';

$whereArrivals = "DATE(scheduled_arrival) = :date";

$params = [':date' => $date];

if ($dest !== '') {
    $whereArrivals .= " AND a.id_destination = :dest";
    $params[':dest'] = $dest;
}

// Вземаме пристигащи
$sql_arrivals = "
SELECT a.flight_number, a.scheduled_arrival, a.actual_arrival,
       d.destination, s.flight_status, a.terminal, a.arrival_exit, a.baggageBelt
FROM Arrivals a
JOIN Destination d ON a.id_destination = d.id_destination
JOIN FlightStatus s ON a.id_status = s.id_status
WHERE $whereArrivals
ORDER BY a.scheduled_arrival ASC";

$stmt = $pdo->prepare($sql_arrivals);
$stmt->execute($params);
$arrivals = $stmt->fetchAll(PDO::FETCH_ASSOC);

//$arrivals = $pdo->query($sql_arrivals)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Flights</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=flight_land" />
<link rel="stylesheet" href="flightsStyles.css">

<!--<style>
body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
table { border-collapse: collapse; width: 100%; margin-bottom: 30px; background: #fff; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
th { background-color: #4482C1 ; color: white; }/*#0077cc*/
h2 { color: #4482C1; }
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

<h2><span class="material-symbols-outlined">flight_land</span> Arrivals</h2>

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
<th>Exit</th>
<th>Baggage belt</th>
<th>Actual arrival</th>
<th>Status</th>
</tr>
</thead>
<tbody>
<?php if (count($arrivals) === 0): ?>
<tr><td colspan="8">No flights found</td></tr>
<?php else: ?>
<?php foreach ($arrivals as $row): ?>
<tr>
<td><?= htmlspecialchars($row['scheduled_arrival']) ?></td>
<td><?= htmlspecialchars($row['flight_number']) ?></td>
<td><?= htmlspecialchars($row['destination']) ?></td>
<td><?= htmlspecialchars($row['terminal']) ?></td>
<td><?= htmlspecialchars($row['arrival_exit']) ?></td>
<td><?= htmlspecialchars($row['baggageBelt']) ?></td>
<td><?= htmlspecialchars($row['actual_arrival']) ?></td>
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
