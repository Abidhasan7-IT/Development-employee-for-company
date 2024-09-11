<?php 
    
   echo $id = $_GET["id"];

   require_once "../connection.php";
   $sql = "UPDATE task SET status = 'Done', sub_date = NOW() WHERE id = '$id' ";
   $result = mysqli_query($conn , $sql);
   if($result){
       header("Location: tasksubmit.php?accept-successfuly");
   }

?>