<?php
//Stock api//

$host = "http://localhost:3306/"; 
$user = "essaybud_essaybud"; 
$password = "su@localz0n3"; 
$dbname = "essaybud_Kencon"; 
$id = '';
$sql = '';

$con = mysqli_connect($host, $user, $password,$dbname);

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
//$input = json_decode(file_get_contents('php://input'),true);


if (!$con) {
  die("Connection failed: " . mysqli_connect_error());
}


switch ($method) {
    case 'GET':
      $id = $_GET['id'];
      $sql = "select * from Kencon_stock".($id?" where id=$id":''); 
      break;
    case 'POST':
      $name = $_POST["name"];
      $quantity = $_POST["quantity"];
      $price = $_POST["price"];
      $category = $_POST["category"];
      $subcat = $_POST["subcat"];
      $upload_dir = 'uploads/';
      $upload_file = $upload_dir . basename($_FILES["itemimg"][
"name"]);                                                             if (move_uploaded_file($_FILES["itemimg"]["tmp_name"], $upload_file)) {                                                          $img = $upload_file
$sql = "insert into Kencon_stock (name, quantity, price, img, category, sub_category) values ('$name', $quantity, $price, '$img', '$category', '$subcat')".($id?" where id=$id":'');
      }
      break;
}

// run SQL statement
$result = mysqli_query($con,$sql);

// die if SQL statement failed
if (!$result) {
  http_response_code(404);
  die(mysqli_error($con));
}

if ($method == 'GET') {
    if (!$id) echo '[';
    for ($i=0 ; $i<mysqli_num_rows($result) ; $i++) {
      echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
    }
    if (!$id) echo ']';
  } elseif ($method == 'POST') {
    echo json_encode($result);
  } else {
    echo mysqli_affected_rows($con);
  }

$con->close();

?>
