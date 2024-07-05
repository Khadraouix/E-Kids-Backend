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
    $nom=$data->nom;
    $lvl=$data->lvl;

    $sql0="SELECT id_S FROM student WHERE email='$email' AND password='$password' AND username='$username'";
    $verif0=mysqli_query($conn, $sql0);

    $sql1="SELECT id_C FROM courses WHERE nom='$nom' AND lvl='$lvl'";
    $verif1=mysqli_query($conn, $sql1);

    if (($verif0 && mysqli_num_rows($verif0) > 0) && ($verif1 && mysqli_num_rows($verif1) > 0)) {

        $row0 = mysqli_fetch_assoc($verif0);
        $id_S = $row0['id_S'];

        $row1 = mysqli_fetch_assoc($verif1);
        $id_C = $row1['id_C'];
        
        $sql = "DELETE FROM mylearning WHERE id_S = $id_S AND id_C=$id_C";
        $result = mysqli_query($conn, $sql);

    if ($result) {
        $response = array('success' => true);
    } else {
        $response = array('success' => false);
    }

    echo json_encode($response);

    mysqli_close($conn);
}
}
?>
