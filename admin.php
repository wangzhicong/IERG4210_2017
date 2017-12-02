
<?php
include_once('csrf.php');
session_start();
?>

<fieldset>
    <legend>Login</legend>
    <form id="form" method="GET" action="auth-process.php" enctype="multipart/form-data">

        <label for="prod_name">Email *</label>
        <div><input id="email" name="em" required="true" pattern="^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+""/></div>

        <label for="prod_price">password *</label>
        <div><input id="password" name="password" type="password" required="true" pattern="^[\d]+$" /></div>
        <input  id="nonce" type="hidden" name = "action" value="login"/>
        <input  id="nonce" type="hidden" name = "nonce" value="<?php echo getNonce('login');?>"/>

        <input type="submit" value="Login"/>
    </form>

</fieldset>


<fieldset>
    <legend>Sign up</legend>
    <form id="form" method="GET" action="auth-process.php" enctype="multipart/form-data">

        <label for="prod_name">Email *</label>
        <div><input id="email" name="em" required="true" pattern="^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+"/></div>

        <label for="prod_price">Password *</label>
        <div><input id="password" name="password" type="password" required="true" pattern="^[\d]+$" /></div>

        <label for="prod_price">Repeat Password *</label>
        <div><input id="password_1" name="password_1" type="password" required="true" pattern="^[\d]+$" /></div>

        <label for="prod_price">Paypal email *</label>
        <div><input name="paymail" required="true" pattern="^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+" /></div>



        <input  id="nonce" type="hidden" name = "action" value="signup"/>
        <input  id="nonce" type="hidden" name = "nonce" value="<?php echo getNonce('signup');?>"/>


    </form>
    <button onclick="login()">Sign Up</button>
</fieldset>
<script>
    function login(){
        var pass=document.getElementById("password").value;
        var pass_1=document.getElementById("password_1").value;
        if(pass !== pass_1){
            alert("The passward are not the same!");
            return false;
        }
        document.getElementById("form").submit();
    }




</script>