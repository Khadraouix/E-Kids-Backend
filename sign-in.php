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
  $userType = mysqli_real_escape_string($conn, $data->userType);

  if ($userType == 'student') {
    $sql = "SELECT * FROM student WHERE email='$email' AND password='$password'";
  } else if ($userType == 'teacher') {
    $sql = "SELECT * FROM teacher WHERE email='$email' AND password='$password'";
  } else {
    $response = array('success' => false, 'error' => 'Invalid user type');
    echo json_encode($response);
    exit();
  }

  $result = mysqli_query($conn, $sql);


  if (mysqli_num_rows($result) > 0) {
    while($row=mysqli_fetch_assoc($result)){
    $response = array('success' => true,'username'=>$row['username'],'email'=>$row['email'],'password'=>$row['password']);
    echo json_encode($response);}
  } else {
    $response = array('success' => false, 'error' => 'Invalid email or password');
    echo json_encode($response);
  }

  mysqli_close($conn);
}
?>
