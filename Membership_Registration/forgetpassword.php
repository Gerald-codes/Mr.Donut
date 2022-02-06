<?php 
session_start(); // Detect the current session
include("../header.php"); // Include the Page Layout header
include_once("../Database/mysql_conn.php");
?>
<div class="container" style="width:80%; margin:auto;">
<span class="page-title">Password Reset</span>
<form method="post" >
	<div class="form-group row">
		<label class="col-sm-3 col-form-label" for="eMail">
          Insert Email:</label>
		<div class="col-sm-5">
			<input class="form-control" name="eMail" id="eMail"
                   type="email" required />
		</div>
	</div>
	<div class="form-group row">      
		<div class="col-sm-9 offset-sm-3">
			<button class="btn btn-primary" style="background-color: #f89ec9; border-color: #f89ec9" type="submit">Submit</button>
		</div>
	</div>
</form>
<?php 
$qry = "SELECT * FROM Shopper WHERE Email LIKE ?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s", $_POST['eMail']);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0) {
	$row = $result->fetch_array();
	$qn = $row["PwdQuestion"];
	echo "<form action='forget2.php' method='post'>";
	echo "<div class='form-group row'><label class='col-sm-3 col-form-label'>Security Question:</label><label class='col-sm-5 col-form-label'>$qn</label></div>";
	echo "<div class='form-group row'><label class='col-sm-3 col-form-label' for='answer'>Security Answer:</label><div class='col-sm-5'>";
	echo '<input class="form-control" name="answer" id="answer" type="text" required /></div></div>';
	echo '<div class="form-group row"><div class="col-sm-3"></div><div><button type="submit" class="btn btn-primary" style="background-color: #f89ec9; border-color: #f89ec9" class="btn-default">Reset Password</button></div></div>';
}
else{
	echo "<script>alert('Invalid Email Address')</script>";
}
?>

</div> <!-- Closing container -->
<?php 
include("../footer.php"); // Include the Page Layout footer
?>