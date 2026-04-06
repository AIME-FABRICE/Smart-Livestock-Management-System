<?php
include("db.php");

$sql = "SELECT * FROM animals ORDER BY tagId DESC";
$result = $conn->query($sql);

$animals = array();
while ($row = $result->fetch_assoc()) {
    $animals[] = $row;
}

echo json_encode($animals);
$conn->close();
?>