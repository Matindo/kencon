<?php
//Users api//

$host = "localhost"; 
$user = "root"; 
$password = ""; 
$dbname = "essaybud_Kencon";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo("success");
?>
