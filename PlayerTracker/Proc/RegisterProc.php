<?php
include "../Includes/Connect.php";
include "../Includes/Functions.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $result = saveUserToDatabase($username,$email, $password);
    if($result === true){
        header("Location: ../Pages/Login.php");
        exit();
    } else {
        header("Location: ../Pages/Register.php?error=userexists");
        exit();
    }


}