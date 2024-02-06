<?php

$database ="tekken8playertracker";
$username = "root";
$password = "";
$host = "localhost";

$conn = new mysqli($host, $username, $password, $database);
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}