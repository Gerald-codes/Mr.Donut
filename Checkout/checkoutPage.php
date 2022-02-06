<?php 
session_start();
// Include the code that contains shopping cart's functions.
// Current session is detected in cartFunctions.php, hence need not start session here.

if (! isset($_SESSION["ShopperID"])) { // Check if user logged in 
	// redirect to login page if the session variable shopperid is not set
	header ("Location: ../login.php");
	exit;
}

include("../header.php"); // Include the Page Layout header
include_once("../Database/mysql_conn.php"); 

echo "<div id='checkoutPage' style='margin:auto'>"; // Start a container
if (isset($_SESSION["Cart"])) {


    echo "<p class='page-title' style='text-align:center'>Checkout</p>"; 
    echo "<div class='table-responsive' >"; // Bootstrap responsive table
    echo "<table class='table table-hover'>"; // Start of table
    echo "<thead class='cart-header'>";
    echo "<tr>";
    echo "<th width='60%'>Product(s) Ordered</th>"; 
    echo "<th width='15%'>Unit Price (S$)</th>";
    echo "<th width='10%'>Quantity</th>";
    echo "<th width='15%'>Item Subtotal (S$)</th>";
    echo "</tr>"; // End of table row
    echo "</thead>"; // End of table's header section

    foreach($_SESSION['Items'] as $item) {
        $qry = "SELECT ProductImage FROM Product WHERE ProductID=?";
        $stmt = $conn->prepare($qry);
        $stmt->bind_param("i", $item['productId']);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        while ($row = $result->fetch_array()){
            $img = "../Images/Products/$row[ProductImage]";

        }
        echo "<tr>";
        echo "<td style='width:50%'>"; 
        echo "<img src='$img' width='100px' class='offered-img'/><br />$item[name]</td>";
        echo "<td>$item[price]</td>";
        echo "<td>$item[quantity]</td>";
        echo "<td>$item[totalcost]</td>";
        echo "</tr>";

    }
    echo "</tbody>"; // End of table's body section
    echo "</table>"; // End of table
    echo "</div>"; // End of Bootstrap responsive table
    echo('<form method="post" action="./checkoutProcess.php" class="form-container">');
    echo ("<div style='display: flex;
	border: 2px solid black;
	border-radius: 4px; height: 70px;'>");
        echo("<div style='flex:4; display:flex; justify-content:center; align-items:center; border: 1px solid pink;
	border-radius: 4px; height: 100%;'>");
        echo("<span style='padding: 4px;'>Message</span>");
            echo("<div style='style='padding: 4px; margin-left:10px;
        border-radius: 4px; height: 30%;'>");
            echo('<input type="text" style="width:200px" placeholder="(Optional) Leave a message" value="">');
            echo ("</div>");
        echo ("</div>");
    echo("<div style='flex:6; display:flex;
	border-radius: 4px; height: 100%; justify-content: flex-start; align-items: center;'>");
        echo("<span style='margin-left:30%; padding: 4px;'>Delivery Option:</span>");
        echo("<div style='display:flex; margin-left:10px;  flex-direction:row'>");
            echo("<div >");
            echo('<input type="radio" id="Normal" name="ShipCharge" value="2">');
        //     echo('<span class="hovertext" data-hover="Delivered within 1 working day after an order is placed">
        //     Normal Delivery ($2)
        // </span>');
    echo('<label for="normal">Normal Delivery ($2)</label>');
    // echo("<span>Delivered within 1 working day after an order is placed</span>");
            echo("<div >");
                echo('<input type="radio" id="express" name="ShipCharge" value="5">');
                echo('<label for="express">Express Delivery ($5)</label>');
            echo("</div>");
        echo ("</div>");
        echo ("</div>");
        echo ("</div>");
    echo ("</div>");
    echo("<div class='buttonContainer'>");
		// Checkout Package - Basic Requirement 2
		echo "<input id='formButton' type='image' style='float:right;'
					src='https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-large.png' 
					alt='Buy now with PayPal'>";
		echo('</div>');
    echo('</form>');
	}
	else {
		echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
	}
	$conn->close(); // Close database connection

echo "</div>"; // End of container
include("../footer.php"); // Include the Page Layout footer
?>
