<?php
include_once ('csrf.php');
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




<?php
#echo "Hello World! in php2";
#error_reporting(E_ALL);


function ierg4210_prod_add()
{
    $conn = new PDO('sqlite:../cart.db');
    if(!preg_match('/^[\w\- ]+$/',$_POST['name'])|| !preg_match('/^[\d]+$/',$_POST['price'])||!preg_match('/^[\w\- ]+$/',$_POST['description']) ){
        echo "invalid input";
        exit();
    }



    if($_FILES['myfile']['size'] >  10485760) {
        echo "the file is too large";
        exit();
    }
    if( $_FILES['myfile']['type']=="image/jpeg" ||  $_FILES['myfile']['type']=="image/png" || $_FILES['myfile']['type']=="image/gif" ) {

        $q = $conn->prepare('select name from products');
        $q->execute();
        $result= $q->fetchAll(PDO::FETCH_ASSOC);
        $i= 0;
        while($i < sizeof( $result) ){
            if($_POST[name] == $result[$i][name]) {
              echo "This name already exists, please use anohter one";
                exit;
            }
            $i +=1;
        }


        //$new_name = "dummpy";

        $q = $conn->prepare('INSERT INTO products  VALUES (null,?,?,null,?,?)');
        $q->execute(array($_POST[catid],$_POST[name],$_POST[price],$_POST['description']));
        if($_FILES['myfile']['type']=="image/jpeg" )
            $new_name = $conn->lastInsertId() . ".jpg";
        elseif ($_FILES['myfile']['type']=="image/png")
            $new_name = $conn->lastInsertId() . ".png";
        else
            $new_name = $conn->lastInsertId() . ".gif";

        $q = $conn->prepare( 'UPDATE products SET  image_source = ? WHERE name = ?') ;

        $q->execute(array($new_name,$_POST[name] ));#->fetcharray();
        #echo "here2";
        #echo $_FILES['myfile']['tmp_name'] ."<br>";

        echo "new image name :".$new_name . "<br>";
        if (move_uploaded_file($_FILES['myfile']['tmp_name'], '/var/www/html/img/' . $new_name))
            echo "success uploaded  <br>";

        #  $conn->close();
        # var_dump($result);
        echo "Success in inserting";
    }
    else{
        echo "false upload file type <br>";
        exit();
    }


}
function ierg4210_prod_delete()
{
    $item_name = $_POST['name'];
    if(!preg_match('/^[\w\- ]+$/',$_POST['name'])){
        echo "invalid input";
        exit();
    }
    echo "deleting ";
    echo $item_name ."<br>";


   $conn = new PDO('sqlite:../cart.db');  

    $q = $conn->prepare( 'DELETE FROM products WHERE name = ?' );
    #$q = $conn->prepare( 'SELECT name FROM categories';
    $q->execute(array($item_name));#->fetcharray();
    $conn = null;
    echo "........done!";

}
function ierg4210_prod_update()
{
    if(!preg_match('/^[\w\- ]+$/',$_POST['name'])|| !preg_match('/^[\d]+$/',$_POST['price'])||!preg_match('/^[\w\- ]+$/',$_POST['description']) ){
        echo "invalid input";
        exit();
    }
    $item_name = $_POST['name'];
    echo $item_name ." updating <br>";

   $conn = new PDO('sqlite:../cart.db');
    
    $q = $conn->prepare( 'UPDATE products SET catid = ?, price =? , description =? WHERE name = ?') ;
    #echo 'UPDATE products SET catid = '.$_POST['catid'] .', price =\''.$_POST['price'].'\',description =\''.$_POST['description'].'\' WHERE name = \''. $item_name;
    $q->execute(array($_POST['catid'],$_POST['price'],$_POST['description'],$item_name));#->fetcharray();
    echo "Done";

}
function ierg4210_cat_add()
{
    if(!preg_match('/^[\w\- ]+$/',$_POST['name']) ){
        echo "invalid input";
        exit();
    }
    echo "inserting  ".$_POST[name] . "<br>";
    $conn = new PDO('sqlite:../cart.db');
    $q = $conn->prepare('select name from categories');
    $q->execute();
    $result= $q->fetchAll(PDO::FETCH_ASSOC);
    $i= 0;
    while($i < sizeof( $result) ){
        if($_POST[name] == $result[$i][name]) {
            echo "This name already exists, please use anohter one";
            exit;
        }
        $i +=1;
    }

   $q = $conn->prepare( 'INSERT INTO categories VALUES (null,?);' );
   $q->execute(array($_POST['name']));

   echo "Done!";


}
function ierg4210_cat_delete()
{
    if(!preg_match('/^[\w\- ]+$/',$_POST['name']) ){
        echo "invalid input";
        exit();
    }
    $item_name = $_POST['name'];
    echo "deleting category".$item_name." <br>";


    $conn = new PDO('sqlite:../cart.db');  
    #$conn = new sqlite3($db_name);
    $q = $conn->prepare( 'DELETE FROM categories WHERE name = ?') ;
    $q->execute(array($item_name));#->fetcharray();

    echo "Done!";

}
function ierg4210_cat_update()
{
    if($_REQUEST['action']=='cat_update')
        csrf_verfNonce($_REQUEST['action'],$_GET['nonce']);

    if(!preg_match('/^[\w\- ]+$/',$_POST['name']) ){
        echo "invalid input";
        exit();
    }

    $item_name = $_POST['name'];

    echo "updating ".$_POST['catid']." <br>";
    echo $item_name ."updating <br>";
   $conn = new PDO('sqlite:../cart.db');
    $q = $conn->prepare( 'UPDATE categories SET name = ? WHERE catid = ? ') ;

   $q->execute(array($_POST['name'],$_POST['catid']));

   echo "Done!";

}



if ($_SERVER["REQUEST_METHOD"]=="POST") {
    if(empty($_REQUEST['action'])||!preg_match('/^[\w\_]+$/',$_REQUEST['action'])) {
        echo 'undefined action';
    }
    echo "check csrf <br>";
    if(!(csrf_verfNonce($_REQUEST['action'],$_POST['nonce']))){
        echo "csrf attack";
        exit();
    }
    if(($returnVal=call_user_func('ierg4210_'.$_REQUEST['action']))===false)
    {
        echo "false";exit();
    }
}

?>

