<?php
                                         //20ucc114 , 20ucc116 


session_start();

	$conn = mysqli_connect("localhost:3308","root","","railway");

if(!$conn){  
	echo "<script type='text/javascript'>alert('Database failed');</script>";
  	die('Could not connect: '.mysqli_connect_error());  
}
session_start();
session_destroy();
unset($_SESSION["email"]);
unset($_SESSION["pw"]);
header("Location:login.php");
                                            //20ucc114 , 20ucc116 

?>