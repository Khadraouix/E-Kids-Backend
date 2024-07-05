<?php
// Retrieve all STUDENTS
$result = $conn->query("SELECT * FROM student");

// Return the result as JSON
header("Content-Type: application/json");
echo json_encode($result->fetch_all(MYSQLI_ASSOC));
?>
