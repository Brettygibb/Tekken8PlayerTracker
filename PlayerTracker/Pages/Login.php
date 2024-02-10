<?php
if(isset($_GET["message"])){
    if($_GET["message"] == "Success"){
        //alert
        echo "<script>alert('User has been created');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/styles.css">

</head>
<body>
    <div>
        <h1>Login</h1>
        <form action="../Proc/LoginProc.php" method="post">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
    </form>
    </div>
    <div>
        <a href="Register.php">Register</a>
    </div>
</body>
</html>