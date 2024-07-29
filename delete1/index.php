<?php
include '../connect.php';
if(isset($_GET['id'])){
    $id= $_GET['id'];
    $deletedAt=time();

    $sql="UPDATE employees SET isDeleted=1 ,deletedAt=$deletedAt where id=$id ";
    $result=mysqli_query($con,$sql);
    if($result){
        // echo "Deleted successfully";
        header('location:../listusers');
       
    }
    else{
        die(mysqli_error($con));
    }
}
?>