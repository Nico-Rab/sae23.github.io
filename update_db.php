<!DOCTYPE html>
<html lang="en">
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
    <form method="post">
        <h1>Update the DataBase</h1>
        <br>
        <input type="submit" name="update-db" id="update-db">
    </form>
</header>
<main id="results"></main>
<footer>
    <p>SAE23 &copy; 2023. RABERGEAU / GANGNANT / KURUL.</p>
</footer>
<script src="update-db.js"></script>
</body>
</html>
