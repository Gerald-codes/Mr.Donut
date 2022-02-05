<?php 
// Detect the current session
session_start();
// Include the Page Layout header
include("../header.php"); 
// Include the PHP file that establishes database connection handle: $conn
include_once("../Database/mysql_conn.php");
?>

<!-- HTML Form to collect search keyword and submit it to the same page in server -->
<div style="width:80%; margin:auto;"> <!-- Container -->
<form name="frmSearch" method="GET" action="search.php">
    
    <div class="form-group row"> <!-- 1st row -->
        <div class="col-sm-9 offset-sm-3">
            <span class="page-title">Product Search</span>
        </div>
    </div> <!-- End of 1st row -->
    <div class="form-group row"> <!-- 2nd row -->
        <label for="keywords" 
               class="col-sm-1 col-form-label">Search:</label>
        <div class="col-sm-5">
            <input class="form-control" name="keywords" id="keywords" 
                   type="search" />
        </div>
        <div class="col-sm-3">
            <button class="btn btn-primary" style="background-color: #f89ec9; border-color: #f89ec9" type='submit' >Search</button>
        </div>
    </div>  <!-- End of 2nd row -->
</form>


<?php

// The non-empty search keyword is sent to server
if (isset($_GET["keywords"]) && trim($_GET['keywords']) != "") {
    
    $keywords = $_GET['keywords'];
    $qry = "SELECT * FROM product p INNER JOIN productspec ps ON p.ProductID=ps.ProductID
            WHERE p.ProductTitle LIKE '%$keywords%'
            OR p.ProductDesc LIKE '%$keywords%'
            OR ps.SpecVal LIKE '%$keywords%'
            OR p.Price <= '$keywords'
            OR p.OfferedPrice <= '$keywords'" ;
    $result = $conn->query($qry); 
    
    echo "Search results for: $keywords";
    echo "</p>";
    if ($result->num_rows > 0) { // If found, display records
        while ($row = $result->fetch_array()){
            $product = "/MrDonut/Mr.Donut/Product_Catalogue/productDetails.php?pid=$row[ProductID]";
            echo "<p><a href=$product style='color: #d589ac'>$row[ProductTitle]</a></p>";  
            $img = "../Images/Products/$row[ProductImage]";
            echo "<div class='col-4'>";
            echo "<img src='$img'/>";
            echo "</div>";
        }
    }
    else {
        echo "No record found!";
    }
}



	// To Do (DIY): End of Code


$conn->close(); // Close database connnection
echo "</div>"; // End of container
// Include the Page Layout footer
include("../footer.php"); 
?>