<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Address and Wind Finder</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="https://openweathermap.org/img/w/01d.png" type="image/png" id="icon">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
</head>
<body>
<header>
    <h1>Ventilomètre</h1>
    <br>
    <form method="post">
        <label for="search">Entrez le nom ou prénom d'un étudiant:</label>
        <br><br>
        <input type="text" name="search" id="search" placeholder="Entrez un nom..." required>
        <input type="submit" name="submit-button" id="submit-button">
    </form>
    <div class="autocomplete">
        <ul id="autocomplete"></ul>
    </div>
    <div id="results"></div>
</header>
<div id="wind" class="wind"></div>
<footer>
    <p>SAE23 &copy; 2023. RABERGEAU / GANGNANT / KURUL.</p>
</footer>
<script src="search.js"></script>
</body>
</html>
