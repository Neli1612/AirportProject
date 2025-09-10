<?php
require_once 'config.php';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$destinations = $pdo->query("SELECT id_destination, destination FROM Destination ORDER BY destination")
    ->fetchAll(PDO::FETCH_ASSOC);

$airlines = $pdo->query("SELECT id_airline, airline FROM Airline ORDER BY airline")
    ->fetchAll(PDO::FETCH_ASSOC);     

$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$dest = isset($_GET['dest']) ? $_GET['dest'] : '';
$air = isset($_GET['air']) ? $_GET['air'] : '';

$whereDepartures = "DATE(scheduled_departure) = :date";

$params = [':date' => $date];

if ($dest !== '') {
    $whereDepartures .= " AND dest.id_destination = :dest";
    $params[':dest'] = $dest;
}

if ($air !== '') {
    $whereDepartures .= " AND aline.id_airline = :air";
    $params[':air'] = $air;
}

$sql_departures = "
SELECT d.flight_number, d.scheduled_departure, d.actual_departure,
       dest.destination, s.flight_status, d.terminal, d.gate, aline.airline_logo
FROM Departures d
JOIN DepartureFlights df ON d.flight_number=df.flight_number
JOIN Destination dest ON df.id_destination = dest.id_destination
JOIN Airline aline ON aline.id_airline= df.id_airline
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
<title>Departures</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=flight_takeoff" />
<link rel="stylesheet" href="departures.css">
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
        </ul>
      </li>
      <li><a href="#">Transport ▾</a>
      <ul>
          <li><a href="transport.html">To and from airport</a></li>
          <li><a href="carRental.html">Car Rental</a></li>
          <li><a href="Parking.html">Parking</a></li>
        </ul>
    </li>
      <li><a href="#">Airport guide ▾</a>
      <ul>
          <li><a href="shops.html">Shops</a></li>
          <li><a href="eat&drink.html">Eat&Drink</a></li>
          <li><a href="airportMap.html">Airport map</a></li>
          <li><a href="#">Services ▾</a>
            <ul>
          <li><a href="lost&found.html">Lost&Found</a></li>
          <li><a href="bank&atm.html">Bank&ATM</a></li>
          <li><a href="vip.html">VIP</a></li>
          <li><a href="med.html">Medical Services</a></li>
           </ul>
          </li>
        </ul>
    </li>
      <li><a href="#">Passenger info ▾</a>
      <ul>
          <li><a href="check-in.html">Check-in</a></li>
          <li><a href="security.html">Security</a></li>
          <li><a href="passportControl.html">Passport Control</a></li>
          <li><a href="#">Special needs ▾</a>
            <ul>
          <li><a href="accessibleTravel.html">Accessible travel</a></li>
          <li><a href="travelWithKids.html">Travel with kids</a></li>
          <li><a href="travelWithPets.html">Travel with pets</a></li>
           </ul>
          </li>
        </ul>
    </li>
      <li><a href="#">Contact</a></li>
    </ul>
  </nav>
</header>

<div class="hero">
<h2><span class="material-symbols-outlined">flight_takeoff</span> Departures</h2>
</div>

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
    <label>Airline:
        <select name="air">
            <option value="">All</option>
            <?php foreach ($airlines as $a): ?>
            <option value="<?= $a['id_airline'] ?>" <?= $air == $a['id_airline'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($a['airline']) ?>
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
<th>Airline</th>
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
<td><img src="<?= htmlspecialchars($row['airline_logo']) ?>" alt="Airline logo" style="height: 50px;"></td>
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
