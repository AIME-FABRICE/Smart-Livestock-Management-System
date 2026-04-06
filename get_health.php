<?php
include "db.php";

$response = [];

$sql = "SELECT * FROM animal_health ORDER BY id DESC";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
} else {
    $response["error"] = $conn->error;
}

echo json_encode($response);
$conn->close();
?>