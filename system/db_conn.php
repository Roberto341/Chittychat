<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
  }
$dbhost= "localhost";
$dbuser= "root";
$dbpass = "";
$dbname = "chat";

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

$mysqli = @new mysqli($dbhost, $dbuser, $dbpass, $dbname);
// PDO  
$conn2 = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
try{
    $db = new PDO("mysql:dbhost=$dbhost; dbname=$dbname", "$dbuser", "$dbpass");
}catch(PDOException $e){
    echo $e->getMessage();
}
?>