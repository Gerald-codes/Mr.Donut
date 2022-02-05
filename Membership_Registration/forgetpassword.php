<?php 
session_start(); // Detect the current session
include("../header.php"); // Include the Page Layout header
include_once("../Database/mysql_conn.php");
?>
<div class="container" style="width:80%; margin:auto;">
<h2 style="text-align:center">Forget Password</h2>
<form method="post" >
	<div class="form-group row">
		<label class="col-sm-3 col-form-label" for="eMail">
          Insert Email:</label>
		<div class="col-sm-9">
			<input class="form-control" name="eMail" id="eMail"
                   type="email" required />
		</div>
	</div>
	<div class="form-group row">      
		<div class="col-sm-9 offset-sm-3">
			<button type="submit">Submit</button>
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
	echo "<div class='form-group row'><label class='col-sm-5 col-form-label'>Security Question: $qn</label></div>";
	echo "<div class='form-group row'><label class='col-sm-3 col-form-label' for='answer'>Security Answer:</label><div class='col-sm-9'>";
	echo '<input class="form-control" name="answer" id="answer" type="text" required /></div></div>';
	echo '<div class="form-group row"><button type="submit" class="btn-default">Reset Password</button></div>';
}
else{
	echo "<script>alert(Invalid Email Address)</script>";
}
?>

</div> <!-- Closing container -->
<?php 
include("../footer.php"); // Include the Page Layout footer
?>