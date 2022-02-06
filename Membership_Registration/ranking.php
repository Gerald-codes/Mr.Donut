<?php 
session_start();
include("../header.php");
include_once("../Database/mysql_conn.php");
if (! isset($_SESSION["ShopperID"])) { // Check if user logged in 
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}
?>
<div class='container' style='width:90%; margin:auto; display:block;'>
    <h1 style="text-align:center;">Rate this donut!</h1>
    <div class="row">
        <div class="col-sm-6">
            <?php 
            $pid=$_GET["pid"];
            $qry = "SELECT * from Product where ProductID=?";//get product from database
            $stmt = $conn->prepare($qry);
            $stmt->bind_param("i", $pid); 	// "i" - integer 
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            while($row=$result->fetch_array()){//show product information
                $img = "../Images/Products/$row[ProductImage]";
                echo "<h2>$row[ProductTitle]</h2><br/>";
                echo "<p><img src=$img /></p><br/>";
                echo "<p>$row[ProductDesc]</p>";
            }
            ?>
            <p></p><br/>
        </div>
        <div class="col-sm-6">
            <form method="post">
                <div class="form-group row"><input type="radio" name="rate" value="1">1 (Bad)</div>
                <div class="form-group row"><input type="radio" name="rate" value="2">2</div>
                <div class="form-group row"><input type="radio" name="rate" value="3">3 (OK)</div>
                <div class="form-group row"><input type="radio" name="rate" value="4">4</div>
                <div class="form-group row"><input type="radio" name="rate" value="5">5 (Excellent)</div>
                <div class="form-group row">
                    <textarea id="comment" name="comment" placeholder="Add your comment" rows="4" cols="50"></textarea>
                </div>
                <div class="form-group row">
                    <button type="submit" class="btn-primary">Rate!</button>
                </div>
            </form>
            <?php 
                if(isset($_POST["rate"])){ //if a rating is selected
                    $ratevalue = (int)$_POST["rate"];//getting the rating value
                    $sid = $_SESSION["ShopperID"];
                    $comment = "";//empty string
                    if(isset($_POST["comment"])){
                        $comment = $_POST["comment"];//fill empty string
                    }
                    $qry = "INSERT INTO Ranking (ShopperID,ProductID,Rank,Comment)
                    VALUES(?,?,?,?)";
                    $stmt = $conn->prepare($qry);
                    $stmt->bind_param("iiis",$sid,$pid,$ratevalue,$comment);
                    if($stmt->execute()){ 
                        echo "<p>Thanks for your rating</p>";
                    }
                    else{
                        echo "<p>Error: Can't rate</p>";
                    }
                }
                else{
                    echo "<script>alert('Please select a rating')</script>";
                }
            ?>
        </div>
    </div>
</div>
