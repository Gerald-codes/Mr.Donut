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
    $formattedPrice = number_format($row["Price"],2);
    $OfferedPrice = number_format($row["OfferedPrice"],2);
    $Quantity = $row["Quantity"];

    if ($row["Offered"] == 1){
        echo "<div class='row'>";
        echo "<div class='col-sm-12' style='padding:5px'>";
        echo "<span class='page-title' style='color: #d589ac'>$row[ProductTitle]</span>";  
        echo "</div>";
        echo "</div>";
        echo "<div class='row'>";
        echo "<div class='col-sm-12' style='padding:5px'>";
        echo "<h6><span style='color: red'>Now on Sale!</span></h6>";
        echo "</div>";
        echo "</div>";
      }
    else{
        echo "<div class='row'>";
        echo "<div class='col-sm-12' style='padding:5px'>";
        echo "<span class='page-title' style='color: #d589ac'>$row[ProductTitle]</span>";
        echo "</div>";
        echo "</div>";
    }

    echo "<div class='row'>";
    // Left Column - Display the Product's Description 
    echo "<div class='col-sm-9' style='padding:5px'>";
    echo "<p><h6><i>$row[ProductDesc]</i></h6></p>";

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
        echo "</p>";
        echo $row2["SpecName"].": ".$row2["SpecVal"]."<br />";
    }
    // End of Left column 
    echo "</div>";

    // Right Column - Display the product's image 
    $img = "../Images/Products/$row[ProductImage]";
    echo "<div class='col-sm-3' style='vertical-align:top; padding:5px'>";
    echo "<p><img src='$img'/></p>";

    // Right Column - Display the Product's Price 
    
    if ($Quantity == 0){
      echo "<div class='col-8'>";
      echo "<h5><span style='font-weight: bold;color:red;'>
      Out of Stock!</span></h5>";
      echo "</div>";
    }
    else{
        if ($row["Offered"] == 1){
            echo "<div class='col-8'>";
            echo "<span style='font-weight: bold;color:grey;'><s>
                S$ $formattedPrice</s></span>";
            echo "<p><h5><span style='font-weight: bold;color:red;'>
                S$ $OfferedPrice</span></h5></p>";
            echo "</div>";
          }
        else{
            echo "<div class='col-8'>";
            echo "<h5><span style='font-weight: bold;color:red;'>
                S$ $formattedPrice</h5></span>";
            echo "</div>";
        }
    }
    
}
// To Do 1:  Ending ....

// To Do 2:  Create a Form for adding the product to shopping cart. Starting ....
if ($Quantity <= 0){
    echo "</p>";
    echo "<form action='../Shopping_Cart/cartFunctions.php' method='post'>";
    echo "<input type='hidden' name='action' value='add' />";
    echo "<input type='hidden' name='product_id' value='$pid' />";
    echo "Quantity: <input type='number' name='quantity' value='1'
                 min='1' max='10' style='width:40px' disabled />";
    echo "<button class='btn btn-primary btn-sm' style='margin-left:3px; background-color: #f89ec9; border-color: #f89ec9' type='submit' disabled> Add to Cart </button>";
    echo "</form>";
    $link = "../Membership_Registration/ranking.php?pid=$pid";
    echo "</p>";
    echo "<a href=$link><button class='btn btn-primary btn-sm' style='margin-left:3px; background-color: #f89ec9; border-color: #f89ec9' >Rate me!</button></a>";
    echo "</div>";
    echo "</div>";
}
else {
    echo "</p>";
    echo "<form action='../Shopping_Cart/cartFunctions.php' method='post'>";
    echo "<input type='hidden' name='action' value='add' />";
    echo "<input type='hidden' name='product_id' value='$pid' />";
    echo "Quantity: <input type='number' name='quantity' value='1'
                 min='1' max='10' style='width:40px' required />";
    echo "<button class='btn btn-primary btn-sm' style='margin-left:3px; background-color: #f89ec9; border-color: #f89ec9' type='submit'> Add to Cart </button>";
    echo "</form>";
    $link = "../Membership_Registration/ranking.php?pid=$pid";
    echo "</p>";
    echo "<a href=$link><button class='btn btn-primary btn-sm' style='margin-left:3px; background-color: #f89ec9; border-color: #f89ec9' >Rate me!</button></a>";
    echo "</div>";
    echo "</div>";
}
// To Do 2:  Ending ....

$conn->close(); // Close database connnection
echo "</div>"; // End of container
include("../footer.php"); // Include the Page Layout footer
?>
