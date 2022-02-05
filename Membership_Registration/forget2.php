<?php
session_start();
include_once("../Database/mysql_conn.php");
include("../header.php");
// Read email address entered by user
$answer = strtolower($_POST["answer"]);
// Retrieve shopper record based on e-mail address
include_once("../Database/mysql_conn.php");
$qry = "SELECT * FROM Shopper WHERE LOWER(PwdAnswer) LIKE ?";
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
    include("myMail.php");
    $to=$row['Email'];
    $from="donut12733889@gmail.com";
    $from_name="Mr Donut";
    $subject="Mr Donut Login Password";
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
        Wrong Answer!</span></p>";
}

$stmt->close();
$conn->close();
include("../footer.php")
?>