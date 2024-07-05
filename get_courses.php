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
  $email = $data->email;
  $password = $data->password;

  $sql = "SELECT courses.id_C, courses.nom, courses.chapter, courses.lvl, courses.img, teacher.id_T FROM courses JOIN teacher ON courses.id_T = teacher.id_T WHERE teacher.email='$email' AND teacher.password='$password'";
  $result = mysqli_query($conn, $sql);

  $response = array();

  while ($row = mysqli_fetch_assoc($result)) {
    $response[] = $row;
  }

  echo json_encode($response);

  mysqli_close($conn);
}
?>
