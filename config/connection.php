<?php 
$host = "localhost";
$user = "root";
$password = "";
$db = "pms_db";
try {

  $con = new PDO("mysql:dbname=$db;port=3307;host=$host", 
  	$user, $password);
  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
} catch(PDOException $e) {
  echo "Connection failed: ".
   $e->getMessage();
  echo $e->getTraceAsString();
  exit;
}

session_start();

?>