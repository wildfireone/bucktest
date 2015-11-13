<?php
require_once "database.inc.php";
        
function dbConnect() {
    global $host, $mysqldatabase, $mysqlusername, $mysqlpassword;
    try {
      $conn = new PDO("mysql:host=$host;dbname=$mysqldatabase", $mysqlusername, $mysqlpassword);
      $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
      return $conn;
    } catch (PDOException $e) {
      echo 'Cannot connect to database';
      exit;
    }
}

function dbClose(&$conn){
    $conn = null;
}
?>
