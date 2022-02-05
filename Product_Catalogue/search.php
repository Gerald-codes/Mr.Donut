<?php 
// Detect the current session
session_start();
// Include the Page Layout header
include("../header.php"); 
// Include the PHP file that establishes database connection handle: $conn
include_once("../Database/mysql_conn.php");
?>

<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />-->
<!-- HTML Form to collect search keyword and submit it to the same page in server -->
<div style="width:80%; margin:auto;"> <!-- Container -->
<form name="frmSearch" method="GET" action="search.php">
    
    <div class="form-group row"> <!-- 1st row -->
        <div class="col-sm-9 offset-sm-3">
            <span class="page-title">Product Search</span>
        </div>
    </div> <!-- End of 1st row -->
    <div class="form-group row"> <!-- 2nd row -->
        <!-- <div class="col-sm-3"> 
            <select name="sweetness" class="form-control" id="sweetness">
            <option value="0">Select Sweetness</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            </select> -->
        <!-- </div> -->
        <label for="keywords" 
               class="col-sm-1 col-form-label">Search:</label>
        <div class="col-sm-5">
            <input class="form-control" name="keywords" id="keywords" 
                   type="search" />
        </div>
        <div class="col-sm-3">
            <button type="submit">Search</button>
        </div>
    </div>  <!-- End of 2nd row -->
</form>


<?php

// The non-empty search keyword is sent to server
if (isset($_GET["keywords"]) && trim($_GET['keywords']) != "") {
    // Retrieve list of product records with "ProductTitle" 
	// contains the keyword entered by shopper, and display them in a table.
    include_once("../Database/mysql_conn.php");
    $keywords = $_GET['keywords'];
    $qry = "SELECT * FROM productspec ps INNER JOIN product p ON ps.ProductID=p.ProductID
            WHERE p.ProductTitle LIKE '%$keywords%'
            OR p.ProductDesc LIKE '%$keywords%'
            OR ps.SpecVal LIKE '%$keywords%'" ;
    $result = $conn->query($qry); 
    

    if ($result->num_rows > 0) { // If found, display records
        while ($row = $result->fetch_array()){
            $product = "/MrDonut/Mr.Donut/Product_Catalogue/productDetails.php?pid=$row[ProductID]";
            echo "<p><a href=$product>$row[ProductTitle]</a></p>";  
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