<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/styles.css">


</head>
<body>
    <div>
        <h1>Register</h1>
        
        <form action="../Proc/RegisterProc.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label type="email" for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Register">
        </form>
    </div>
</body>
</html>