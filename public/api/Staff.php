<?php
/**  Staff api  **/

include('Connect.php');

$result = array('error'=>false);
$action = '';

if (isset($_GET['action'])) {
  $action = $_GET['action'];
  if ($action == 'read') {
    $sql = $conn->query("SELECT * FROM Kencon_staff");
    $staff = array();
    while($row = $sql->fetch_assoc()){
      array_push($staff, $row);
    }
    $result['staff'] = $staff;
  }
  if ($action == 'create') {
    $fname = $_POST["fname"];
    $oname = $_POST["oname"];
    $address = $_POST["address"];
    $status = $_POST["status"];
    $idNo = $_POST["idNo"];
    $phone = $_POST["phone"];
    $office = $_POST["office"];
    $upload_dir = '../assets/staff/';
    $upload_file = $upload_dir . basename($_FILES["img"]["name"]);
    if (move_uploaded_file($_FILES["img"]["tmp_name"], $upload_file)) {
      $img = $upload_file;
      $sql = $conn->query("INSERT INTO Kencon_staff(fname, oname, email, status, idNo, phone, img, office) VALUES ('$fname', '$oname', '$address', '$status', $idNo, '$phone', '$img', '$office')");
      if($sql){
        $result['message'] = "Member $oname added successfully!";
      } else {
        $result['error'] = true;
        $result['message'] = "Failed to add new user $oname!".$conn->error;
      }
    } else {
      $result['error'] = true;
      $result['message'] = "Failed to add image to local storage!";
    }
  }
  if ($action == 'update') {
    $id = $_POST['id'];
    $oname = $_POST["oname"];
    $address = $_POST["address"];
    $status = $_POST["status"];
    $idNo = $_POST["idNo"];
    $phone = $_POST["phone"];
    $office = $_POST["office"];
    $upload_dir = '../assets/staff/';
    $upload_file = $upload_dir . basename($_FILES["img"]["name"]);
    if (move_uploaded_file($_FILES["img"]["tmp_name"], $upload_file)) {
      $img = $upload_file;
      $sql = $conn->query("UPDATE Kencon_staff SET fname='$fname', oname='$oname', email='$address', status='$status', idNo='$idNo', phone='$phone', img='$img', office='$office' WHERE id='$id'");
      if($sql){
        $result['message'] = "Member $oname updated successfully!";
      } else {
        $result['error'] = true;
        $result['message'] = "Failed to update $oname!".$conn->error;
      }
    } else {
      $result['error'] = true;
      $result['message'] = "Failed to add image to local storage!";
    }
  }
  if ($action == 'delete') {
    $id = $_POST['id'];
    $sql = $conn->query("DELETE FROM  Kencon_staff where id='$id'");
    if($sql){
      $result['message'] = "Member deleted successfully!";
    } else {
      $result['error'] = true;
      $result['message'] = "Failed to delete member!";
    }
  }
}

$conn->close();
echo json_encode($result);
?>