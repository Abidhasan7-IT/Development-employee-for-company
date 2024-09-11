<?php 

require_once "../connection.php";

$id =  $_GET["id"];

$sql = "DELETE FROM emp_leave WHERE id = $id ";

mysqli_query($conn , $sql); 

header("Location: manage-leave.php?delete-success-where-id=" .$id );


?>
