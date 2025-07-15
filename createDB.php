<?php

$host = 'localhost';     
$user = 'root';          
$pass = '';              
$dbname = 'airport_db';

$conn = new mysqli($host, $user, $pass);


if ($conn->connect_error) {
    die("No connection: " . $conn->connect_error);
}
 
$sql = "CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "Database '$dbname' created.<br>";
} else {
    die("Error: " . $conn->error);
}

$conn->select_db($dbname);

$tablesSQL = <<<SQL

-- AircraftType
CREATE TABLE AircraftType (
    id_type INT PRIMARY KEY AUTO_INCREMENT,
    aircraft_type VARCHAR(30),
    capacity INT
);

-- Airline
CREATE TABLE Airline (
    id_airline INT PRIMARY KEY AUTO_INCREMENT,
    airline VARCHAR(30)
);

-- Aircraft
CREATE TABLE Aircraft (
    flight_number VARCHAR(10) PRIMARY KEY,
    id_airline INT,
    id_type INT,
    call_sign VARCHAR(10),
    FOREIGN KEY (id_airline) REFERENCES Airline(id_airline),
    FOREIGN KEY (id_type) REFERENCES AircraftType(id_type)
);

-- Destination
CREATE TABLE Destination (
    id_destination INT PRIMARY KEY AUTO_INCREMENT,
    destination VARCHAR(30)
);

-- Status
CREATE TABLE FlightStatus (
    id_status INT PRIMARY KEY AUTO_INCREMENT,
    flight_status VARCHAR(20)
);

-- Arrivals
CREATE TABLE Arrivals (
    flight_number VARCHAR(10),
    scheduled_arrival TIMESTAMP,
    id_destination INT,
    terminal INT,
    arrival_exit VARCHAR(2),
    baggageBelt INT,
    actual_arrival TIME,
    id_status INT,
    FOREIGN KEY (flight_number) REFERENCES Aircraft(flight_number),
    FOREIGN KEY (id_destination) REFERENCES Destination(id_destination),
    FOREIGN KEY (id_status) REFERENCES FlightStatus(id_status)
);

-- Departures
CREATE TABLE Departures (
    flight_number VARCHAR(10),
    scheduled_departure TIMESTAMP,
    id_destination INT,
    terminal INT,
    gate VARCHAR(2),
    actual_departure TIME,
    id_status INT,
    FOREIGN KEY (flight_number) REFERENCES Aircraft(flight_number),
    FOREIGN KEY (id_destination) REFERENCES Destination(id_destination),
    FOREIGN KEY (id_status) REFERENCES FlightStatus(id_status)
);

-- Employee
CREATE TABLE Employee (
    id_emp INT PRIMARY KEY AUTO_INCREMENT,
    emp_name VARCHAR(100),
    emp_password CHAR(60) 
);

-- Contacts
CREATE TABLE Contacts (
    id_message INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(100),
    email VARCHAR(50),
    phone VARCHAR(12),
    messageText TEXT
);

SQL;


$queries = explode(";", $tablesSQL);
foreach ($queries as $query) {
    $q = trim($query);
    if (!empty($q)) {
        if ($conn->query($q) === TRUE) {
            echo "Table created.<br>";
        } else {
            echo "Error: " . $conn->error . "<br>";
        }
    }
}

$conn->close();
?>
