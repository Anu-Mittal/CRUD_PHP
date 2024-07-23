<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$con = new mysqli('localhost', 'root', 'Arcs@123', 'CrudOperations');

if (!$con) {
    die(mysqli_error($con));
} 
?>
