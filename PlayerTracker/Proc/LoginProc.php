<?php
session_start(); // Start the session at the top
include "../Includes/Connect.php";
include "../Includes/Functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    $loginResult = loginUser($email, $password); // Assume loginUser returns true on success, false on failure
    
    if ($loginResult === "Success") {
        // Assuming loginUser sets some session variables on success
        header("Location: ../Pages/Profile.php");
        exit(); // Prevent further script execution after a redirect
    } else {
        // Handle login failure
        // For example, redirect back to the login page with a query parameter indicating failure
        header("Location: ../Pages/Login.php?error=invalidcredentials");
        exit();
    }
}
