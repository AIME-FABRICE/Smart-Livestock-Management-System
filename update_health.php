<?php
error_reporting(0);
ini_set('display_errors', 0);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include "db.php";

$response = [];

$input = file_get_contents("php://input");

if (empty($input)) {
    $response["error"] = "No data received";
    echo json_encode($response);
    exit;
}

$data = json_decode($input, true);

if (!$data) {
    $response["error"] = "Invalid JSON data";
    echo json_encode($response);
    exit;
}


$id = intval($data['id']);
$tagId = intval($data['tagId']);
$type = isset($data['type']) ? $data['type'] : '';
$status = isset($data['status']) ? $data['status'] : '';
$notes = isset($data['notes']) ? $data['notes'] : '';
$startdate = (isset($data['startdate']) && !empty($data['startdate'])) ? $data['startdate'] : null;
$enddate = (isset($data['enddate']) && !empty($data['enddate'])) ? $data['enddate'] : null;
$nexteventdate = (isset($data['nexteventdate']) && !empty($data['nexteventdate'])) ? $data['nexteventdate'] : null;
$vetname = isset($data['vetName']) ? $data['vetName'] : '';
$vetcontact = isset($data['vetcontact']) ? $data['vetcontact'] : '';
$iscurrent = isset($data['iscurrent']) ? intval($data['iscurrent']) : 1;

$sql = "UPDATE animal_health SET tagid = ?, type = ?, status = ?, notes = ?, startdate = ?, enddate = ?, nexteventdate = ?, vetname = ?, vetcontact = ?, iscurrent = ? WHERE id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    $response["error"] = "Database prepare error: " . $conn->error;
    echo json_encode($response);
    $conn->close();
    exit;
}

$stmt->bind_param("issssssssii", $tagId, $type, $status, $notes, $startdate, $enddate, $nexteventdate, $vetname, $vetcontact, $iscurrent, $id);

if ($stmt->execute()) {
    $response["message"] = "Health record updated successfully";
} else {
    $response["error"] = "Database error: " . $stmt->error;
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>