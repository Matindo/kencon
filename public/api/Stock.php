<?php
/**  Stock api  **/

include('Connect.php');

$result = array('error'=>false);
$action = '';

if (isset($_GET['action'])) {
  $action = $_GET['action'];
  if ($action == 'read') {
    $sql = $conn->query("SELECT * FROM Kencon_stock");
    $stock = array();
    while($row = $sql->fetch_assoc()){
      array_push($stock, $row);
    }
    $result['stock'] = $stock;
  }
  if ($action == 'create') {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $quantity = mysqli_real_escape_string($conn, $_POST["quantity"]);
    $price = mysqli_real_escape_string($conn, $_POST["price"]);
    $category = mysqli_real_escape_string($conn, $_POST["category"]);
    $subcat = mysqli_real_escape_string($conn, $_POST["subcat"]);
    $upload_dir = '../assets/stock/';
    $upload_file = $upload_dir . basename($_FILES["userimg"]["name"]);
    if (move_uploaded_file($_FILES["userimg"]["tmp_name"], $upload_file)) {
      $img = $upload_file;
      $sql = $conn->query("INSERT INTO Kencon_stock(name, quantity, price, category, subcategory, img) VALUES ('$name', '$quantity', '$price', '$category', '$subcat', '$img')");
      if($sql){
        $result['message'] = "New item $name added successfully!";
      } else {
        $result['error'] = true;
        $result['message'] = "Failed to add new item $name!";
      }
    } else {
      $result['error'] = true;
      $result['message'] = "Failed to add image to local storage!";
    }
  }
  if ($action == 'update') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $quantity = mysqli_real_escape_string($conn, $_POST["quantity"]);
    $price = mysqli_real_escape_string($conn, $_POST["price"]);
    $category = mysqli_real_escape_string($conn, $_POST["category"]);
    $subcat = mysqli_real_escape_string($conn, $_POST["subcat"]);
    $upload_dir = '../assets/stock/';
    $upload_file = $upload_dir . basename($_FILES["userimg"]["name"]);
    if (move_uploaded_file($_FILES["userimg"]["tmp_name"], $upload_file)) {
      $img = $upload_file;
      $sql = $conn->query("UPDATE Kencon_stock SET name='$name', quantity='$quantity', price='$price', category='$category', subcategory='$subcat', img='$img' WHERE id='$id'");
      if($sql){
        $result['message'] = "Item $name updated successfully!";
      } else {
        $result['error'] = true;
        $result['message'] = "Failed to update $name!";
      }
    } else {
      $result['error'] = true;
      $result['message'] = "Failed to add image to local storage!";
    }
  }
  if ($action == 'delete') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $sql = $conn->query("DELETE FROM  Kencon_stock where id='$id'");
    if($sql){
      $result['message'] = "Item deleted successfully!";
    } else {
      $result['error'] = true;
      $result['message'] = "Failed to delete item!";
    }
  }
}

$conn->close();
echo json_encode($result);
?>