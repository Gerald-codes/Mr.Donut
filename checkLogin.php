<?php
// Detect the current session
session_start();
// Include the Page Layout header
// include("indexHeader.php"); 
include_once("Database/mysql_conn.php");

// Reading inputs entered in previous page
$email = $_POST["email"];
$pwd = $_POST["password"];

// To Do 1 (Practical 2): Validate login credentials with database
$qry = "SELECT * FROM Shopper WHERE Email=? ";
$stmt = $conn->prepare($qry);
$stmt->bind_param('s',$email);
$stmt->execute();
$result = $stmt->get_result();
$isLoggedIn= FALSE;
if ($result->num_rows > 0){
	$row = $result->fetch_array();
	$hashed_pwd = $row["Password"];
	if (password_verify($pwd,$hashed_pwd) == true) {
		// Save user's info in session variables
		$_SESSION["ShopperName"] = $row["Name"];
		$_SESSION["ShopperID"] = $row["ShopperID"];
		$isLoggedIn= TRUE;
		// Get active shopping cart
		$qry = "SELECT sc.ShopCartID, COUNT(sci.ProductID) AS NumItems
				FROM ShopCart sc LEFT JOIN ShopCartItem sci 
				ON sc.ShopCartID=sci.ShopCartID 
				WHERE sc.ShopperID=? AND sc.OrderPlaced=0";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("i", $_SESSION["ShopperID"]);
		$stmt->execute();
		$result2 = $stmt->get_result();
		$row2 = $result2->fetch_array();
		$_SESSION["Cart"] = $row2["ShopCartID"];
		$_SESSION["NumCartItem"] = $row2["NumItems"];
		// Release the resource allocated for prepared statement
		$stmt->close();
		// Redirect to home page
		header("Location: index.php");
		exit;
	}
	if ($row["Password"] == $pwd){
		// Save user's info in session variables
		$_SESSION["ShopperName"] = $row["Name"];
		$_SESSION["ShopperID"] = $row["ShopperID"];
		$isLoggedIn= TRUE;
		// Get active shopping cart
		$qry = "SELECT sc.ShopCartID, COUNT(sci.ProductID) AS NumItems
				FROM ShopCart sc LEFT JOIN ShopCartItem sci 
				ON sc.ShopCartID=sci.ShopCartID 
				WHERE sc.ShopperID=? AND sc.OrderPlaced=0";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("i", $_SESSION["ShopperID"]);
		$stmt->execute();
		$result2 = $stmt->get_result();
		$row2 = $result2->fetch_array();
		$_SESSION["Cart"] = $row2["ShopCartID"];
		$_SESSION["NumCartItem"] = $row2["NumItems"];
		// Release the resource allocated for prepared statement
		$stmt->close();
		// Redirect to home page
		header("Location: index.php");
		exit;
	}
	else{
		echo  "<h3 style='color:red'>Invalid Login Credentials</h3>";
	}
}
else{
	echo  "<h3 style='color:red'>Invalid Login Credentials</h3>";
}


// Close database connection
$conn->close();

// Include the Page Layout footer
include("footer.php");
?>