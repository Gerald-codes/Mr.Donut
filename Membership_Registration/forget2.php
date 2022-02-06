<?php
session_start();
include_once("../Database/mysql_conn.php");
include("../header.php");
// Read email address entered by user
$answer = strtolower($_POST["answer"]);
// Retrieve shopper record based on e-mail address
include_once("../Database/mysql_conn.php");
$qry = "SELECT * FROM Shopper WHERE LOWER(PwdAnswer) LIKE ?";//password answer is lowercase with database for easier matching
$stmt = $conn->prepare($qry);
$stmt->bind_param("s", $answer); 	// "s" - string 
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0) {//if the answer is valid
    //Update the default new password to shopper's account
    $row = $result->fetch_array();
    $shopperId = $row["ShopperID"];
    $new_pwd = "password";
    $hashed_pwd = password_hash($new_pwd, PASSWORD_DEFAULT);
    $qry = "UPDATE Shopper SET Password=? WHERE ShopperID=?";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("si", $hashed_pwd, $shopperId);
    $stmt->execute();
    echo"<span class='page-title'>Password has been reset</span>";
    echo"<p>Your new password is <b>$new_pwd</b>, please update ASAP for security reasons</p>";
}
else {
    echo "<span class='page-title' style='color:red;'>
        Wrong Answer!</span>";
}
$conn->close();
include("../footer.php")
?>