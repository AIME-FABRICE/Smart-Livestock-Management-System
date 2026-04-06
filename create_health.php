<?php
error_reporting(0);
ini_set('display_errors', 0);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include "db.php";

$response = [];

$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    $response["error"] = "No data received";
    echo json_encode($response);
    exit;
}

if (!isset($data["tagId"]) || empty($data["tagId"])) {
    $response["error"] = "Tag ID is required";
    echo json_encode($response);
    exit;
}

$tagId = $data["tagId"];
$type = isset($data["type"]) ? $data["type"] : "";
$status = isset($data["status"]) ? $data["status"] : "";
$notes = isset($data["notes"]) ? $data["notes"] : "";
$startdate = isset($data["startdate"]) && !empty($data["startdate"]) ? $data["startdate"] : null;
$enddate = isset($data["enddate"]) && !empty($data["enddate"]) ? $data["enddate"] : null;
$nexteventdate = isset($data["nexteventdate"]) && !empty($data["nexteventdate"]) ? $data["nexteventdate"] : null;
$vetname = isset($data["vetName"]) ? $data["vetName"] : "";
$vetcontact = isset($data["vetcontact"]) ? $data["vetcontact"] : "";

// First, set previous records as not current (FIX: Added this critical step)
$updateStmt = $conn->prepare("UPDATE animal_health SET iscurrent = 0 WHERE tagid = ?");
if ($updateStmt) {
    $updateStmt->bind_param("i", $tagId);
    $updateStmt->execute();
    $updateStmt->close();
}

$sql = "INSERT INTO animal_health (tagid, type, status, notes, startdate, enddate, nexteventdate, vetname, vetcontact, iscurrent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issssssss", $tagId, $type, $status, $notes, $startdate, $enddate, $nexteventdate, $vetname, $vetcontact);

if ($stmt->execute()) {
    $response["message"] = "Health record added successfully";
} else {
    $response["error"] = $stmt->error;
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>