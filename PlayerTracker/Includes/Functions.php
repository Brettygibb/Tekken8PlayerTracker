<?php
include "Connect.php";

function saveUserToDatabase($username, $email, $password) {
    global $conn;

    // First, prepare and execute the GetUserByEmail stored procedure
    $stmt = $conn->prepare("CALL GetUserByEmail(?)");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $result->free();
        $stmt->close();
        return "User already exists";
        header("Location: ../Pages/Register.php?error=userexists");
    }
    

    // Important: Clear results from the first procedure call
    while ($conn->more_results() && $conn->next_result()) {
        // Free any additional result sets
        if ($l_result = $conn->use_result()) {
            $l_result->free();
        }
    }

    // Now, prepare and execute the RegisterUser stored procedure
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("CALL RegisterUser(?, ?, ?)");
    if (false === $stmt) {
        // Handle errors in preparing the statement
        return "Error preparing statement: " . htmlspecialchars($conn->error);
    }
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $stmt->close();
        exit(); // Prevent further execution
    } else {
        $stmt->close(); // Always ensure the statement is closed, even upon failure
        return "Error: " . $conn->error;
    }
}

function loginUser($email, $password) {
    global $conn;

    $stmt = $conn->prepare("CALL LoginUser(?)");
    if (!$stmt) {
        return false; // Preparation of statement failed
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (isset($user["passwordHash"]) && password_verify($password, $user["passwordHash"])) {
            // Ensure a session has started
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
    
            // Directly set session variables
            $_SESSION['user_id'] = $user['id']; // Store user ID in session
            $_SESSION['username'] = $user['username']; // Store username in session

            $stmt->close();
            return true; // Authentication successful
        }
    }
    
    $stmt->close();
    return false; // Authentication failed
}



