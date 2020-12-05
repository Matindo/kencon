<?php
/**  Sales api  **/

include('Connect.php');

$result = array('error'=>false);
$action = '';

if (isset($_GET['action'])) {
  $action = $_GET['action'];
  if ($action == 'read') {
    $sql = $conn->query("SELECT * FROM Kencon_sales");
    $sales = array();
    while($row = $sql->fetch_assoc()){
      array_push($sales, $row);
    }
    $result['sales'] = $sales;
  }
  if ($action == 'create') {
    $item = $_POST['item'];
    $salesperson = $_POST['salesperson'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $sql = $conn->query("INSERT INTO Kencon_sales(item, salesperson, quantity, price, datestamp) VALUES ('$item', '$salesperson', $quantity, $price)");
    if($sql){
      $result['message'] = "Sale added successfully!";
    } else {
      $result['error'] = true;
      $result['message'] = "Failed to add new sale!";
    }
  }
  if ($action == 'delete') {
    $id = $_POST['id'];
    $sql = $conn->query("DELETE FROM  Kencon_sales where id='$id'");
    if($sql){
      $result['message'] = "Sale deleted successfully!";
    } else {
      $result['error'] = true;
      $result['message'] = "Failed to delete sale";
    }
  }
}

$conn->close();
echo json_encode($result);
?>