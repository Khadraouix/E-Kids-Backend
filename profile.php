<?php
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  header('HTTP/1.1 204 No Content');
  exit();
}

$table = json_decode(file_get_contents("php://input"));

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-kids";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $table->newusername;
    $email = $table->newemail;
    $password = $table->newpassword;

    $sql = "UPDATE teacher SET username='$username' , email='$email' , password='$password' WHERE username='$table->oldusername' and email='$table->oldemail' and password ='$table->oldpassword'";
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