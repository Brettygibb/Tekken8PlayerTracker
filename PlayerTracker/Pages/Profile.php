<?php
session_start(); // Start the session at the very top of your script
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($_SESSION['username']) ? $_SESSION['username'] . "'s Profile" : "User Profile"; ?></title>
</head>
<link rel="stylesheet" href="../css/styles.css">
<body>
<nav id="navBar">
    <ul>
        <li><a href="LogoutProc.php">Logout</a></li>
    </ul>
</nav>
<?php
// Check if the user is logged in and display a personalized welcome message
if(isset($_SESSION['username'])){
    // Use htmlspecialchars to prevent XSS attacks
    echo "<h1>Welcome, " . htmlspecialchars($_SESSION['username']) . "!</h1>";
} else {
    // Fallback message if the user is not logged in
    echo "<h1>Welcome, Guest!</h1>";
}
?>
    
    <div id="characterList" class="scroll-box">
    <!-- Characters will be loaded here with PHP -->
    <?php
include "../Includes/Connect.php";
$query = "SELECT id, name FROM characters";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<button class='character-btn' data-id='" . $row['id'] . "'>" . htmlspecialchars($row['name']) . "</button>";
    }
} else {
    echo "<p>No characters found.</p>";
}
?>
</div>

<div id="matchResults">
    <p id="wins">Wins: 0</p>
    <p id="losses">Losses: 0</p>
    <p id="ratio">Win/Loss Ratio: N/A</p>
</div>


<script>
document.getElementById('characterList').addEventListener('click', function(e) {
    if (e.target && e.target.matches('.character-btn')) {
        const characterId = e.target.getAttribute('data-id');

        fetch('fetch_records.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'characterId=' + characterId
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('wins').textContent = "Wins: " + data.wins;
            document.getElementById('losses').textContent = "Losses: " + data.losses;
            document.getElementById('ratio').textContent = "Win/Loss Ratio: " + data.ratio.toFixed(2);
        })
        .catch(error => console.error('Error:', error));
    }
});
</script>
</body>
</html>