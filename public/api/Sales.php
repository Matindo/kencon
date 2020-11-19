<?php
//Sales api//

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
      if ($_GET['id']) {
        $id = $_GET['id'];
	$sql = "select * from Kencon_sales where id=$id";
      } elseif ($_GET['salesperson']) {
        $sp = $_GET['salesperson'];
        $sql = "select * from Kencon_sales where salesperson=$sp";
      } elseif ($_GET['item']) {
        $item = $_GET['item'];
        $sql = "select * from Kencon_sales where item_name='$item'";
      } else {
        $sql = "select * from Kencon_sales";
      }
      break;
    case 'POST':
      $item = $_POST["item"];
      $quantity = $_POST["quantity"];
      $salesperson = $_POST["salesperson"];
      $time = $_POST["time"];
      $price = $_POST["price"];

      $sql = "insert into Kencon_sales (item_name, quantity, salesperson, time, price) values ('$item', $quantity, '$salesperson', '$time', $price)"; 
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
