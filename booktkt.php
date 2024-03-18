

<?php 
//20ucc114 , 20ucc116 
session_start();
	if(empty($_SESSION['user_info'])){
		echo "<script type='text/javascript'>alert('Please login before proceeding further!');</script>";
	}
$conn = mysqli_connect("localhost:3308","root","","railway");
if(!$conn){  
	echo "<script type='text/javascript'>alert('Database failed');</script>";
  	die('Could not connect: '.mysqli_connect_error());  
}
if (isset($_POST['submit']))
{
$trains=$_POST['trains'];
$pnr = generateUniquePNR($conn);
$p_id= getp_id($conn);

$sql_train = "SELECT t_status, t_fare FROM trains WHERE t_name = '$trains'";
    $result_train = mysqli_query($conn, $sql_train);
    $row_train = mysqli_fetch_assoc($result_train);
    $status = $row_train['t_status'];
    $fare = $row_train['t_fare'];


$sql = "SELECT t_no FROM trains WHERE t_name = '$trains'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$email=$_SESSION['user_info'];
$update_passengers_query="UPDATE passengers SET t_no='$row[t_no]' WHERE email='$email';";

if(mysqli_query($conn, $update_passengers_query))
    {  
        // Insert ticket details into 'tickets' table
        $insert_ticket_query = "INSERT INTO tickets (PNR, t_status, t_fare, p_id) VALUES ('$pnr', '$status', '$fare', '$p_id');";
        if(mysqli_query($conn, $insert_ticket_query)) {
            $message = "Ticket booked successfully. Your PNR is: $pnr";
        } else {
            $message = "Ticket booking failed";
        }
    } else {
        $message = "Transaction failed";
    }
    
    echo "<script type='text/javascript'>alert('$message');</script>";
}


function generateUniquePNR($conn) {
    $unique = false;
    $pnr = null;
    while (!$unique) {
        $pnr = mt_rand(100000, 999999);
        $check_query = "SELECT COUNT(*) AS count FROM tickets WHERE PNR = '$pnr'";
        $result = mysqli_query($conn, $check_query);
        $row = mysqli_fetch_assoc($result);
        if ($row['count'] == 0) {
            $unique = true;
        }
    }
    return $pnr;
}

function getp_id($conn){
	$email = $_SESSION['user_info'];
    
    // Fetch the passenger ID based on the email
    $sql_passenger_id = "SELECT p_id FROM passengers WHERE email = '$email'";
    $result_passenger_id = mysqli_query($conn, $sql_passenger_id);
    $row_passenger_id = mysqli_fetch_assoc($result_passenger_id);
    $passenger_id = $row_passenger_id['p_id'];

	return $passenger_id;
}
                                                                        //20ucc114 , 20ucc116 

?>
<!DOCTYPE html>
<html>
<head>
	<title>Book a ticket</title>
	<LINK REL="STYLESHEET" HREF="STYLE.CSS">
	<style type="text/css">
		#booktkt	{
			margin:auto;
			margin-top: 50px;
			width: 40%;
			height: 60%;
			padding: auto;
			padding-top: 50px;
			padding-left: 50px;
			background-color: rgba(0,0,0,0.3);
			border-radius: 25px;
		}
		html { 
		  background: url(img/bg7.jpg) no-repeat center center fixed; 
		  -webkit-background-size: cover;
		  -moz-background-size: cover;
		  -o-background-size: cover;
		  background-size: cover;
		}
		#journeytext	{
			color: white;
			font-size: 28px;
			font-family:"Comic Sans MS", cursive, sans-serif;
		}
		#trains	{
			margin-left: 90px;
			font-size: 15px;
		}
		#submit	{
			margin-left: 150px;
			margin-bottom: 40px;
			margin-top: 30px
		}
	</style>
	<script type="text/javascript">
		function validate()	{
			var trains=document.getElementById("trains");
			if(trains.selectedIndex==0)
			{
				alert("Please select your train");
				trains.focus();
				return false;		
			}
		}
	</script>
</head>
<body>
	<?php
		include ('header.php');
	?>
	<div id="booktkt">
	<h1 align="center" id="journeytext">Choose your journey</h1><br/><br/>
	<form method="post" name="journeyform" onsubmit="return validate()">
		<select id="trains" name="trains" required>
			<option selected disabled>-------------------Select trains here----------------------</option>
			<option value="rajdhani" >Rajdhani Express - Mumbai Central to Delhi</option>
			<option value="duronto" >Duronto Express - Mumbai Central to Ernakulum</option>
			<option value="geetanjali">Geetanjali Express - CST to Kolkata</option>
			<option value="garibrath" >Garib Rath - Udaipur to Jammu Tawi</option>
			<option value="mysoreexp" >Mysore Express - Talguppa to Mysore Jn</option>
		</select>
		<br/><br/>
		<input type="submit" name="submit" id="submit" class="button" />
	</form>
	</div>
	</body>
	</html>

	