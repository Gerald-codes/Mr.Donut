<?php 
session_start(); // Detect the current session
include("../header.php"); // Include the Page Layout header
?>
<!-- Create a container, 90% width of viewport -->
<div style='width:90%; margin:auto;'>

<?php 
$pid=$_GET["pid"]; // Read Product ID from query string

// Include the PHP file that establishes database connection handle: $conn
include_once("../Database/mysql_conn.php"); 
$qry = "SELECT * from Product where ProductID=?";
$stmt = $conn->prepare($qry);
$stmt->bind_param("i", $pid); 	// "i" - integer 
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

// To Do 1:  Display Product information. Starting ....
while ($row = $result->fetch_array()){
    // Display Page Header
    // Product's name is read from the "ProductTitle" column of "product" table.
    echo "<div class='row'>";
    echo "<div class='col-sm-12' style='padding:5px'>";
    echo "<span class='page-title'>$row[ProductTitle]</span>";
    echo "</div>";
    echo "</div>";

    echo "<div class='row'>";

    // Left Column - Display the Product's Description 
    echo "<div class='col-sm-9' style='padding:5px'>";
    echo "<p>$row[ProductDesc]</p>";

    // Left Column - Display the product's Specification
    $qry = "SELECT s.SpecName, ps.SpecVal 
            FROM ProductSpec ps INNER JOIN Specification s ON ps.SpecID=s.SpecID
            WHERE ps.ProductID=?
            ORDER BY ps.Priority";
    $stmt = $conn->prepare($qry);
    $stmt->bind_param("i",$pid);
    $stmt->execute();
    $result2 = $stmt->get_result();
    $stmt->close();
    while ($row2 = $result2->fetch_array()){
        echo $row2["SpecName"].": ".$row2["SpecVal"]."<br />";
    }
    // End of Left column 
    echo "</div>";

    // Right Column - Display the product's image 
    $img = "../Images/Products/$row[ProductImage]";
    echo "<div class='col-sm-3' style='vertical-align:top; padding:5px'>";
    echo "<p><img src='$img'/></p>";

    // Right Column - Display the Product's Price 
    $formattedPrice = number_format($row["Price"],2);
    echo "Price:<span style='font-weight: bold;color:red;'>
		S$ $formattedPrice</span>";
}
// To Do 1:  Ending ....

// To Do 2:  Create a Form for adding the product to shopping cart. Starting ....
echo "<form action='../Shopping_Cart/cartFunctions.php' method='post'>";
echo "<input type='hidden' name='action' value='add' />";
echo "<input type='hidden' name='product_id' value='$pid' />";
echo "Quantity: <input type='number' name='quantity' value='1'
                 min='1' max='10' style='width:40px' required />";
echo "<button class='btn btn-primary btn-sm' style='margin-left:3px' type='submit'> Add to Cart </button>";
echo "</form>";
$link = "../Membership_Registration/ranking.php?pid=$pid";
echo "<a href=$link><button class='btn btn-primary btn-sm' style='margin-left:3px' >Rate me!</button></a>";
echo "</div>";
echo "</div>";

// To Do 2:  Ending ....

$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("../footer.php"); // Include the Page Layout footer
?>
