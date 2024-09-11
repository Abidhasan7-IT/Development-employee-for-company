<?php 

    // database connection
    require_once "../connection.php";
    $id = $_GET["id"];

    $sql = "DELETE FROM schedule WHERE s_id = '$id' ";
    $result= mysqli_query($conn , $sql);
    if($result){
        header("Location: schedule.php?delete-success-id=" .$id);
    }
?>