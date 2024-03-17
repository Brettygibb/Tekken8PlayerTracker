<?php
include "Connect.php";

if (isset($_GET['id'])){
    $id =intval($_GET['id']);
    $query = "SELECT image FROM characters WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($row = $result->fetch_assoc()){
        $image = $row['image'];
        header("Content-type: image/jpeg");
        echo $image;
    }
    else{
        echo "No image found.";
    }
    $stmt->close();
}
else{
    echo "No image found.";
}
$conn->close();