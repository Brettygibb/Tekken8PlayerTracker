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
        return "Error preparing statement: " . htmlspecialchars($conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Correctly use $result to fetch the associative array
    $user = $result->fetch_assoc();

    if ($user && isset($user["passwordHash"])) {
        if (password_verify($password, $user["passwordHash"])) {
            // Start or ensure a session has started
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
    
            // Set session variables
            $_SESSION['user_id'] = $user['id']; // Assuming there's an 'id' field
            $_SESSION['username'] = $user['username']; // Assuming there's a 'username' field
    
            $stmt->close();
            return "Success";
        } else {
            $stmt->close();
            return "Invalid password";
        }
    }
    
    
}


