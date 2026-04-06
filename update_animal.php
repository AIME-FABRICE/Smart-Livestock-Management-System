<?php
include("db.php");

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["error" => "No data received"]);
    exit;
}

if (!isset($data['tagId']) || !is_numeric($data['tagId'])) {
    echo json_encode(["error" => "Valid Tag ID is required"]);
    exit;
}

$tagId = $data['tagId'];
$animalname = isset($data['animalname']) ? $data['animalname'] : '';
$animaltype = isset($data['animaltype']) ? $data['animaltype'] : '';
$sex = isset($data['sex']) ? $data['sex'] : '';
$breed = isset($data['breed']) ? $data['breed'] : '';
$birthdate = isset($data['birthdate']) && !empty($data['birthdate']) ? $data['birthdate'] : null;
$ownerContact = isset($data['ownerContact']) ? $data['ownerContact'] : '';

$sql = "UPDATE animals SET animalname = ?, animaltype = ?, sex = ?, breed = ?, birthdate = ?, ownerContact = ? WHERE tagId = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $animalname, $animaltype, $sex, $breed, $birthdate, $ownerContact, $tagId);

if ($stmt->execute()) {
    echo json_encode(["message" => "Animal updated successfully"]);
} else {
    echo json_encode(["error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>