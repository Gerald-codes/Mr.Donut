<?php 
// Detect the current session
session_start();
// Include the Page Layout header
include("indexHeader.php");
?>
<!-- Create a centrallly located container -->
<div style="width:80%; margin:auto;">
    <!-- Create a HTML Form withing the container -->
    <form action="checkLogin.php" method="post">
        <!-- 1st row - Header Row -->
        <div class="form-group row">
            <div class="col-sm-9 offset-sm-3">
                <span class="page-title">Member Login</span>
            </div>
        </div>
        <!-- 2nd row - Entry of email address -->
        <div class="form-group row">
            <Label class="col-sm-3 col-form-label" for="email">
                Email Address:
            </label>
            <div class="col-sm-6">
            <input class="form-control" type="email"
                name="email" id="email" required />
            </div>
        </div>
        <!-- 3rd row - Entry of password -->
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="password">
            Password:
            </label>
            <div class="col-sm-6">
                <input class="form-control" type="password"
                    name="password" id="password"
                required/>
            </div>
        </div>
        <!-- 4th row - Login button  -->
        <div class="form-group row" >
            <div class= "col-sm-9 offset-sm-3" >
                <button class="btn btn-primary" style="background-color: #f89ec9; border-color: #f89ec9" type='submit' >Login</button>
                <p></p>
                <p><i>Please sign up if you do not have an account.</i></p>
                <p><a href="Membership_Registration/forgetPassword.php" style='color: #d589ac'>Forget Password</a></p>
            </div> 
        </div>
    </form>
</div>
<?php 
// Include the Page Layout footer
include("footer.php");
?>
