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
  $username = $table->username;
  $email = $table->email;
  $password = $table->password;
    $sql="SELECT c.nom,c.chapter,c.lvl 
    FROM courses c,student s,mylearning ml 
    WHERE s.username='$username' AND s.email='$email' AND s.password='$password'
    AND s.id_S=ml.id_S AND ml.id_C=c.id_C";
    $result=mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        $response[] = $row;}
        echo json_encode($response);
    }
    else {
        $response = array('success' => false, 'error' => 'Unable to insert data');
        echo json_encode($response);
      }

    mysqli_close($conn);
}
?>