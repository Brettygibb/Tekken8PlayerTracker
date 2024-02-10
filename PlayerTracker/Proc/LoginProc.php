<?php
session_start();
include "../Includes/Connect.php";
include "../Includes/Functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"]; // Consider using password_hash() and password_verify() for secure password handling
    
    // Assuming loginUser() verifies user credentials and returns true on success
    // It's better to fetch the user ID within the loginUser function if authentication is successful
    $userId = loginUser($email, $password); // Adjust loginUser to return user ID on success, false on failure
    
    if ($userId !== false) {
        // Authentication successful, set user ID in session
        $_SESSION['user_id'] = $userId;
        header("Location: ../Pages/Profile.php");
        exit();
    } else {
        // Authentication failed
        header("Location: ../Pages/Login.php?error=invalidcredentials");
        exit();
    }
}
?>
