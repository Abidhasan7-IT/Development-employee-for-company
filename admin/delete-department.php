<?php 

require_once "../connection.php";

$id =  $_GET["id"];

$sql = "DELETE FROM department WHERE id = $id ";

mysqli_query($conn , $sql); 

header("Location: department.php?delete-success-where-id=" .$id );


?>
