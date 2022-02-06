<?php
session_start();
include("../header.php"); // Include the Page Layout header
include_once("./myPayPal.php"); // Include the file that contains PayPal settings
include_once("../Database/mysql_conn.php"); 

if($_POST) //Post Data received from Checkout page.
{
	// Checkout Package - Basic Requirement 1
	if (isset($_POST['ShipCharge'])) {
		$_SESSION["ShipCharge"] =  $_POST['ShipCharge'];
		if ($_POST['ShipCharge'] == "2"){
			if ($_SESSION['SubTotal'] > 50) {
				$_SESSION["ShipCharge"] = 0;
			}
			$_SESSION["DeliveryMode"] = "Normal";
			$_SESSION["DeliveryDate"] = date("Y-m-d", time() + 7200);
		}else{
			$_SESSION["DeliveryMode"] = "Express";
			$_SESSION["DeliveryDate"] = date("Y-m-d", time() + 86400);
		}
	}
	if ($_POST['message'] != null) {
		$_SESSION["Message"] =  $_POST['message'];
	}
	// Checkout Package - Additional Requirement 1
	//  Check to ensure each product item saved in the associative array is not out of stock
	$outOfStock = FALSE;
	foreach($_SESSION['Items'] as $item) {
		$qry = "SELECT Quantity FROM Product WHERE ProductID=?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("i", $item['productId']);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$row = $result->fetch_array();
		if ($row['Quantity'] < $item['quantity']) {
			echo "Product $item[productId]: $item[name] is out of stock! <br />";
			echo "You ordered $item[quantity] but we only have $row[Quantity] left. <br /><br />";
			$outOfStock = TRUE;
		}
	
	if ($outOfStock) {
		echo "Return to <a href='../Shopping_Cart/shoppingCart.php'>shopping cart</a> to amend purchases. <br />";
		include("../footer.php");
		exit;
	}
}
	$paypal_data = '';
	// Get all items from the shopping cart, concatenate to the variable $paypal_data
	// $_SESSION['Items'] is an associative array
	foreach($_SESSION['Items'] as $key=>$item) {
		$paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$key.'='.urlencode($item["quantity"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($item["price"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$key.'='.urlencode($item["name"]);
		// $paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$key.'='.urlencode($item["productId"]);
	}
	
	// Checkout Package - Additional Requirement 2
	// Compute GST amount from GST Table in DB, round the figure to 2 decimal places
	$qry = "SELECT TaxRate FROM GST WHERE EffectiveDate < now() order by EffectiveDate DESC LIMIT 1";
	$stmt = $conn->prepare($qry);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();	
	$row = $result->fetch_array();
	$_SESSION["Tax"] = round($_SESSION["SubTotal"]*($row["TaxRate"]/100),2);

	//Data to be sent to PayPal
	$padata = '&CURRENCYCODE='.urlencode($PayPalCurrencyCode).
			  '&PAYMENTACTION=Sale'.
			  '&ALLOWNOTE=1'.
			  '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
			  '&PAYMENTREQUEST_0_AMT='.urlencode($_SESSION["SubTotal"] +
				                                 $_SESSION["Tax"] + 
												 $_SESSION["ShipCharge"]).
			  '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($_SESSION["SubTotal"]). 
			  '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($_SESSION["ShipCharge"]). 
			  '&PAYMENTREQUEST_0_TAXAMT='.urlencode($_SESSION["Tax"]). 	
			  '&BRANDNAME='.urlencode("Mr. Donut").
			  $paypal_data.				
			  '&RETURNURL='.urlencode($PayPalReturnURL ).
			  '&CANCELURL='.urlencode($PayPalCancelURL);	
		
	//We need to execute the "SetExpressCheckOut" method to obtain paypal token
	$httpParsedResponseAr = PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, 
	                                   $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
		
	//Respond according to message we receive from Paypal
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
	   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {					
		if($PayPalMode=='sandbox')
			$paypalmode = '.sandbox';
		else
			$paypalmode = '';
				
		//Redirect user to PayPal store with Token received.
		$paypalurl ='https://www'.$paypalmode. 
		            '.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.
					$httpParsedResponseAr["TOKEN"].'';
		header('Location: '.$paypalurl);
	}
	else {
		//Show error message
		echo "<div style='color:red'><b>SetExpressCheckOut failed : </b>".
		      urldecode($httpParsedResponseAr["L_LONGMESSAGE0"])."</div>";
		echo "<pre>".print_r($httpParsedResponseAr)."</pre>";
	}
}

//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
if(isset($_GET["token"]) && isset($_GET["PayerID"])) 
{	
	//we will be using these two variables to execute the "DoExpressCheckoutPayment"
	//Note: we haven't received any payment yet.
	$token = $_GET["token"];
	$playerid = $_GET["PayerID"];
	$paypal_data = '';
	
	// Get all items from the shopping cart, concatenate to the variable $paypal_data
	// $_SESSION['Items'] is an associative array
	foreach($_SESSION['Items'] as $key=>$item) 
	{
		$paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$key.'='.urlencode($item["quantity"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($item["price"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$key.'='.urlencode($item["name"]);
		$paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$key.'='.urlencode($item["productId"]);
	}
	
	//Data to be sent to PayPal
	$padata = '&TOKEN='.urlencode($token).
			  '&PAYERID='.urlencode($playerid).
			  '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
			  $paypal_data.	
			  '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($_SESSION["SubTotal"]).
              '&PAYMENTREQUEST_0_TAXAMT='.urlencode($_SESSION["Tax"]).
              '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($_SESSION["ShipCharge"]).
			  '&PAYMENTREQUEST_0_AMT='.urlencode($_SESSION["SubTotal"] + 
			                                     $_SESSION["Tax"] + 
								                 $_SESSION["ShipCharge"]).
			  '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode);
	
	//We need to execute the "DoExpressCheckoutPayment" at this point 
	//to receive payment from user.
	$httpParsedResponseAr = PPHttpPost('DoExpressCheckoutPayment', $padata, 
	                                   $PayPalApiUsername, $PayPalApiPassword, 
									   $PayPalApiSignature, $PayPalMode);
	
	//Check if everything went ok..
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
	   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
	{
		// Update stock inventory in product table 
		//                after successful checkout

		$qry = "SELECT * FROM ShopCartItem WHERE ShopCartID = ?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("i", $_SESSION["Cart"]);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
    
		// Checkout Package - Basic Requirement 3
		while($row = $result->fetch_array()){
			$qry = "UPDATE Product SET Quantity = Quantity - ? 
					WHERE ProductID = ?";
			$stmt = $conn->prepare($qry);
			$stmt->bind_param("ii",$row["Quantity"],$row["ProductID"]);
			$stmt->execute();
			$stmt->close();
		}

		// Update shopcart table, close the shopping cart (OrderPlaced=1)
		$total = $_SESSION["SubTotal"] + $_SESSION["Tax"] + $_SESSION["ShipCharge"];
		$qry = "UPDATE ShopCart SET OrderPlaced=1, Quantity=?,
				SubTotal=?, ShipCharge=?, Tax=?, Total=?, Discount=?
				WHERE ShopCartID=?";
		$stmt = $conn->prepare($qry);
		$stmt->bind_param("idddddi", $_SESSION["NumCartItem"],
						$_SESSION["SubTotal"], $_SESSION["ShipCharge"],
						$_SESSION["Tax"], $total, $_SESSION['TotalDiscount'],
						$_SESSION["Cart"]);
		$stmt->execute();
		$stmt->close();
		
		//We need to execute the "GetTransactionDetails" API Call at this point 
		//to get customer details
		$transactionID = urlencode(
		                 $httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);
		$nvpStr = "&TRANSACTIONID=".$transactionID;
		$httpParsedResponseAr = PPHttpPost('GetTransactionDetails', $nvpStr, 
		                                   $PayPalApiUsername, $PayPalApiPassword, 
										   $PayPalApiSignature, $PayPalMode);

		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
		   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) 
		   {
			//gennerate order entry and feed back orderID information
			//You may have more information for the generated order entry 
			//if you set those information in the PayPal test accounts.
			
			$ShipName = addslashes(urldecode($httpParsedResponseAr["SHIPTONAME"]));
			
			$ShipAddress = urldecode($httpParsedResponseAr["SHIPTOSTREET"]);
			if (isset($httpParsedResponseAr["SHIPTOSTREET2"]))
				$ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOSTREET2"]);
			if (isset($httpParsedResponseAr["SHIPTOCITY"]))
			    $ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOCITY"]);
			if (isset($httpParsedResponseAr["SHIPTOSTATE"]))
			    $ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOSTATE"]);
			$ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOCOUNTRYNAME"]). 
			                ' '.urldecode($httpParsedResponseAr["SHIPTOZIP"]);
				
			$ShipCountry = urldecode(
			               $httpParsedResponseAr["SHIPTOCOUNTRYNAME"]);
			
			$ShipEmail = urldecode($httpParsedResponseAr["EMAIL"]);			
			
			// Insert an Order record with shipping information
			// Get the Order ID and save it in session variable.
			$qry = "INSERT INTO OrderData (DeliveryDate, DeliveryMode, ShipName, ShipAddress, ShipCountry,
											ShipEmail, ShopCartID, Message)
					VALUE (?,?,?,?,?,?,?,?)";
			$stmt = $conn->prepare($qry);
			$stmt->bind_param("ssssssis",$_SESSION["DeliveryDate"], $_SESSION["DeliveryMode"], $ShipName, $ShipAddress,
							 $ShipCountry, $ShipEmail,$_SESSION["Cart"],$_SESSION["Message"]);	
			$stmt->execute();
			$stmt->close();		
			$qry = "SELECT LAST_INSERT_ID() AS OrderID";
			$result = $conn->query($qry);
			$row = $result->fetch_array();
			$_SESSION["OrderID"]= $row["OrderID"];				
				
			$conn->close();
				  
			// Reset the "Number of Items in Cart" session variable to zero.
			$_SESSION["NumCartItem"]= 0;
	  		
			// Clear the session variable that contains Shopping Cart ID.
			unset($_SESSION["Cart"]);
			
			// Redirect shopper to the order confirmed page.
			header("Location: orderConfirmed.php");
			exit;
		} 
		else 
		{
		    echo "<div style='color:red'><b>GetTransactionDetails failed:</b>".
			                urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
			echo "<pre>".print_r($httpParsedResponseAr)."</pre>";
			$conn->close();
		}
	}
	else {
		echo "<div style='color:red'><b>DoExpressCheckoutPayment failed : </b>".
		                urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
		echo "<pre>".print_r($httpParsedResponseAr)."</pre>";
	}
}

include("../footer.php"); // Include the Page Layout footer
?>