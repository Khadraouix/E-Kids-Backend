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
  $username = $data->username;
  $email = $data->email;
  $password = $data->password;
  $age = $data->age;
  $userType = $data->userType;

  // Check if the email or username already exists in the database
  if ($userType == 'student') {
    $checkSql = "SELECT * FROM student WHERE email='$email' OR username='$username'";
  } elseif ($userType == 'teacher') {
    $checkSql = "SELECT * FROM teacher WHERE email='$email' OR username='$username'";
  }

  $checkResult = mysqli_query($conn, $checkSql);

  if (mysqli_num_rows($checkResult) > 0) {
    // If a record with the same email or username already exists, return an error response and exit
    $response = array('success' => false, 'error' => 'User already exists');
    echo json_encode($response);
    exit();
  }

  // If no record with the same email or username exists, proceed with the INSERT query
  if ($userType == 'student') {
    $sql = "INSERT INTO student (username, email, password, age) VALUES ('$username', '$email', '$password', '$age')";
  } elseif ($userType == 'teacher') {
    $sql = "INSERT INTO teacher (username, email, password) VALUES ('$username', '$email', '$password')";
  } else {
    $response = array('success' => false, 'error' => 'Invalid user type');
    echo json_encode($response);
    exit();
  }

  if (mysqli_query($conn, $sql)) {
    $response = array('success' => true);
    echo json_encode($response);
  } else {
    $response = array('success' => false, 'error' => 'Unable to insert data');
    echo json_encode($response);
  }

  mysqli_close($conn);
}
?>
