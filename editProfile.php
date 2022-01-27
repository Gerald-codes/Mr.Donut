<?php
session_start();
include("indexHeader.php");

if (! isset($_SESSION["ShopperID"])) { // Check if user logged in 
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}
$qry = "SELECT * FROM Shopper WHERE ShopperID = ?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i",$_SESSION["ShopperID"]);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0) {

}
else{
    header ("Location: login.php");
	exit;
}
?>
<div>
    <form action="" method="post">
        <div class="form-group row">
            <div class="col-sm-9 offset-sm-3">
                <span class="page-title">Change Email Address</span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="email">
                Existing Email Address:
            </label>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="email">
                New Email Address:
            </label>
            <div class="col-sm-9">
                <input class="form-control" type="email2"
                    name="email" id="email" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-9 offset-sm-3">
                <button type="submit">Update</button>
            </div>
        </div>
    </form>
</div>
<div>
    <script type="text/javascript">
    function validateForm()
    {
        // Check if password matched
        if (document.passform.pwd1.value != document.passform.pwd2.value) {
            alert("Passwords not matched!");
            return false;   // cancel submission
        }
        return true;  // No error found
    }
    </script>
    <form name="passform" method="post" onsubmit="return validateForm()">
        <div class="form-group row">
            <div class="col-sm-9 offset-sm-3">
                <span class="page-title">Update Password</span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="email">
                Existing Password:
            </label>
            <div class="col-sm-9">
                <input class="form-control" type="password"
                    name="pwd" id="pwd" />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="email">
                New Password:
            </label>
            <div class="col-sm-9">
                <input class="form-control" type="password"
                    name="pwd1" id="pwd1" />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="email">
                Confirm New Password:
            </label>
            <div class="col-sm-9">
                <input class="form-control" type="password"
                    name="pwd2" id="pwd2" />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-9 offset-sm-3">
                <button type="submit">Update</button>
            </div>
        </div>
    </form>
</div>
<div>
    <form action="" method="post">
        <div class="form-group row">
            <div class="col-sm-9 offset-sm-3">
                <span class="page-title">Update Location Information</span>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="email">
                Existing Country:
            </label>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="email">
                New Country:
            </label>
            <div class="col-sm-9">
                <input class="form-control" type="text"
                    name="country" id="country" required />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="email">
                Existing Address:
            </label>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="email">
                New Address:
            </label>
            <div class="col-sm-9">
                <input class="form-control" type="text"
                    name="country" id="country" required />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-9 offset-sm-3">
                <button type="submit">Update</button>
            </div>
        </div>
    </form>
</div>
<?php
if (isset($_POST['email']) && trim($_POST['email']) != "" ) {
    $email = $_POST['email'];
    //Validation for email to be added
    $qry = "UPDATE Shopper SET EmailAddress=? WHERE ShopperID LIKE ?" ;
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("si",$email,$_SESSION["ShopperID"]);
    $stmt->execute();
    $stmt->close();
}

if (isset($_POST["pwd1"])) {
	// To Do 2: Read new password entered by user
    password_verify($pwd,$hashed_pwd);
	$qry = "SELECT * FROM Shopper WHERE ShopperID LIKE ?" ;
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i",$_SESSION["ShopperID"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    $value = $result->num_rows;
    if ($result->num_rows > 0) {
        $hashed_pwd = $row['Password'];
        if(password_verify($_POST["pwd1"],$hashed_pwd)){
            $password = password_hash($_POST['pwd2'],PASSWORD_DEFAULT);
            $qry = "UPDATE Shopper SET Password=? WHERE ShopperID LIKE ?" ;
            $stmt = $conn->prepare($qry);
            $stmt->bind_param("s",$password);
            $stmt->execute();
            $stmt->close();
        }
        else{

        }
    }
    else{

    }
	
}

$conn->close();
// Include the Page Layout footer
include("footer.php"); 
?>