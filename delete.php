<?php
include("db.php");

if (!isset($_GET['tagId']) || !is_numeric($_GET['tagId'])) {
    echo json_encode(["error" => "Valid Tag ID is required"]);
    exit;
}

$tagId = $_GET['tagId'];

$conn->query("DELETE FROM animal_health WHERE tagid = $tagId");

$sql = "DELETE FROM animals WHERE tagId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tagId);

if ($stmt->execute()) {
    echo json_encode(["message" => "Animal and related health records deleted successfully"]);
} else {
    echo json_encode(["error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>