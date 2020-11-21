<?php
/** Connect api **/

$host = "localhost"; 
$user = "root"; 
$password = "BySonic98"; 
$dbname = "essaybud_Kencon";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
