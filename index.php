<?php 
// Detect the current session
session_start();
// Include the Page Layout header
include("indexHeader.php"); 
// Include the PHP file that establishes database connection handle: $conn
include_once("Database/mysql_conn.php");
?>

<div style="text-align:center"><h2>Sales</h2></div>
<?php 

// From SQL to retrieve list of products associated to the category ID
$qry = "SELECT `ProductTitle`,`ProductImage`,`OfferedPrice` 
        FROM `Product` WHERE now() BETWEEN `OfferStartDate` 
        AND `OfferEndDate`";
// Test with multiple items
// $qry = "SELECT `ProductTitle`,`ProductImage`,`OfferedPrice` 
//           FROM `Product` WHERE `Offered` = '1' ";

$stmt = $conn->prepare($qry);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

echo "<div id='offered-img' class='row' >";
// Display each product in a row 
while ($row = $result->fetch_array()){
     echo " <div style='margin:auto'>";
     $img = "./Images/Products/$row[ProductImage]";
     echo "<img src='$img' class='offered-img'/>";
     echo "<h4>$row[ProductTitle]</h4>";
     echo "<div style='text-align:center'><span>Offered Price: $row[OfferedPrice]</span><div>";
     echo "</div>";
}
// To Do:  Ending ....
$conn->close(); // Close database connnection
echo "</div>"; // End of container
// Include the Page Layout footer
include("footer.php"); 
?>
