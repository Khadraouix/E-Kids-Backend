<?php
// Create a new MySQLi instance
$conn = new mysqli("localhost", "root", "", "e-kids");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve all students
$result = $conn->query("SELECT * FROM student");

// Convert the result to an associative array
$students = $result->fetch_all(MYSQLI_ASSOC);

// Return the result as JSON
header("Content-Type: application/json");
echo json_encode($students);
?>