<?php 
    
   echo $id = $_GET["id"];

   require_once "../connection.php";
   $sql = "UPDATE task SET status = 'progress' WHERE id = '$id' ";
   $result = mysqli_query($conn , $sql);
   if($result){
       header("Location: task.php?progress-successfuly");
   }

?>