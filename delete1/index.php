<?php
include '../connect.php';
if(isset($_GET['id'])){
    $id= $_GET['id'];
    $deletedAt=time();

    $sql="UPDATE em_users SET user_isDeleted=1 ,user_deletedAt=$deletedAt where user_id=$id ";
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