<?php
session_start();
include_once("../Database/mysql_conn.php");
$name = $_POST['name'];
$birthdate = $_POST['birthdate'];
$address = $_POST['address'];
$country = $_POST['country'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = password_hash($_POST['password'],PASSWORD_DEFAULT);
$pwdquestion = $_POST['pwdquestion'];
$pwdanswer = $_POST['pwdanswer'];
$qry = "SELECT * FROM Shopper WHERE Email LIKE ?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("s",$email);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$addNewItem=0;
if(!$result->num_rows>0){
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
        $Message="Registration Successful! <br/>
                Your Shopper ID is $_SESSION[ShopperID] <br/>";
        $_SESSION["ShopperName"] = $name;
}
else{
    $Message = "<h3 style='color:red'>Error: Email has already been registered</h3>";
}
$stmt->close();
}


$conn->close();

include("../header.php");
echo $Message;
include("../footer.php")
?>