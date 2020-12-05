<?php
/** Connect api **/

$host = "localhost"; 
$user = "essaybud_essaybud"; 
$password = "su@localz0n3"; 
$dbname = "essaybud_Kencon";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
