<?php 

    // database connection
    require_once "../connection.php";
    $id = $_GET["id"];

    $sql = "DELETE FROM task WHERE id = '$id' ";
    $result= mysqli_query($conn , $sql);
    if($result){
        header("Location: list-project.php?delete-success-id=" .$id);
    }
?>