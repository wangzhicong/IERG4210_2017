
<?php
include_once('csrf.php');
session_start();
?>

<fieldset>
    <legend>Login</legend>
    <form method="Post" action="auth-process.php" enctype="multipart/form-data">

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
    <form id="form" method="POST" action="auth-process.php" enctype="multipart/form-data">

        <label>Email *</label>
        <div><input id="email" name="em" required="true" pattern="^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+"/></div>

        <label>Password *</label>
        <div><input id="password_0" name="password" type="password" required="true" pattern="^[\d]+$" /></div>

        <label>Repeat Password *</label>
        <div><input id="password_1" name="password_1" type="password" required="true" pattern="^[\d]+$" /></div>


        <input  id="nonce" type="hidden" name = "action" value="signup"/>
        <input  id="nonce" type="hidden" name = "nonce" value="<?php echo getNonce('signup');?>"/>


    </form>
    <button onclick="signin()">Sign Up</button>
</fieldset>
<script>
    function signin(){
        var pass=document.getElementById("password_0").value;
        var pass_1=document.getElementById("password_1").value;
        alert(pass);
        alert(pass_1);
        if(pass !== pass_1){
            alert("The passward are not the same!");
            return false;
        }
        document.getElementById("form").submit();
    }




</script>