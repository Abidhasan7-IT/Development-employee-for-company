<?php 

$id = $_GET["id"];

require_once "../connection.php";

$sql = "UPDATE task SET status = 'canceled' WHERE id = '$id' ";
$result = mysqli_query($conn , $sql);
if($result){
    header("Location: task.php?cancel-successfuly");
}

?>