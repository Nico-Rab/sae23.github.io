<?php

$name = $_POST['search'];

// Token for https://api.openweathermap.org
$tokenInput = "64908c0f4421afd417abb76d3823a76e";

// Split string into words
$words = explode(" ", $name);

// Assign each word to a variable
$firstName = $words[0];
$lastName = $words[1];

$sqlDP = "SELECT Lieu.Nom, Lieu.Code_Postal
FROM Lieu
JOIN Etudiant ON Lieu.IdL = Etudiant.DP
WHERE Etudiant.Nom = '$lastName'
AND Etudiant.Prenom = '$firstName'";

$sqlDS = "SELECT Lieu.Nom, Lieu.Code_Postal
FROM Lieu
JOIN Etudiant ON Lieu.IdL = Etudiant.DS
WHERE Etudiant.Nom = '$lastName'
AND Etudiant.Prenom = '$firstName'";

include 'database.php';
global $conn;

$resultDP = $conn->query($sqlDP);
$resultDS = $conn->query($sqlDS);

$dataDP = array();
if ($resultDP->num_rows > 0) {
    // Output data of each row
    while($row = $resultDP->fetch_assoc()) {
        $dataDP[] = $row;
    }
}

$dataDS = array();
if ($resultDS->num_rows > 0) {
    // Output data of each row
    while($row = $resultDS->fetch_assoc()) {
        $dataDS[] = $row;
    }
}
$data = array();
$data = array_merge($dataDP, $dataDS);

// Data for openweather request
$country = "France";
$cityDP = $data[0]['Nom'];
$cityDS = $data[1]['Nom'];

// Request url to https://api.openweathermap.org/data/2.5/weather
$urlDP = "https://api.openweathermap.org/data/2.5/weather?q=$cityDP,$country&appid=$tokenInput";
$urlDS = "https://api.openweathermap.org/data/2.5/weather?q=$cityDS,$country&appid=$tokenInput";

// Check last time the weather was updated in the DB
$sqlMeteoDP = "SELECT Meteo.Date FROM Meteo 
                  JOIN Lieu on Meteo.IdL = Lieu.idL
                  WHERE Lieu.Nom = '$cityDP'";
$sqlMeteoDS = "SELECT Meteo.Date FROM Meteo 
                  JOIN Lieu on Meteo.IdL = Lieu.idL
                  WHERE Lieu.Nom = '$cityDS'";
$resultMeteoDP = $conn->query($sqlMeteoDP);
$resultMeteoDS = $conn->query($sqlMeteoDS);
// If the value exist, and the timestamp is different from today's timestamp range, then update the Meteo

$dataMeteoDP = array();
$dataMeteoDS = array();

if ($resultMeteoDP->num_rows > 0) {
    // Output data of each row
    while($row = $resultMeteoDP->fetch_assoc()) {
        $dataMeteoDP[] = $row;
    }
}
if ($resultMeteoDS->num_rows > 0) {
    // Output data of each row
    while($row = $resultMeteoDS->fetch_assoc()) {
        $dataMeteoDS[] = $row;
    }
}

// Get today's timestamp range
$today = date("Y-m-d");
$todayStart = $today . " 00:00:00";
$todayEnd = $today . " 23:59:59";

// Function to convert deg into orientation for the wind
function degToOrientation($deg): string
{
    $val = intval(($deg/22.5)+.5);
    $arr = array("North", "North-northeast", "Northeast", "East-northeast", "East", "East-southeast", "Southeast", "South-southeast", "South", "South-southwest", "Southwest", "West-southwest", "West", "West-northwest", "Northwest", "North-northwest");
    return $arr[($val % 16)];
}

// If the timestamp is different from today's timestamp range, then insert the new value into Meteo
if ($resultMeteoDP->num_rows > 0 AND $resultMeteoDS->num_rows > 0){
    if ($dataMeteoDP[0]['Date'] < $todayStart OR $dataMeteoDP[0]['Date'] > $todayEnd){
        $dataMeteoDP = file_get_contents($urlDP);
        $dataMeteoDP = json_decode($dataMeteoDP, true);
        $dataWindSpeedDP = $dataMeteoDP['wind']['speed'];
        $dataWindDirectionDP = degToOrientation($dataMeteoDP['wind']['deg']);
        $sqlMeteoDP = "INSERT INTO Meteo (IdL, Date, FV, DV) VALUES ((SELECT idL FROM Lieu WHERE Nom = '$cityDP'), NOW(), '$dataWindSpeedDP', '$dataWindDirectionDP')";
        $conn->query($sqlMeteoDP);
    }
    if ($dataMeteoDS[0]['Date'] < $todayStart OR $dataMeteoDS[0]['Date'] > $todayEnd){
        $dataMeteoDS = file_get_contents($urlDS);
        $dataMeteoDS = json_decode($dataMeteoDS, true);
        $dataWindSpeedDS = $dataMeteoDS['wind']['speed'];
        $dataWindDirectionDS = degToOrientation($dataMeteoDS['wind']['deg']);
        $sqlMeteoDS = "INSERT INTO Meteo (IdL, Date, FV, DV) VALUES ((SELECT idL FROM Lieu WHERE Nom = '$cityDS'), NOW(), '$dataWindSpeedDS', '$dataWindDirectionDS')";
        $conn->query($sqlMeteoDS);
    }
} elseif ($resultMeteoDP->num_rows > 0 AND $resultMeteoDS->num_rows < 1 AND $cityDS != null){
    if ($dataMeteoDP[0]['Date'] < $todayStart OR $dataMeteoDP[0]['Date'] > $todayEnd){
        $dataMeteoDP = file_get_contents($urlDP);
        $dataMeteoDP = json_decode($dataMeteoDP, true);
        $dataWindSpeedDP = $dataMeteoDP['wind']['speed'];
        $dataWindDirectionDP = degToOrientation($dataMeteoDP['wind']['deg']);
        $sqlMeteoDP = "INSERT INTO Meteo (IdL, Date, FV, DV) VALUES ((SELECT idL FROM Lieu WHERE Nom = '$cityDP'), NOW(), '$dataWindSpeedDP', '$dataWindDirectionDP')";
        $conn->query($sqlMeteoDP);
    }
} elseif ($resultMeteoDP->num_rows < 1 AND $resultMeteoDS->num_rows > 0 AND $cityDP != null){
    if ($dataMeteoDS[0]['Date'] < $todayStart OR $dataMeteoDS[0]['Date'] > $todayEnd){
        $dataMeteoDS = file_get_contents($urlDS);
        $dataMeteoDS = json_decode($dataMeteoDS, true);
        $dataWindSpeedDS = $dataMeteoDS['wind']['speed'];
        $dataWindDirectionDS = degToOrientation($dataMeteoDS['wind']['deg']);
        $sqlMeteoDS = "INSERT INTO Meteo (IdL, Date, FV, DV) VALUES ((SELECT idL FROM Lieu WHERE Nom = '$cityDS'), NOW(), '$dataWindSpeedDS', '$dataWindDirectionDS')";
        $conn->query($sqlMeteoDS);
    }
} elseif ($resultMeteoDP->num_rows < 1 AND $resultMeteoDS->num_rows < 1 AND $cityDP != null AND $cityDS != null){
    $dataMeteoDP = file_get_contents($urlDP);
    $dataMeteoDP = json_decode($dataMeteoDP, true);
    $dataWindSpeedDP = $dataMeteoDP['wind']['speed'];
    $dataWindDirectionDP = degToOrientation($dataMeteoDP['wind']['deg']);
    $sqlMeteoDP = "INSERT INTO Meteo (IdL, Date, FV, DV) VALUES ((SELECT idL FROM Lieu WHERE Nom = '$cityDP'), NOW(), '$dataWindSpeedDP', '$dataWindDirectionDP')";
    $conn->query($sqlMeteoDP);
    $dataMeteoDS = file_get_contents($urlDS);
    $dataMeteoDS = json_decode($dataMeteoDS, true);
    $dataWindSpeedDS = $dataMeteoDS['wind']['speed'];
    $dataWindDirectionDS = degToOrientation($dataMeteoDS['wind']['deg']);
    $sqlMeteoDS = "INSERT INTO Meteo (IdL, Date, FV, DV) VALUES ((SELECT idL FROM Lieu WHERE Nom = '$cityDS'), NOW(), '$dataWindSpeedDS', '$dataWindDirectionDS')";
    $conn->query($sqlMeteoDS);
} elseif ($resultMeteoDP->num_rows < 1 AND $resultMeteoDS->num_rows < 1 AND $cityDP != null AND $cityDS == null){
    $dataMeteoDP = file_get_contents($urlDP);
    $dataMeteoDP = json_decode($dataMeteoDP, true);
    $dataWindSpeedDP = $dataMeteoDP['wind']['speed'];
    $dataWindDirectionDP = degToOrientation($dataMeteoDP['wind']['deg']);
    $sqlMeteoDP = "INSERT INTO Meteo (IdL, Date, FV, DV) VALUES ((SELECT idL FROM Lieu WHERE Nom = '$cityDP'), NOW(), '$dataWindSpeedDP', '$dataWindDirectionDP')";
    $conn->query($sqlMeteoDP);
}

// Get wind information from the DB
$sqlMeteoDP = "SELECT * FROM Meteo WHERE IdL = (SELECT idL FROM Lieu WHERE Nom = '$cityDP') ORDER BY Date DESC LIMIT 1";
$resultMeteoDP = $conn->query($sqlMeteoDP);
$sqlMeteoDS = "SELECT * FROM Meteo WHERE IdL = (SELECT idL FROM Lieu WHERE Nom = '$cityDS') ORDER BY Date DESC LIMIT 1";
$resultMeteoDS = $conn->query($sqlMeteoDS);

// Add the wind info into the $data array
if ($resultMeteoDP->num_rows > 0 AND $resultMeteoDS->num_rows > 0){
    $dataMeteoDP = $resultMeteoDP->fetch_all(MYSQLI_ASSOC);
    $dataMeteoDS = $resultMeteoDS->fetch_all(MYSQLI_ASSOC);
    $data['FVDP'] = $dataMeteoDP[0]['FV'] . ' m/s ';
    $data['DVDP'] = $dataMeteoDP[0]['DV'];
    $data['FVDS'] = $dataMeteoDS[0]['FV'] . ' m/s ';
    $data['DVDS'] = $dataMeteoDS[0]['DV'];
} elseif ($resultMeteoDP->num_rows > 0 AND $resultMeteoDS->num_rows < 1){
    $dataMeteoDP = $resultMeteoDP->fetch_all(MYSQLI_ASSOC);
    $data['FVDP'] = $dataMeteoDP[0]['FV'] . ' m/s ';
    $data['DVDP'] = $dataMeteoDP[0]['DV'];
    $data['FVDS'] = 'N/A';
    $data['DVDS'] = 'N/A';
} elseif ($resultMeteoDP->num_rows < 1 AND $resultMeteoDS->num_rows > 0){
    $dataMeteoDS = $resultMeteoDS->fetch_all(MYSQLI_ASSOC);
    $data['FVDP'] = 'N/A';
    $data['DVDP'] = 'N/A';
    $data['FVDS'] = $dataMeteoDS[0]['FV'] . ' m/s ';
    $data['DVDS'] = $dataMeteoDS[0]['DV'];
} else {
    $data['FVDP'] = 'N/A';
    $data['DVDP'] = 'N/A';
    $data['FVDS'] = 'N/A';
    $data['DVDS'] = 'N/A';
}


// Close connection
$conn->close();

// Return data as JSON
echo json_encode($data);
