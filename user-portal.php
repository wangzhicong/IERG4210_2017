
<?php
include_once('csrf.php');
session_start();

?>

<h1>Hello! This is buyer portal</h1>
<h2 id="user_name"><?php if(isset($_SESSION['t4210']['em'])) echo $_SESSION['t4210']['em']; else echo "Guest";?></h2>



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
    <legend>Logout</legend>
<button onclick="logout()">Logout</button>
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
    <button onclick="signin()">Sign Up</button>
</fieldset>

<script>
    function logout() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("user_name").innerHTML ="Guest";
            }
        };
        xhttp.open("GET", "auth-process.php?action=logout&nonce="+<?php echo getNonce('logout')?>, true);
        xhttp.send();
    }


    function signin(){
        var pass=document.getElementById("password").value;
        var pass_1=document.getElementById("password_1").value;
        if(pass !== pass_1){
            alert("The passward are not the same!");
            return false;
        }
        document.getElementById("form").submit();
    }
</script>




















<h2>Recent orders</h2>


    <?php
    $user = $_SESSION['t4210']['em'];
    $conn_2 = new PDO('sqlite:../order.db');
    $q_2 = $conn_2->prepare('SELECT * FROM orders where email = ?  order by oid desc limit 0,5');
    $q_2->execute(array($user));
    $result=$q_2->fetchAll();
    for($j=0;$j<sizeof($result);$j++ ){
        echo '<h4>order</h4><table border="0"><tr><th>item</th><th>num</th><th>price</th></tr>';
        $out = explode("-",$result[$j]['total']);
        if($result[$j]['tid']=='empty') {
            continue;
        }
        for($i=0;$i<(sizeof($out)-1)/3;$i++) {
            echo "<tr>";
            echo "<th>" . $out[3*$i] . "</th>";
            echo "<th>" . $out[3*$i+1] . "</th>";
            echo "<th>" . $out[3*$i+2] . "</th>";
            echo "</tr>";
        }
        echo '</table>';
    }

    ?>




