<?php
session_start();
include_once("../Database/mysql_conn.php");
include("../header.php");//display header
//Retrieving from form in register.php
$name = $_POST['name'];
$birthdate = $_POST['birthdate'];
$address = $_POST['address'];
$country = $_POST['country'];
$phone = $_POST['phone'];
//lowercase to make the input less case sensitive upon login
$email = strtolower($_POST['email']);
//password hashed for security
$password = password_hash($_POST['password'],PASSWORD_DEFAULT);
$pwdquestion = $_POST['pwdquestion'];
$pwdanswer = $_POST['pwdanswer'];
//check for duplicates in database (LOWER() sets all emails to lowercase for easier comparison
$qry = "SELECT *,LOWER(Email) FROM Shopper WHERE Email=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s",$email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$addNewItem=0;
$rowno = intval($result->num_rows);
if($rowno>0){
        //Error message if there are results
        echo "<div class='container'><h3 style='color:red'>Error: Email has already been registered</h3><br/>";
        echo "<h3><a href='register.php>Back to Registration</a></h3></div>";
}
else{
        //Insert register data into database
        $qry = "INSERT INTO Shopper (Name, Birthdate, Address, Country, Phone, Email, Password,PwdQuestion,PwdAnswer)
        VALUES(?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("sssssssss",$name,$birthdate,$address,$country,$phone,$email,$password,$pwdquestion,$pwdanswer);
        if($stmt->execute()){
                $qry="SELECT LAST_INSERT_ID() AS ShopperID";
                $result = $conn->query($qry);
                while ($row = $result -> fetch_array()) {
                        $_SESSION["ShopperID"]=$row["ShopperID"];
        }
        //Success message
        echo "Registration Successful! <br/>
                Your Shopper ID is $_SESSION[ShopperID] <br/>";
        //Name on header
        $_SESSION["ShopperName"] = $name;
}
$stmt->close();
}

//display footer
$conn->close();
include("../footer.php")
?>