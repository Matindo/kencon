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
    $name = $_POST["name"];
    $quantity = $_POST["quantity"];
    $price = $_POST["price"];
    $category = $_POST["category"];
    $subcat = $_POST["subcategory"];
    $upload_dir = '../assets/stock/';
    if(is_uploaded_file($_FILES["img"]["tmp_name"])){
      $upload_file = $upload_dir . basename($_FILES["img"]["name"]);
      if(move_uploaded_file($_FILES["img"]["tmp_name"], $upload_file)) {
        $img = $upload_file;
        $sql = $conn->query("INSERT INTO Kencon_stock(name, quantity, price, category, sub_category, img) VALUES ('$name', '$quantity', '$price', '$category', '$subcat', '$img')");
        if($sql){
          $result['message'] = "New item $name added successfully!";
        } else {
          $result['error'] = true;
          $result['message'] = "Failed to add new item $name!".$conn->error;
        }
      } else {
        $result['error'] = true;
        $result['message'] = "Failed to add image to local storage!";
      }
    } else {
      $result['error'] = true;
      $result['message'] = "No file to upload!";
    }
  }
  if ($action == 'update') {
    $id = $_POST['id'];
    $name = $_POST["name"];
    $quantity = $_POST["quantity"];
    $price = $_POST["price"];
    $category = $_POST["category"];
    $subcat = $_POST["subcategory"];
    $upload_dir = '../assets/stock/';
    $upload_file = $upload_dir . basename($_FILES["img"]["name"]);
    if (move_uploaded_file($_FILES["img"]["tmp_name"], $upload_file)) {
      $img = $upload_file;
      $sql = $conn->query("UPDATE Kencon_stock SET name='$name', quantity='$quantity', price='$price', category='$category', sub_category='$subcat', img='$img' WHERE id='$id'");
      if($sql){
        $result['message'] = "Item $name updated successfully!";
      } else {
        $result['error'] = true;
        $result['message'] = "Failed to update $name!".$conn->error;
      }
    } else {
      $result['error'] = true;
      $result['message'] = "Failed to add image to local storage!";
    }
  }
  if ($action == 'delete') {
    $id = $_POST['id'];
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