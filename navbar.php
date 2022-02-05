<?php 
//Display guest welcome message, Login and Registration links
//when shopper has yet to login,
$content1 = "Account: Guest<br />";
$content2 = "<li class='nav-item'>
		     <a class='nav-link' href='../Membership_Registration/register.php'>Sign Up</a></li>
			 <li class='nav-item'>
		     <a class='nav-link' href='../login.php'>Login</a></li>";

if(isset($_SESSION["ShopperName"])) { 
	//To Do 1 (Practical 2) - 
    //Display a greeting message, Change Password and logout links 
    //after shopper has logged in.
	$content1 = "Account: <b>$_SESSION[ShopperName]</b>";
    $content2 = "<li class='nav-item'>
                <a class='nav-link' href='../Membership_Registration/editProfile.php'>Edit Profile</a></li>
                <li class='nav-item'>
                <a class='nav-link' href='../logout.php'>Logout</a></li>";
	//To Do 2 (Practical 4) - 
    //Display number of item in cart
	if (isset($_SESSION["NumCartItem"])){
        $content1 .= ", $_SESSION[NumCartItem] item(s) in shopping cart";
    }
}
?>
<link rel="stylesheet" href="../css/site.css">
<!-- To Do 3 (Practical 1) - 
     Display a navbar which is visible before or after collapsing -->
<nav class="navbar navbar-expand-md navbar-dark bg-custom" style="background-color: #2c2b30; display:flex; justify-content: space-between;" >
    <!-- Dynamic Text Display -->
    <div style=" display: flex; justify-content: flex-start;align-items: center; padding:10px; height:60px; width:80%">
        <a href="../index.php" style="width=200px">
                        <img src="../Images/logo.png" alt="Logo"
                        class="img-fluid" style="width:200px"/></a>
        <div style="box-shadow: 0 0.125rem 0.25rem rgb(0 0 0 / 9%); margin-left: 20px; width: 100%; border: 3px solid white;
         background-color: white; border-radius: 5px; display:flex; justify-content: space-between;align-items: center;">
            <div style=" width: 100%;">
                <form name="frmSearch" method="GET" action="../search.php" autocomplete="off" style=" display: flex;">
               
                <input class="form-control" name="keywords" id="keywords" placeholder="Search for products" style="border: 0px; border-color:white"
                            type="search" /> 
                <button style=" background-color:black;border-radius: 5px; padding:3px; margin-left:10px;margin-right:10px"type="submit">
                <img src="https://www.freeiconspng.com/uploads/search-icon-png-29.png" width="25px" alt=" Search Icon" style="color:white;" />
            </button></form>
            </div>
            
        </div> 
    </div>  
    <span style="color:white"><?php echo $content1; ?> </span>
    <!-- Toggler/Collapsibe Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse"
        data target="#collapsibleNavbar">
        <span class= "navbar-toggler-icon"></span>
    </button>
</nav>
<!-- To Do 4 (Practical 1) - 
     Define a collapsible navbar -->
<nav class="navbar navbar-expand-md navbar-dark bg-custom" style="background-color: #2c2b30" >
    <!-- Collapsible part of navbar -->
    <div class="collapse navbar-collapse" id="collapsibleNavbar" >
        <!-- Left-justified menu items -->
        <ul class= "navbar-nav mr-auto" style="color:#ffffff;">
            <li class="nav-item" >
                <a class="nav-link" href="../Product_Catalogue/category.php">Product Categories</a>
            </li>
            <li class="nav-item" >
                <a class="nav-link" href="../Product_Catalogue/search.php">Product Search</a>
            </li>
            <li class="nav-item" >
                <a class="nav-link" href="../Shopping_Cart/shoppingCart.php">Shopping Cart</a>
            </li>
            <li class="nav-item" >
                <a class="nav-link" href="../Membership_Registration/allranking.php">Donut Ratings</a>
            </li>
        </ul>
        <!-- Right-justified menu items -->
        <ul class= "navbar-nav ml-auto">
            <?php echo $content2; ?>
        </ul>
    </div>
<nav>

