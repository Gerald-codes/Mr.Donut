<?php 
// Detect the current session
session_start();
// Include the Page Layout header
include("indexHeader.php"); 
// Include the PHP file that establishes database connection handle: $conn
include_once("Database/mysql_conn.php");
?>

<div style="text-align:center" class="text-danger"><marquee scrollamount='9'><h2>Now on Sale!</h2></marquee></div>
<?php 

// From SQL to retrieve list of products associated to the category ID
$qry = "SELECT `ProductTitle`,`ProductImage`,`OfferedPrice`,  `ProductID`
        FROM `Product` WHERE now() BETWEEN `OfferStartDate` 
        AND `OfferEndDate`";
// Test with multiple items
// $qry = "SELECT `ProductTitle`,`ProductImage`,`OfferedPrice` 
//           FROM `Product` WHERE `Offered` = '1' ";

$stmt = $conn->prepare($qry);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

echo "<div id='offered-img' class='row' style='border-color: #f89ec9'>";
// Display each product in a row 
while ($row = $result->fetch_array()){
     $product = "/MrDonut/Mr.Donut/Product_Catalogue/productDetails.php?pid=$row[ProductID]";
     echo " <div style='margin:auto'>";
     $img = "./Images/Products/$row[ProductImage]";
     echo "<img src='$img' class='offered-img'/>";
     echo "<h4><a href=$product style='color: #d589ac'>$row[ProductTitle]</a></h4>";
     echo "<div style='text-align:center'><span>On sale at $row[OfferedPrice] !</span><div>";
     echo "</div>";
}
// To Do:  Ending ....
$conn->close(); // Close database connnection
echo "</div>"; // End of container
// Include the Page Layout footer
include("footer.php"); 
?>
