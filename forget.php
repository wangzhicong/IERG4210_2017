
<?php
include_once('csrf.php');
session_start();


$to = "wangzhc5@outlook.com";
$subject = "Test";
$message = "This is a test mail!";
mail($to,$subject,$message);
?>

<fieldset>
    <legend>Forget your password</legend>
    <form id="form" method="POST" action="auth-process.php" enctype="multipart/form-data">

        <label>User email *</label>
        <div><input id="email" name="em" required="true" pattern="^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+"/></div>

        <input  type="hidden" name = "action" value="forget"/>
        <input  id="nonce" type="hidden" name = "nonce" value="<?php echo getNonce('forget');?>"/>
        <input type="submit" value="Reset"/>

    </form>


</fieldset>