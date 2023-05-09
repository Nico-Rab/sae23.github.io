<?php

include 'database.php';
global $conn;

// Token for https://api.openweathermap.org
$tokenInput = "64908c0f4421afd417abb76d3823a76e";

// Function to convert deg into orientation for the wind
function degToOrientation($deg): string
{
    $val = intval(($deg/22.5)+.5);
    $arr = array("North", "North-northeast", "Northeast", "East-northeast", "East", "East-southeast", "Southeast", "South-southeast", "South", "South-southwest", "Southwest", "West-southwest", "West", "West-northwest", "Northwest", "North-northwest");
    return $arr[($val % 16)];
}

// Loop through all cities name called "Nom" in the table Lieu, and check if the IdL of the table Meteo is the same as the idL of Lieu,
// and as a timestamp in today's timestamp range, then pass, otherwise, update Meteo.FV (wind speed), and Meteo.DV (wind direction)
// using the openweathermap api.

$sql = "SELECT Nom, idL FROM Lieu";
$result = $conn->query($sql);

$country = "France";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc())
    {
        $sql2 = "SELECT IdL, FV, DV, Date FROM Meteo WHERE IdL = " . $row["idL"] . " AND Date > " . strtotime("today midnight") . " AND Date < " . strtotime("tomorrow midnight");
        $result2 = $conn->query($sql2);
        if ($result2->num_rows == 0) {
            $url = "https://api.openweathermap.org/data/2.5/weather?q=" . $row["Nom"] . "," . $country . "&appid=" . $tokenInput;
            $json = file_get_contents($url);
            $data = json_decode($json, true);
            $windSpeed = $data["wind"]["speed"];
            $windDirection = degToOrientation($data["wind"]["deg"]);
            $sql3 = "INSERT INTO Meteo (IdL, FV, DV) VALUES (" . $row["idL"] . ", " . $windSpeed . ", '" . $windDirection . "')";
            $conn->query($sql3);
        }
    }
}

if($conn->error){
    echo "Error: " . $sql . "<br>" . $conn->error;
} else {
    echo "Success!";
}

$conn->close();
