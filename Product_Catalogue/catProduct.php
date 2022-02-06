<?php 
session_start(); // Detect the current session
include("../header.php"); // Include the Page Layout header
?>
<!-- Create a container, 60% width of viewport -->
<div style='width:60%; margin:auto;'>
<!-- Display Page Header - Category's name is read 
     from the query string passed from previous page -->
<div class="row" style="padding:5px">
	<div class="col-12">
		<span class="page-title"><?php echo "$_GET[catName]"; ?></span>
	</div>
</div>

<?php 
// Include the PHP file that establishes database connection handle: $conn
include_once("../Database/mysql_conn.php");

// To Do:  Starting ....
$cid=$_GET["cid"]; // Read catgory id from query string

// From SQL to retrieve list of products associated to the category ID
$qry = "SELECT*FROM CatProduct cp INNER JOIN Product p ON cp.ProductID=p.ProductID
		WHERE cp.CategoryID=?
    ORDER BY p.ProductTitle ";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i",$cid);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();



// Display each product in a row 
while ($row = $result->fetch_array()){
    echo "<div class='row' style='padding:5px'>";

    // Left column - Display a text link showing the Product's name,
    //               Display the selling price in red in a new paragraph
    
    $product = "productDetails.php?pid=$row[ProductID]";
	  $formattedPrice = number_format($row["Price"],2);
    $OfferedPrice = number_format($row["OfferedPrice"],2);
    $Quantity = $row["Quantity"];
    $OfferStart = $row["OfferStartDate"];
    $OfferEnd = $row["OfferEndDate"];
    $now = date('Y-m-d');

    if ($Quantity <= 0){
      echo "<div class='col-8'>";
      echo "<p><a href=$product style='color: #d589ac'><h5>$row[ProductTitle]</h5></a></p>";
      echo "<span style='font-weight: bold;color:red;'>
      Out of Stock!</span>";
      echo "</div>";
    }

    else{
      if (($row["Offered"] == 1)  && ($OfferStart <= $now && $now <= $OfferEnd)){
        echo "<div class='col-8'>";
        echo "<p><a href=$product style='color: #d589ac'><h5>$row[ProductTitle]</h5></a></p>";
        echo "<span style='font-weight: bold;color:grey;'><s>
        S$ $formattedPrice</s></span>";
        echo "<p><h6><span style='font-weight: bold;color:red;'>NOW S$ $OfferedPrice !</span></h6></p>";
        echo "</div>";
      }
      else{
        echo "<div class='col-8'>";
        echo "<p><a href=$product style='color: #d589ac'><h5>$row[ProductTitle]</h5></a></p>";
        echo "<span style='font-weight: bold;color:grey;'>
        S$ $formattedPrice</span>";
        echo "</div>";
      }
    }
    

    // Right Column - Display the product's image 
    $img = "../Images/Products/$row[ProductImage]";
    echo "<div class='col-4'>";
    echo "<img src='$img'/>";
    echo "</div>";

    echo "</div>";
}
// To Do:  Ending ....

$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("../footer.php"); // Include the Page Layout footer
?>
