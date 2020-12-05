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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $pass = crypt($_POST['pword'], '$6$rounds=427$CraZyMommAkateNinYasI$');
    $sql = $conn->query("INSERT INTO Kencon_users(name, email, password, role) values ('$name', '$email', '$pass', '$role')");
    if($sql){
      $result['message'] = "User $name added successfully!";
    } else {
      $result['error'] = true;
      $result['message'] = "Failed to add new user $name!".$conn->error;
    }
  }
  if ($action == 'update') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $pass = crypt($_POST['pword'], '$6$rounds=427$CraZyMommAkateNinYasI$');
    $sql = $conn->query("UPDATE Kencon_users SET name='$name', email='$email', password='$pass', role='$role' where id=$id");
    if($sql){
      $result['message'] = "User $name updated successfully!";
    } else {
      $result['error'] = true;
      $result['message'] = "Failed to update user $name!".$conn->error;
    }
  }
  if ($action == 'login') {
    $email = $_POST['email'];
    $pass = crypt($_POST['pword'], '$6$rounds=427$CraZyMommAkateNinYasI$');
    $sql = $conn->query("SELECT * FROM Kencon_users WHERE email='$email'");
    if($sql->num_rows > 0){
      while($row = $sql->fetch_assoc()){
        if($row['password'] == $pass){
          $result['user'] = $row;
        }else {
          $result['error'] = true;
          $result['message'] = "Invalid password!";
        }
      }
    } else {
      $result['error'] = true;
      $result['message'] = "Invalid email address!";
    }
  }
  if ($action == 'delete') {
    $id = $_POST['id'];
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