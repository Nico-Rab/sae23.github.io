<?php

include 'database.php';
global $conn;

// Open the file and get his content
$file = './contacts.json';
$fileContent = file_get_contents($file);

// Update the DB with the content of the file
// Decode the file as it's a JSON file
$fileContent = json_decode($fileContent, true);

foreach ($fileContent as $line) {
    // First let's check if all the cities are implemented into the DB, if not, then insert it
    $cityName1 = $line['ville1'];
    $cityName2 = $line['ville2'];
    $codePostal1 = $line['cp1'];
    $codePostal2 = $line['cp2'];
    if ($cityName2 != null) {
        $sql = "SELECT * FROM Lieu WHERE Nom = '$cityName1' AND Code_Postal = '$codePostal1'";
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            $sql = "INSERT INTO Lieu (Nom, Code_Postal) VALUES ('$cityName1', '$codePostal1')";
            $conn->query($sql);
        }
        $sql = "SELECT * FROM Lieu WHERE Nom = '$cityName2' AND Code_Postal = '$codePostal2'";
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            $sql = "INSERT INTO Lieu (Nom, Code_Postal) VALUES ('$cityName2', '$codePostal2')";
            $conn->query($sql);
        }
    } else {
        $sql = "SELECT * FROM Lieu WHERE Nom = '$cityName1' AND Code_Postal = '$codePostal1'";
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            $sql = "INSERT INTO Lieu (Nom, Code_Postal) VALUES ('$cityName1', '$codePostal1')";
            $conn->query($sql);
        }
    }

    // Then let's check if everyone is inside the DB with there right group,
    // right Domicile Principal, and right Domicile Secondaire,
    // if not insert it or update the group, Domicile Principal or Domicile Secondaire
    // if not the right one
    $nom = $line['Nom'];
    $prenom = $line['Prenom'];
    $groupe = $line['Groupe'];
    $dp = $cityName1;
    $sql = "SELECT idL FROM Lieu WHERE Nom = '$dp'";
    $dp = $conn->query($sql)->fetch_assoc()['idL'];
    if ($cityName2 != null){
        $ds = $cityName2;
        $sql = "SELECT idL FROM Lieu WHERE Nom = '$ds'";
        $ds = $conn->query($sql)->fetch_assoc()['idL'];
    } else {
        $ds = null;
    }

    $sql = "SELECT * FROM Etudiant WHERE Nom = '$nom' AND Prenom = '$prenom'";
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        if($ds==null){
            $sql = "INSERT INTO Etudiant (Nom, Prenom, Groupe, DP) VALUES ('$nom', '$prenom', '$groupe', '$dp')";
            $conn->query($sql);
        } else {
            $sql = "INSERT INTO Etudiant (Nom, Prenom, Groupe, DP, DS) VALUES ('$nom', '$prenom', '$groupe', '$dp', $ds)";
            $conn->query($sql);
        }
    } else {
        $sql = "SELECT * FROM Etudiant WHERE Nom = '$nom' AND Prenom = '$prenom' AND Groupe = '$groupe' AND DP = '$dp' AND DS = '$ds'";
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            $sql = "UPDATE Etudiant SET Groupe = '$groupe', DP = '$dp', DS = '$ds' WHERE Nom = '$nom' AND Prenom = '$prenom'";
            $conn->query($sql);
        }
    }
}

// If all went right, close the connection and return a 'Success!' message, otherwise return the error
if ($conn->error) {
    echo $conn->error;
} else {
    echo 'Success!';
}