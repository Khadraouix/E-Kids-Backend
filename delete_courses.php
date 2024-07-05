<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  header('HTTP/1.1 204 No Content');
  exit();
}

$data = json_decode(file_get_contents("php://input"));

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-kids";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$id = isset($data->id_C) ? $data->id_C : null;

if ($id === null) {
  die("Course ID is missing.");
}

// Delete course from the courses table
$sql = "DELETE FROM courses WHERE id_C = $id";
$result = mysqli_query($conn, $sql);

if ($result) {
    $response = array('success' => true);
} else {
    $response = array('success' => false);
}

echo json_encode($response);

mysqli_close($conn);
?>
