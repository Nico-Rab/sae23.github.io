<?php

// Retrieve data from database
$sql = "SELECT * FROM Etudiant ORDER BY Groupe, Nom, Prenom ASC";

include "database.php";
global $conn;
$result = $conn->query($sql);

$data = array();
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Close connection
$conn->close();

// Return data as JSON
echo json_encode($data);