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

    echo "<p class='page-title' style='text-align:center;margin-bottom: 5px;'>Checkout</p>"; 
    echo "<div id='checkoutPage' style='margin-bottom: 10px; background-color: white ;box-shadow: 0 5px 5px 0 rgb(0 0 0 / 9%);'>"; // Start a container
    echo "<div class='table-responsive' >"; // Bootstrap responsive table
    echo "<table class='table table-hover'>"; // Start of table
    echo "<thead class='cart-header'>";
    echo "<tr style='background-color: #2c2b30'>";
    echo "<th width='50%'>Product(s) Ordered</th>"; 
    echo "<th width='12%'>Unit Price (S$)</th>";
    echo "<th width='11%'>Quantity</th>";
    echo "<th width='12%'>Discount (S$)</th>";
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
        echo "<td>$item[unitprice]</td>";
        echo "<td>$item[quantity]</td>";
        echo "<td>$item[totaldiscount]</td>";
        echo "<td>$item[totalcost]</td>";
        echo "</tr>";

    }
    echo "</tbody>"; // End of table's body section
    echo "</table>"; // End of table
    echo ("</div>");
    echo ("</div>");
    echo "<div id='checkoutPage' style='margin-bottom: 10px; background-color: white ;box-shadow: 0 5px 5px 0 rgb(0 0 0 / 9%);'>"; // Start a container
    echo('<form method="post" action="./checkoutProcess.php" class="form-container">');
    echo("<div style='display: flex; justify-content: space-around; height: 150px;'>");
    echo("<div style=' display:flex; justify-content:center; align-items:center; height: 100%; width: 40%; '>");
        echo("<h5 style='margin-right: 5px;'>Message:</h5>");
            echo("<div style='padding: 4px; margin-left:10px;  width: 60%;'>");
            echo('<input type="text" style="width: 100%;
            height: calc(1.5em + 0.75rem + 2px);overflow:hidden; " placeholder="(Optional) Leave a message" name="message" value="">');
            echo ("</div>");
        echo ("</div>");
    echo("<div style='width:50%;display:flex;
	border-radius: 4px; height: 100%; justify-content: flex-start; align-items: center;'>");
        echo("<h5>Delivery Mode:</h5>");
        echo("<div style='display:flex; margin-left:10px;  flex-direction:row'>");
            echo("<div >");
            echo('<input type="radio" id="Normal" name="ShipCharge" value="2" checked>');
    echo('<label for="normal" style="font-size:16px; margin-left: 5px;">Normal Delivery ($2)</label>');
    echo("<p style='width: 350px;font-style: italic;color: grey'>Delivered within 1 working day</p>");
            echo("<div >");
                echo('<input type="radio" id="express" name="ShipCharge" value="5">');
                echo('<label for="express" style="font-size:16px; margin-left: 5px;">Express Delivery ($5)</label>');
            echo("<p style='width: 350px;font-style: italic; color: grey'>Delivered within 2 hours</p>");

            echo("</div>");
        echo ("</div>");
        echo ("</div>");
        echo ("</div>");
        echo ("</div>");
        

    echo ("</div>"); 
    echo "<p style='text-align:right; font-size:16px'>Total Discount: S$".number_format($_SESSION['TotalDiscount'],2), 
		"</br>Order Total (".$_SESSION['TotalQuantities']," item): S$".number_format($_SESSION['SubTotal'],2);
    echo "</div>";
    echo("<div style='display:flex;justify-content: flex-end;' >");
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
