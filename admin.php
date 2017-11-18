
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
        <div><input id="password" name="password" required="true" pattern="^[\d]+$" /></div>
        <input  id="nonce" type="hidden" name = "action" value="login"/>
        <input  id="nonce" type="hidden" name = "nonce" value="<?php echo getNonce('login');?>"/>

        <input type="submit" value="Login"/>
    </form>
</fieldset>