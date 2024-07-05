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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = mysqli_real_escape_string($conn, $data->email);
  $password = mysqli_real_escape_string($conn, $data->password);
  $id_C= mysqli_real_escape_string($conn, $data->id_C);

  // Check if the email and password are valid credentials for a teacher
  $verif = "SELECT id_S FROM student WHERE email='$email' and password='$password'";
  $result = mysqli_query($conn, $verif);
  if ($result && mysqli_num_rows($result) > 0) {
    // Get the teacher ID from the result set
    $row = mysqli_fetch_assoc($result);
    $id_S = $row['id_S'];

    // Insert the course data into the database with the teacher ID
    $sql = "INSERT INTO mylearning (id_S,id_C) VALUES ('$id_S','$id_C')";
    if (mysqli_query($conn, $sql)) {
      $response = array('success' => true,'id_S'=>$row['id_S'],'id_C'=>$row['id_C']);
      echo json_encode($response);
    } else {
      $response = array('success' => false, 'error' => 'Unable to insert data');
      echo json_encode($response);
    }
  } else {
    // Invalid teacher credentials, return an error response
    $response = array('success' => false, 'error' => 'Invalid teacher credentials');
    echo json_encode($response);
  }

  mysqli_close($conn);
}
?>
