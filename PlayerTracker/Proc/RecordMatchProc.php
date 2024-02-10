<?php
session_start();
include '../Includes/Connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['characterId'], $_POST['userId'], $_POST['outcome'])) {
    $characterId = $_POST['characterId'];
    $userId = $_POST['userId'];
    $outcome = $_POST['outcome'];

    // Validate and sanitize inputs as necessary

    // Prepare your SQL statement to insert the match outcome
    $stmt = $conn->prepare("INSERT INTO matches (user_id, character_id, result) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $userId, $characterId, $outcome);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Redirect back to profile with success message
        header("Location: ../Pages/Profile.php");
    } else {
        // Redirect back with error message
        header("Location: ../Pages/Profile.php?message=error");
    }
} else {
    // Redirect or handle invalid access
    header("Location: ../Pages/Login.php");
}
?>
