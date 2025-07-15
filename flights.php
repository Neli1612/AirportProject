<?php
require_once 'config.php';
// Настройка за връзка
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Вземаме пристигащи
$sql_arrivals = "
SELECT a.flight_number, a.scheduled_arrival, a.actual_arrival,
       d.destination, s.flight_status, a.terminal, a.arrival_exit, a.baggageBelt
FROM Arrivals a
JOIN Destination d ON a.id_destination = d.id_destination
JOIN FlightStatus s ON a.id_status = s.id_status
ORDER BY a.scheduled_arrival ASC";

$arrivals = $pdo->query($sql_arrivals)->fetchAll(PDO::FETCH_ASSOC);

// Вземаме заминаващи
$sql_departures = "
SELECT d.flight_number, d.scheduled_departure, d.actual_departure,
       dest.destination, s.flight_status, d.terminal, d.gate
FROM Departures d
JOIN Destination dest ON d.id_destination = dest.id_destination
JOIN FlightStatus s ON d.id_status = s.id_status
ORDER BY d.scheduled_departure ASC";

$departures = $pdo->query($sql_departures)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
<meta charset="UTF-8">
<title>Пристигащи и Заминаващи</title>
<style>
body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
table { border-collapse: collapse; width: 100%; margin-bottom: 30px; background: #fff; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
th { background-color: #0077cc; color: white; }
h2 { color: #0077cc; }
</style>
</head>
<body>

<h2>📥 Пристигащи</h2>
<table>
<thead>
<tr>
<th>Полет</th>
<th>Планиран</th>
<th>Действителен</th>
<th>Дестинация</th>
<th>Статус</th>
<th>Терминал</th>
<th>Изход</th>
<th>Колан за багаж</th>
</tr>
</thead>
<tbody>
<?php foreach ($arrivals as $row): ?>
<tr>
<td><?= htmlspecialchars($row['flight_number']) ?></td>
<td><?= htmlspecialchars($row['scheduled_arrival']) ?></td>
<td><?= htmlspecialchars($row['actual_arrival']) ?></td>
<td><?= htmlspecialchars($row['destination']) ?></td>
<td><?= htmlspecialchars($row['status']) ?></td>
<td><?= htmlspecialchars($row['terminal']) ?></td>
<td><?= htmlspecialchars($row['arrival_exit']) ?></td>
<td><?= htmlspecialchars($row['baggageBelt']) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<h2>📤 Заминаващи</h2>
<table>
<thead>
<tr>
<th>Полет</th>
<th>Планиран</th>
<th>Действителен</th>
<th>Дестинация</th>
<th>Статус</th>
<th>Терминал</th>
<th>Гейт</th>
</tr>
</thead>
<tbody>
<?php foreach ($departures as $row): ?>
<tr>
<td><?= htmlspecialchars($row['flight_number']) ?></td>
<td><?= htmlspecialchars($row['scheduled_departure']) ?></td>
<td><?= htmlspecialchars($row['actual_departure']) ?></td>
<td><?= htmlspecialchars($row['destination']) ?></td>
<td><?= htmlspecialchars($row['status']) ?></td>
<td><?= htmlspecialchars($row['terminal']) ?></td>
<td><?= htmlspecialchars($row['gate']) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</body>
</html>
