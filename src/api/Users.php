<?php
//Users api//

$host = "localhost:3306"; 
$user = "root"; 
$password = "TrInItY45"; 
$dbname = "essaybud_Kencon"; 
$id = $error = $result = '';

$con = mysqli_connect($host, $user, $password,$dbname);

$method = $_SERVER['REQUEST_METHOD'];


if (!$con) {
  $error = "Connection failed: " . mysqli_connect_error();
  echo $error;
}


if ($method == 'GET') {
      if ($_GET['id']) {
        $id = $_GET['id'];
	$sql = "select * from Kencon_users where id=$id");
	$result = mysqli_query($con, $sql);
        if (mysqli_num_rows($result) > 0) {
          echo json_encode($result);
	} else {
          $error = "No such user in database!";
          echo $error;
        }
      } else {
	$sql = "select * from Kencon_users";
	$result = mysqli_query($con, $sql);
	if (mysqli_num_rows($result) > 0) {
          echo json_encode($result);
	} else {
          $error = "The database is empty!";
          echo $error;
	}
      }
      break;
} elseif ($method == 'POST') {
      $email = $_POST["email"];
      $pword = $_POST["pword"];
      if ($_POST["role"]) {
        $role = $_POST["role"];
        $name = $_POST["name"];
	$sql = "insert into Kencon_users (name, email, password, role) values ('$name', '$email', '$pword', '$role')";
      } else {
	$sql = "select * from Kencon_users where email='$email'";
	$result = mysqli_query($con, $sql);
	if (mysqli_num_rows($result) > 0) {
          $sql = "select * from Kencon_users where email='$email' and password='$pword'";
	  $result = mysqli_query($con, $sql);
	  if (mysqli_num_rows($result) > 0) {
            echo json_encode($result);
	  } else {
            $error = "Incorrect password!";
            echo $error;
          }
	} else {
          $error = "This email does not exist in the database!";
          echo $error;
        }
	break;
      }
}

$con->close();

?>
