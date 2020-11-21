<?php
/**  Users api  **/

include('Connect.php');

$result = array('error'=>false);
$action = '';

if (isset($_GET['action'])) {
  $action = $_GET['action'];
  if ($action == 'read') {
    $sql = $conn->query("SELECT * FROM Kencon_users");
    $users = array();
    while($row = $sql->fetch_assoc()){
      array_push($users, $row);
    }
    $result['users'] = $users;
  }
  if ($action == 'create') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $pass = mysqli_real_escape_string($conn, crypt($_POST['pword'], '$6$rounds=427$CraZyMommAkateNinYasI$'));
    $sql = $conn->query("INSERT INTO Kencon_users(name, email, password, role) values ('$name', '$email', '$pass', '$role')");
    if($sql){
      $result['message'] = "User $name added successfully!";
    } else {
      $result['error'] = true;
      $result['message'] = "Failed to add new user $name!";
    }
  }
  if ($action == 'update') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $pass = mysqli_real_escape_string($conn, crypt($_POST['pword'], '$6$rounds=427$CraZyMommAkateNinYasI$'));
    $sql = $conn->query("UPDATE Kencon_users SET name='$name', email='$email', password='$pass', role='$role' where id=$id");
    if($sql){
      $result['message'] = "User $name updated successfully!";
    } else {
      $result['error'] = true;
      $result['message'] = "Failed to update user $name!";
    }
  }
  if ($action == 'login') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, crypt($_POST['pword'], '$6$rounds=427$CraZyMommAkateNinYasI$'));
    $sql = $conn->query("SELECT * FROM Kencon_users WHERE email='$email' AND password='$pass'");
    if($sql->mysqli_num_rows() > 0){
      while($row = $sql->fetch_assoc()){
        $result['user'] = $row;
      }
    } else {
      $result['error'] = true;
      $result['message'] = "Invalid email or password!";
    }
  }
  if ($action == 'delete') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $sql = $conn->query("DELETE FROM  Kencon_users where id='$id'");
    if($sql){
      $result['message'] = "User deleted successfully!";
    } else {
      $result['error'] = true;
      $result['message'] = "Failed to delete user!";
    }
  }
}

$conn->close();
echo json_encode($result);
?>