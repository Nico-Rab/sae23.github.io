<?php

const HOST = 'localhost';
const DB_NAME = 'ygangnan';
const USER = 'ygangnan';
const PASS = 'Sae23NicolasYanisFatih';

// Create connection
$conn = new mysqli(HOST, USER, PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}