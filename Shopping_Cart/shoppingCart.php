<?php 
// Include the code that contains shopping cart's functions.
// Current session is detected in cartFunctions.php, hence need not start session here.
include_once("cartFunctions.php");

if (! isset($_SESSION["ShopperID"])) { // Check if user logged in 
	// redirect to login page if the session variable shopperid is not set
	header ("Location: ../login.php");
	exit;
}

include("../header.php"); // Include the Page Layout header


echo "<div id='myShopCart' style='margin:auto'>"; // Start a container
if (isset($_SESSION["Cart"])) {
	include_once("../Database/mysql_conn.php");

	$qry = "SELECT *, (Price*Quantity) AS Total
			FROM ShopCartItem WHERE ShopCartID=?";
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("i", $_SESSION["Cart"]);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	
	if ($result->num_rows > 0) {
		echo "<p class='page-title' style='text-align:center'>Shopping Cart</p>"; 
		echo "<div class='table-responsive' >"; // Bootstrap responsive table
		echo "<table class='table table-hover'>"; // Start of table
		echo "<thead class='cart-header'>";
		echo "<tr>";
		echo "<th width='250px'>Item</th>"; 
		echo "<th width='90px'>Price (S$)</th>";
		echo "<th width='60px'>Quantity</th>";
		echo "<th width='120px'>Total (S$)</th>";
		echo "<th>&nbsp;</th>";
		echo "</tr>"; // End of table row
		echo "</thead>"; // End of table's header section

		$_SESSION["Items"] = array();	


		$subTotal = 0; // Declare a variable to compute subtotal before tax
		echo "<tbody>"; // Start of table's body section
		while ($row = $result->fetch_array()) {
			echo "<tr>";
			echo "<td style='width:50%'>$row[Name]<br />"; 
			echo "Product ID: $row[ProductID]</td>";
			$formattedPrice = number_format($row["Price"],2);
			echo "<td>$formattedPrice</td>";
			echo "<td>";
			echo "<form action='cartFunctions.php' method='post'>";
			echo "<select name='quantity' onChange='this.form.submit()'>";
			for ($i = 1; $i <= 10; $i++){
				if ($i == $row["Quantity"])
					$selected = "selected";
				else
					$selected = "";
				echo "<option value='$i' $selected>$i</option>";
			}
			echo "</select>";
			echo "<input type='hidden' name='action' value='update' />";
			echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			echo "</form>";
			echo "</td>";
			$formattedTotal = number_format($row["Total"],2);
			echo "<td>$formattedTotal</td>";
			echo "<td>";
			echo "<form action='cartFunctions.php' method='post'>";
			echo "<input type='hidden' name='action' value='remove' />";
			echo "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			echo "<input type='image' src='../Images/trash-can.png' title='Remove Item' />";
			echo "</form>";
			echo "</td>";
			echo "</tr>";

			$_SESSION["Items"][] = array("productID"=>$row["ProductID"],
										 "name"=>$row["Name"],
										 "price"=>$row["Price"],
										 "quantity"=>$row["Quantity"]);	

			// Accumulate the running sub-total
			$subTotal += $row["Total"];
		}
		echo "</tbody>"; // End of table's body section
		echo "</table>"; // End of table
		echo "</div>"; // End of Bootstrap responsive table
    
		$totalQuantity = 0;
		foreach ($_SESSION["Items"] as $purchased) {
			$totalQuantity += $purchased['quantity'];
		}

		// Add PayPal Checkout button on the shopping cart page
		echo('<div class="form-popup" id="deliveryForm">');
		echo('<form method="post" action="../Checkout/checkoutProcess.php" class="form-container">');
		echo('<h2 id="form-header">Delivery Option</h2>');
		echo("<div class='options'>");
		echo('<input type="radio" id="Normal" name="ShipCharge" value="2">');
		echo('<label for="normal"><h5>Normal Delivery</h5><p>$2</p></label>');
		echo("</div>");
		echo("<p> delivered within 1 working day after an order is placed</p>");
		echo("<div class='options'>");
		echo('<input type="radio" id="express" name="ShipCharge" value="5">');
		echo('<label for="express"><h5>Express Delivery</h5><p>$5</p></label>');
		echo("</div>");
		echo("<p> delivered within 2 hours after an order is placed</p>");

		echo "<p style='text-align:right; font-size:20px'>
		Subtotal = S$". number_format($subTotal, 2), 
		"</br>Total quantity of items: $totalQuantity"; // Additional 2. Compute number of items in cart
  		$_SESSION["SubTotal"] = round($subTotal, 2);	

		echo("<div class='buttonContainer'>");
		echo "<input id='formButton' type='image' style='float:right;' onclick='getDeliveryMode()'
					src='https://www.paypalobjects.com/webstatic/en_US/i/buttons/checkout-logo-large.png' 
					alt='Buy now with PayPal'>";
		echo('</div>');
		echo('</div>');
		echo('</form>');

		// Retrieve ShipCharge
		$qry2 = "SELECT ShipCharge FROM ShopCart WHERE ShopCartID=?";
		$stmt = $conn->prepare($qry2);
		$stmt->bind_param("i", $_SESSION["Cart"]);
		$stmt->execute();
		$result = $stmt->get_result();
		$row2 = $result->fetch_array();
		$_SESSION['ShipCharge'] = $row2['ShipCharge'];
		$stmt->close();

		// Addtional 2. Check if subtotal > 50, and change ShipCharge if it is 
		if ($_SESSION['ShipCharge'] == 2) {
			if ($subTotal > 50) {
				$_SESSION['ShipCharge'] = 0;
				$sql = "UPDATE ShopCart SET ShipCharge = ? WHERE ShopCartId = ?  ";
                $stmt2 = $conn->prepare($sql);
                $stmt2->bind_param("di",$_SESSION['ShipCharge'], $_SESSION["Cart"]);
                $stmt2->execute();
                $stmt2->close();
			}
		}
	}
	else {
		echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
	}
	$conn->close(); // Close database connection
}
else {
	echo "<h3 style='text-align:center; color:red;'>Empty shopping cart!</h3>";
}

echo "</div>"; // End of container
include("../footer.php"); // Include the Page Layout footer
?>
