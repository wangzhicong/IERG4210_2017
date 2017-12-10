
<?php
include_once('csrf.php');
session_start();


function redirect($email)
{
    $db = new PDO('sqlite:../user.db');
    $q = $db->prepare('SELECT * FROM users WHERE email = ?');
    $q->execute(array($email));
    if ($r = $q->fetch()) {
        if ($r['admin'] == 0) {
            //echo $r[0][admin];
            header('Location:admin.php', true, 302);
            //return true;
            exit();
        }
        if ($r['admin'] == 1) {
            return true;
        }
    }
}


function checksession()
{
    if (!empty($_SESSION['t4210'])) {
        return $_SESSION['t4210']['em'];
    }
    if (!empty($_COOKIE['auth'])) {
        if ($t = json_decode(stripcslashes($_COOKIE['auth']), true)) {
            if (time() > $t['exp']) return false;
            $db = new PDO('sqlite:../user.db');
            $q = $db->prepare('SELECT * FROM users WHERE email = ?');
            $q->execute(array($t['em']));
            if ($r = $q->fetch()) {
                $realk = hash_hmac('sha1', $t['exp']. $r['password'], $r['salt']);
                if ($realk == $t['k']) {
                    $_SESSION['t4210'] = $t;
                    return $t['em'];
                }
            }
        }
    }
    return false;
}


$result = checksession();
if($result == false)
{
    header('Location:admin.php', true, 302);
}


?>

<h1>Hello! This is buyer portal</h1>
<h2 id="user_name"><?php if(isset($_SESSION['t4210']['em'])) echo $_SESSION['t4210']['em']; else echo "Guest";?></h2>
<a href="index.php">Back</a>

<fieldset>
    <legend>Change passward</legend>
    <form id="change" method="POST" action="auth-process.php" enctype="multipart/form-data">



        <label>Origin Password *</label>
        <div><input id="origin" name="origin" type="password" required="true" pattern="^[\d]+$" /></div>

        <label>Password *</label>
        <div><input id="password_0_1" name="password" type="password" required="true" pattern="^[\d]+$" /></div>

        <label>Repeat Password *</label>
        <div><input id="password_1_1" name="password_1" type="password" required="true" pattern="^[\d]+$" /></div>

        <input  type="hidden" name = "em" value="<?php if(isset($_SESSION['t4210']['em'])) echo $_SESSION['t4210']['em']; else echo "Guest";?>"/>
        <input  id="nonce" type="hidden" name = "action" value="change"/>
        <input  id="nonce" type="hidden" name = "nonce" value="<?php echo getNonce('change');?>"/>


    </form>
    <button onclick="change()">Change</button>
</fieldset>



<script>
    function change(){
        var pass=document.getElementById("password_0_1").value;
        var pass_1=document.getElementById("password_1_1").value;
        if(pass !== pass_1){
            alert("The new passward are not the same!");
            return false;
        }
        document.getElementById("change").submit();
    }
</script>



<h2>Recent orders</h2>


    <?php
    $user = $_SESSION['t4210']['em'];
    $conn_2 = new PDO('sqlite:../order.db');
    $q_2 = $conn_2->prepare('SELECT * FROM orders where email = ? and tid != "empty" order by oid desc limit 0,5');
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




