<?php 

    // database connection
    require_once "../connection.php";
    $id = $_GET["id"];

    $sql = "DELETE FROM notice WHERE id = '$id' ";
    $result= mysqli_query($conn , $sql);
    if($result){
        header("Location: noticeboard.php?delete-success-id=" .$id);
    }
?>