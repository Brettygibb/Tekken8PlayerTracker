<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Login.php"); // Adjust if necessary
    exit();
}
include "../Includes/Connect.php";

function calculateWinLossRatio($characterId, $userId, $conn) {
    // Prepare statements for security
    $winsStmt = $conn->prepare("SELECT COUNT(*) AS wins FROM matches WHERE character_id = ? AND user_id = ? AND result = 'win'");
    $winsStmt->bind_param("ii", $characterId, $userId);
    $winsStmt->execute();
    $winsResult = $winsStmt->get_result()->fetch_assoc();
    $wins = $winsResult['wins'];

    $lossesStmt = $conn->prepare("SELECT COUNT(*) AS losses FROM matches WHERE character_id = ? AND user_id = ? AND result = 'loss'");
    $lossesStmt->bind_param("ii", $characterId, $userId);
    $lossesStmt->execute();
    $lossesResult = $lossesStmt->get_result()->fetch_assoc();
    $losses = $lossesResult['losses'];

    if ($wins + $losses === 0) {
        return "N/A";
    } else {
        $ratio = round($wins / ($wins + $losses), 2);
        return "Wins: $wins, Losses: $losses, Ratio: $ratio";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($_SESSION['username']) . "'s Profile"; ?></title>
    <link rel="stylesheet" href="../css/styles.css">
    <?php include '../Includes/Header.php'; ?>

    <script>
    function displayCharacterDetails(characterId) {
        var img = document.getElementById('characterImage');
        img.src = '../Includes/image.php?id=' + characterId; // Adjust the path as needed
        img.style.display = 'block';

    }

    </script>
</head>
<body>

    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>Your User ID is: <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
    <div class='content-container'>
        <div id="characterList" class="scroll-box">
            <?php
            $query = "SELECT id, name FROM characters"; // Adjust as per your actual database schema
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $characterId = $row['id'];
                    $winLossInfo = calculateWinLossRatio($characterId, $_SESSION['user_id'], $conn);
                    echo "<button class='character-btn' data-character-id='" . $characterId . "' data-ratio='" . htmlspecialchars($winLossInfo) . "' onclick='displayCharacterDetails(" . $characterId . ")'>" . htmlspecialchars($row['name']) . "</button>";
                    echo "<form action='../Proc/RecordMatchProc.php' method='post'>";
                     echo "<input type='hidden' name='characterId' value='" . $characterId . "'>";
                    echo "<input type='hidden' name='userId' value='" . $_SESSION['user_id'] . "'>";
                    echo "<button type='submit' name='outcome' class='outcome-btn' value='win'>Win</button>";
                    echo "<button type='submit' name='outcome' class='outcome-btn' value='loss'>Loss</button>";

                    //echo win/loss ratio
                    echo "<p class='wintoloss-p'>" . $winLossInfo . "</p>";
                    echo "</form>";
                }
        } else {
            echo "<p>No characters found.</p>";
        }
        ?>
        
        </div>

        <div id="characterDetails" style="text-align:center; margin-top: 20px;">
            <img id="characterImage" src="" alt="Character Image">
            <!-- Win/Loss buttons will be dynamically inserted here -->
        </div>
    </div>
</body>
</html>
