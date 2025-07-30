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
    $whereArrivals .= " AND af.id_destination = :dest";
    $params[':dest'] = $dest;
}

// Вземаме пристигащи
$sql_arrivals = "
SELECT a.flight_number, a.scheduled_arrival, a.actual_arrival,
       d.destination, s.flight_status, a.terminal, a.arrival_exit, a.baggageBelt, aline.airline_logo
FROM Arrivals a
JOIN FlightStatus s ON a.id_status = s.id_status
JOIN ArrivalFlights af ON a.flight_number=af.flight_number
JOIN Destination d ON af.id_destination = d.id_destination
JOIN Airline aline ON aline.id_airline=af.id_airline
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
<title>Arrivals</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=flight_land" />
<link rel="stylesheet" href="arrivals.css">

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
      <li><a href="contacts.php">Contact</a></li>
    </ul>
  </nav>
</header>

<div class="hero">
     <h2><span class="material-symbols-outlined">flight_land</span> Arrivals</h2>
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
    <button type="submit">Filter</button>
</form>


<table>
<thead>
<tr>
<th>Scheduled arrival</th>
<th>Flight</th>
<th>Destination</th>
<th>Airline</th>
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
<td><img src="<?= htmlspecialchars($row['airline_logo']) ?>" alt="Airline logo" style="height: 50px;"></td>
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
