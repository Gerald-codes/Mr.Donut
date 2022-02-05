<?php
session_start();
include("../header.php");
include_once("../Database/mysql_conn.php");

if (! isset($_SESSION["ShopperID"])) { // Check if user logged in 
	// redirect to login page if the session variable shopperid is not set
	header ("Location: ../login.php");
	exit;
}
$qry = "SELECT * FROM Shopper WHERE ShopperID = ?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i",$_SESSION["ShopperID"]);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0) {
    $row = $result->fetch_array();
    $name = $row['Name'];
    $email = $row['Email'];
    $pass = $row['Password'];
    $cou = $row["Country"];
    $addr = $row["Address"];
}
else{
    header ("Location: ../login.php");
	exit;
}
?>
<div>
    <form action="" method="post">
        <div class="form-group row">
            <div class="col-sm-4 offset-sm-3">
                <span class="page-title">Update Profile</span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="email">
                Name:
            </label>
            <div class="col-sm-4">
                <?php echo "<b>$name</b>"; ?>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="name">
                New Name:
            </label>
            <div class="col-sm-4">
                <input class="form-control" type="text"
                    name="name" id="name" />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="email">
                Current Email Address:
            </label>
            <div class="col-sm-4">
                <?php echo "<b>$email</b>"; ?>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="email2">
                New Email Address:
            </label>
            <div class="col-sm-4">
                <input class="form-control" type="email"
                    name="email2" id="email2" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-4 offset-sm-3">
                <button type="submit">Update</button>
            </div>
            <?php
            if(isset($_POST['name']) && trim($_POST['name']) != "" ){
                $nm = $_POST["name"];
                $qry = "UPDATE Shopper SET Name=? WHERE ShopperID=?" ;
                $stmt = $conn->prepare($qry);
                $stmt->bind_param("si",$nm,$_SESSION["ShopperID"]);
                $stmt->execute();
                $stmt->close();
                echo "<script>alert('Name updated')</script>";
            }
            
            if (isset($_POST['email2']) && trim($_POST['email2']) != "" ) {
                $email = $_POST['email2'];
                //Validation for email to be added
                $qry = "SELECT * FROM Shopper WHERE Email=?" ;
                $stmt = $conn->prepare($qry);
                $stmt->bind_param("s",$email);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                $value = $result->num_rows;
                if ($result->num_rows > 0) {
                    echo "<p>Email in use</p>";
                }
                else{
                    $qry = "UPDATE Shopper SET Email=? WHERE ShopperID=?" ;
                    $stmt = $conn->prepare($qry);
                    $stmt->bind_param("si",$email,$_SESSION["ShopperID"]);
                    $stmt->execute();
                    $stmt->close();
                    echo "<script>alert('Email updated')</script>";
                }
            } ?>
        </div>
    </form>
    <script type="text/javascript">
    function validateForm()
    {
        // Check if password matched
        if (document.passform.pwd1.value != document.passform.pwd2.value) {
            alert("New passwords not matched!");
            return false;   // cancel submission
        }
        return true;  // No error found
    }
    </script>
    <form name="passform" method="post" onsubmit="return validateForm()">
        <div class="form-group row">
            <div class="col-sm-4 offset-sm-3">
                <span class="page-title">Update Password</span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="email">
                Existing Password:
            </label>
            <div class="col-sm-4">
                <input class="form-control" type="password"
                    name="pwd" id="pwd" required/>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="email">
                New Password:
            </label>
            <div class="col-sm-4">
                <input class="form-control" type="password"
                    name="pwd1" id="pwd1" />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="email">
                Confirm New Password:
            </label>
            <div class="col-sm-4">
                <input class="form-control" type="password"
                    name="pwd2" id="pwd2" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-4 offset-sm-3">
                <button type="submit">Update</button>
            </div>
        </div>
    </form>
    <form action="" method="post">
        <div class="form-group row">
            <div class="col-sm-4 offset-sm-3">
                <span class="page-title">Update Location Information</span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="country">
                Current Country:
            </label>
            <div class="col-sm-4">
                <?php echo "<b>$cou</b>"; ?>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="country">
                New Country:
            </label>
            <div class="col-sm-4">
                <input class="form-control" type="text"
                    name="country" id="country" required />
            </div>
        </div>
        <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="country">
                Current Address:
            </label>
            <div class="col-sm-7">
                <?php echo "<b>$addr</b>"; ?>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="address">
                New Address:
            </label>
            <div class="col-sm-4">
                <input class="form-control" type="text"
                    name="address" id="address" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-4 offset-sm-3">
                <button type="submit">Update</button>
            </div>
        </div>
    </form>
</div>
<?php

if (isset($_POST["pwd"]) && isset($_POST["pwd1"])) {
	$qry = "SELECT * FROM Shopper WHERE ShopperID=?" ;
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i",$_SESSION["ShopperID"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $value = $result->num_rows;
    if ($result->num_rows > 0) {
        $hashed_pwd = $row['Password'];
        if(password_verify($_POST["pwd"],$hashed_pwd)){
            $password = password_hash($_POST['pwd2'],PASSWORD_DEFAULT);
            $qry = "UPDATE Shopper SET Password=? WHERE ShopperID=?" ;
            $stmt = $conn->prepare($qry);
            $stmt->bind_param("si",$password,$_SESSION["ShopperID"]);
            $stmt->execute();
            $stmt->close();
            echo "<script>alert('Password has been updated')</script>";
        }
        else{
            echo "<script>alert('Old Password does not match')</script>";
        }
    }
    else{
        echo "<script>alert('Fatal Error: Shopper ID invalid')</alert>";
        exit;
    }	
}
if(isset($_POST["country"])){
    $cty = $_POST["country"];
    $qry = "UPDATE Shopper SET Country=? WHERE ShopperID=?" ;
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("si",$cty,$_SESSION["ShopperID"]);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Country updated')</script>";
}
if(isset($_POST["address"])){
    $addr = $_POST["address"];
    $qry = "UPDATE Shopper SET Address=? WHERE ShopperID=?" ;
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("si",$addr,$_SESSION["ShopperID"]);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Address updated')</script>";
}

$conn->close();
include("../footer.php"); 
?>