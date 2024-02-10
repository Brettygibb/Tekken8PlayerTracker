<?php
session_start();

$_SESSION = array(); // Clear all session variables

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy(); // Destroy the session

header("Location: ../Pages/Login.php"); // Redirect to the login page
exit();
?>