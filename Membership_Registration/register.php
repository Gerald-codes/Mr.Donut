<?php 
// Detect the current session
session_start(); 
// Include the Page Layout header
include("../header.php"); 
include_once("../mysql_conn.php");
?>
<script type="text/javascript">
function validateForm()
{
    // To Do 1 - Check if password matched
	if(document.register.password.value != document.register.password2.value){
        alert("Password not matched");
        return false;
    }
	// To Do 2 - Check if telephone number entered correctly
	if(document.register.phone.value = ""){
        var str = document.register.phone.value;
        if(str.length != 8){
            alert("Please Enter an 8 digit number");
            return false;
        }
        else if(str.substr(0,1) != "6" && str.substr(0,1) != "8" && str.substr(0,1) != "9"){
            alert("Phone Number should start with a 6,8 or 9");
            return false;
        }
    }
    
    return true;  // No error found
}
</script>

<div class="container" style="width:80%; margin:auto;">
<form name="register" action="addMember.php" method="post" 
      onsubmit="return validateForm()">
    <div class="form-group row">
        <div class="col-sm-9 offset-sm-3">
            <span class="page-title">Register</span>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="name">Name:</label>
        <div class="col-sm-5">
            <input class="form-control" name="name" id="name" 
                   type="text" required /> (required)
        </div>
    </div>
    <div class="form-group row">
    <label class="col-sm-2 col-form-label" for="birthdate">Date of Birth:</label>
    <div class="col-sm-3">
            <input class="form-control" name="birthdate" id="birthdate" 
                   type="date" max="2000-01-01" required /> (required)
        </div>
        <script>
            var maxdate = new Date();
            var dd = maxdate.getDate();
            var mm = maxdate.getMonth() + 1;
            var yyyy = maxdate.getFullYear()-13;
            if (dd < 10) {dd = '0' + dd;}
            if (mm < 10) {mm = '0' + mm;} 
            maxdate = yyyy + '-' + mm + '-' + dd;
            document.getElementById("birthdate").setAttribute("max", maxdate);
        </script>
        <!--The above script is to set a maximum date for the input (13 years before the current date)-->
    </div>
    <p>Minimum age to create an account is 13</p>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="address">Address:</label>
        <div class="col-sm-5">
            <textarea class="form-control" name="address" id="address"
                      cols="25" rows="4" ></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="country">Country:</label>
        <div class="col-sm-5">
            <input class="form-control" name="country" id="country" type="text" />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="phone">Phone:</label>
        <div class="col-sm-5">
            <input class="form-control" name="phone" id="phone" type="text" />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="email">
            Email Address:</label>
        <div class="col-sm-5">
            <input class="form-control" name="email" id="email" 
                   type="email" required /> (required)
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="password">
            Password:</label>
        <div class="col-sm-5">
            <input class="form-control" name="password" id="password" 
                   type="password" required /> (required)
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="password2">
            Confirm Password:</label>
        <div class="col-sm-5">
            <input class="form-control" name="password2" id="password2" 
                   type="password" required /> (required)
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-8">Please add a security question and answer in case you forget your password</label>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="pwdquestion">
            Security Question:</label>
        <div class="col-sm-5">
            <input class="form-control" name="pwdquestion" id="pwdquestion" 
                   type="text"/>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="pwdanswer">
            Answer to Security Question:</label>
        <div class="col-sm-5">
            <input class="form-control" name="pwdanswer" id="pwdanswer" 
                   type="text"/> (required)
        </div>
    </div>
    <div class="form-group row">       
        <div class="col-sm-9 offset-sm-3">
            <button type="submit">Register</button>
        </div>
    </div>
</form>
</div>
<?php 
// Include the Page Layout footer
include("../footer.php"); 
?>