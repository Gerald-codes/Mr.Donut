<?php 
session_start(); // Detect the current session
include("../header.php"); // Include the Page Layout header
?>
<div style="width:80%; margin:auto;">
<form method="post">
	<div class="form-group row">
		
	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label" for="eMail">
         Email:</label>
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
$stmt->bind_param("s", $_SESSION[]); 	// "s" - string 
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0) {
	$row = $result->fetch_array();
	$qn = $row["PwdQuestion"];
	echo "<form method='post'>";
	echo '<div class="form-group row"><label>Security Question:</label></div>';
	echo '<div class="form-group row"><label class="col-sm-3 col-form-label" for="answer">Security Answer: $qn</label><div class="col-sm-9">';
	echo '<input class="form-control" name="answer" id="answer" type="text" required /></div></div>';
	// Process after user click the submit button
	if (isset($_POST["answer"])) {
		// Read email address entered by user
		$answer = $_POST["answer"];
		// Retrieve shopper record based on e-mail address
		include_once("mysql_conn.php");
		$qry = "SELECT * FROM Shopper WHERE PwdAnswer LIKE ?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("s", $answer); 	// "s" - string 
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		if ($result->num_rows > 0) {
			// To Do 1: Update the default new password to shopper"s account
			$row = $result->fetch_array();
			$shopperId = $row["ShopperID"];
			$new_pwd = "password";
			$hashed_pwd = password_hash($new_pwd, PASSWORD_DEFAULT);
			$qry = "UPDATE Shopper SET Password=? WHERE ShopperID=?";
			$stmt = $conn->prepare($qry);
			$stmt->bind_param("si", $hashed_pwd, $shopperId);
			$stmt->execute();
			$stmt->close();
			include("myMail.php");
			$to="@gmail.com";
			$from="@gmail.com";
			$from_name="DONUT SHOP NAME";
			$subject="DONUT SHOP NAME Login Password";
			$body="<span style='color:black; font-size:l2px'> 
			Your new password is <span style='font-weight:bold'> 
			$new_pwd</span>.<br /> 
			Do change this default password.</span>";
			if(smtpmailer($to, $from, $from_name, $subject, $body)) {
				echo "<p>Your new password is sent to: 
				<span style='font-weight:bold'>$to</span>.</p>"; 
			} 
			else { 
				echo "<p><span style='color:red;'> Mailer Error: " . $error . "</span></p>"; 
				}
		}
		else {
			echo "<p><span style='color:red;'>
				Wrong E-mail address!</span></p>";
		}
		$conn->close();
	}
}
else{
	echo "<p>Invalid Email Address</p>";
}
?>

</div> <!-- Closing container -->
<?php 
include("../footer.php"); // Include the Page Layout footer
?>