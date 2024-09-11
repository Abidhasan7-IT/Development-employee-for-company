<?php 

require_once "../connection.php";

$id =  $_GET["id"];

$sql = "DELETE FROM tblleavetype WHERE Id = $id ";

mysqli_query($conn , $sql); 

header("Location: add-leavetype.php?delete-success-where-Id=" .$id );


?>