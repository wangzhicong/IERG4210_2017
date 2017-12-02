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
else
{
    redirect($result);
}

?>





<fieldset>
    <legend>New Product</legend>
    <form id="prod_insert" method="POST" action="admin-process.php" enctype="multipart/form-data">
        <label for="prod_catid">Category *</label>
        <div><select id="prod_catid_" name="catid" required="true">
                <?php
                $db_name = '../cart.db';
                $conn = new sqlite3($db_name);
                $sql = 'SELECT catid FROM categories';
                $result = $conn->query($sql);
                while($row = $result->fetchArray(SQLITE3_ASSOC) ){
                    echo "<option value=\"".$row[catid]. "\">".$row[catid];
                }
                ?>
            </select></div>



        <label for="prod_name">Name *</label>
        <div><input id="prod_name_" type="name" name="name" required="true" pattern="^[\w\- ]+$"
            /></div>

        <label for="prod_price">Price *</label>
        <div><input id="prod_price_" type="price" name="price" required="true" pattern="^[\d]+$"
            /></div>

        <label for="prod_name">Discription *</label>
        <div><input id="prod_name_" type="name" name="description" required="true" pattern="^[\w\- ]+$"
            /></div>


        <label for="prod_name">Image *</label>
        <div><input type="file" name="myfile" required="true" accept="image/jpeg,image/gif,image/png" />
        </div>
        <input  id="nonce" type="hidden" name = "action" value="prod_add"/>
        <input  id="nonce" type="hidden" name = "nonce" value="<?php echo getNonce('prod_add');?>"/>
        <input type="submit" value="Submit" />
    </form>
</fieldset>

<fieldset>
    <legend>DELETE Product</legend>
    <form id="prod_delete" method="POST" action="admin-process.php" enctype="multipart/form-data">
        <label for="prod_name">product name *</label>
        <div><select id="prod_name_1" name="name">
                <?php
                $db_name = '../cart.db';
                $conn = new sqlite3($db_name);
                $sql = 'SELECT name FROM products';
                $result = $conn->query($sql);
                while($row = $result->fetchArray(SQLITE3_ASSOC) ){
                    echo "<option value=\"".$row[name]. "\">".$row[name];
                }
                ?>
            </select></div>
        <input  id="nonce" type="hidden" name = "action" value="prod_delete"/>
        <input  id="nonce" type="hidden" name = "nonce" value="<?php echo getNonce('prod_delete');?>"/>
        <input type="submit" value="Submit" />
    </form>
</fieldset>

<fieldset>
    <legend>UPDATE Product</legend>
    <form id="prod_update" method="POST" action="admin-process.php" enctype="multipart/form-data">
        <label for="prod_catid">Name *</label>
        <div><select id="prod_catid" name="name" required="true">
                <?php
                $db_name = '../cart.db';
                $conn = new sqlite3($db_name);
                $sql = 'SELECT name FROM products';
                $result = $conn->query($sql);
                while($row = $result->fetchArray(SQLITE3_ASSOC) ){
                    echo "<option value=\"".$row[name]. "\">".$row[name];
                }
                ?>
            </select></div>

        <label for="prod_catid">New Category *</label>
        <div><select id="prod_catid_" name="catid" required="true">
                <?php
                $db_name = '../cart.db';
                $conn = new sqlite3($db_name);
                $sql = 'SELECT catid FROM categories';
                $result = $conn->query($sql);
                while($row = $result->fetchArray(SQLITE3_ASSOC) ){
                    echo "<option value=\"".$row[catid]. "\">".$row[catid];
                }
                ?>
            </select></div>



        <label for="prod_price">New Price *</label>
        <div><input id="prod_price" type="text" name="price" required="true" pattern="^[\d]+$"
            /></div>

        <label for="prod_name">Discription *</label>
        <div><input id="prod_name_" type="name" name="description" required="true" pattern="^[\w\- ]+$"
            /></div>
        <input  id="nonce" type="hidden" name = "action" value="prod_update"/>
        <input  id="nonce" type="hidden" name = "nonce" value="<?php echo getNonce('prod_update');?>"/>
        <input type="submit" value="Submit" />
    </form>
</fieldset>












<fieldset>
    <legend>New Category</legend>
    <form method="POST" action="admin-process.php" enctype="multipart/form-data">

        <label for="prod_name">Name *</label>
        <div><input id="prod_name_" type="name" name="name" required="true" pattern="^[\w\- ]+$"
            /></div>
        <input  id="nonce" type="hidden" name = "action" value="cat_add"/>
        <input  id="nonce" type="hidden" name = "nonce" value="<?php echo getNonce('cat_add');?>"/>
        <input type="submit" value="Submit" />
    </form>
</fieldset>

<fieldset>
    <legend>DELETE Cat</legend>
    <form id="prod_delete" method="POST" action="admin-process.php" enctype="multipart/form-data">
        <label for="prod_name">product name *</label>
        <div><select id="prod_name_1" name="name">
                <?php
                $db_name = '../cart.db';
                $conn = new sqlite3($db_name);
                $sql = 'SELECT name FROM categories';
                $result = $conn->query($sql);
                while($row = $result->fetchArray(SQLITE3_ASSOC) ){
                    echo "<option value=\"".$row[name]. "\">".$row[name];
                }
                ?>
            </select></div>
        <input  id="nonce" type="hidden" name = "action" value="cat_delete"/>
        <input  id="nonce" type="hidden" name = "nonce" value="<?php echo getNonce('cat_delete');?>"/>
        <input type="submit" value="Submit" />
    </form>
</fieldset>

<fieldset>
    <legend>UPDATE Cat</legend>
    <form id="prod_update" method="POST" action="admin-process.php" enctype="multipart/form-data">
        <label for="prod_catid">Catid *</label>
        <div><select id="prod_catid" name="catid" required="true">
                <?php
                $db_name = '../cart.db';
                $conn = new sqlite3($db_name);
                $sql = 'SELECT catid FROM categories';
                $result = $conn->query($sql);
                while($row = $result->fetchArray(SQLITE3_ASSOC) ){
                    echo "<option value=\"".$row[catid]. "\">".$row[catid];
                }
                $conn->close();
                ?>
            </select></div>

        <label for="cat_name">New Name *</label>
        <div><input id="cat_name_" type="text" name="name" required="true" pattern="^[\w\- ]+$"
            /></div>
        <input  id="nonce" type="hidden" name = "action" value="cat_update"/>
        <input  id="nonce" type="hidden" name = "nonce" value="<?php echo getNonce('cat_update');?>"/>
        <input type="submit" value="Submit" />
    </form>
</fieldset>








<table border="0">
    <tr>
        <th>Oid</th>
        <th>Email</th>
        <th>Tid</th>
        <th>Salt</th>
    </tr>
    <?php
    $db_name = '../order.db';
    $conn = new sqlite3($db_name);
    $sql = 'SELECT * FROM orders';
    $result = $conn->query($sql);
    while($row = $result->fetchArray(SQLITE3_ASSOC) ){
        echo "<tr>";
        echo "<th>" . $row[oid] ."</th>";
        echo "<th>" . $row[digest] ."</th>";
        echo "<th>" . $row[tid] ."</th>";
        echo "<th>" . $row[salt] ."</th>";
        echo "</tr>";
    }
    $conn->close();
    ?>
</table>












