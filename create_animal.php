<?php
error_reporting(0);
ini_set('display_errors', 0);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['tagId']) || !is_numeric($data['tagId'])) {
    echo json_encode(["error" => "Valid Tag ID is required"]);
    exit;
}

$sql = "INSERT INTO animals (tagId, animalname, animaltype, sex, breed, birthdate, ownerContact) VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issssss", 
    $data['tagId'], 
    $data['animalname'], 
    $data['animaltype'], 
    $data['sex'], 
    $data['breed'], 
    $data['birthdate'], 
    $data['ownerContact']
);

if ($stmt->execute()) {
    echo json_encode(["message" => "Animal registered successfully"]);
} else {
    echo json_encode(["error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>