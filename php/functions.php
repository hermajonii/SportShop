<?php
session_start();
require_once("User.php");
function getConnection(){
    $con=mysqli_connect("localhost", "root", "", "shop");
    if(mysqli_connect_error())
    {
        echo "Connect to database failed!";
        exit();
    }
    else {
        mysqli_query($con, "SET NAMES utf8");
        return $con;
    }
}
function closeConnection($con){
    if($con)
        mysqli_close($con);
}
function getCategories(){
    $con= getConnection();
    if($con){
        $result=mysqli_query($con, "SELECT * FROM view_showcategories");
        if(!mysqli_error($con))
            return $result;
        else 
            echo "";
        closeConnection($con);
    }
}
function getBrands(){
    $con=getConnection();
    if($con){
        $result=mysqli_query($con, "SELECT * FROM view_showbrands");
        if(!mysqli_error($con))
            return $result;
        else 
            echo "";
        closeConnection($con);
    }
}
?>