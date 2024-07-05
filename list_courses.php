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
  $nom = mysqli_real_escape_string($conn, $data->nom); // escape the data to prevent SQL injection
  $chapter = mysqli_real_escape_string($conn, $data->chapter);
  $lvl = mysqli_real_escape_string($conn, $data->lvl);
  $img = mysqli_real_escape_string($conn, $data->img);
  $email = mysqli_real_escape_string($conn, $data->email);
  $password = mysqli_real_escape_string($conn, $data->password);

  // Check if the email and password are valid credentials for a teacher
  $verif = "SELECT id_T FROM teacher WHERE email='$email' and password='$password'";
  $result = mysqli_query($conn, $verif);
  if ($result && mysqli_num_rows($result) > 0) {
    // Get the teacher ID from the result set
    $row = mysqli_fetch_assoc($result);
    $id_T = $row['id_T'];

    // Insert the course data into the database with the teacher ID
    $sql = "INSERT INTO courses (nom, chapter, lvl, img, id_T) VALUES ('$nom', '$chapter', '$lvl', '$img', '$id_T')";
    if (mysqli_query($conn, $sql)) {
      $response = array('success' => true);
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
