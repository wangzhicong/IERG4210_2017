<<<<<<< HEAD
<?php
include_once('csrf.php');
session_start();
function loadcat(){

    $conn = new PDO('sqlite:../cart.db');
    $q = $conn->prepare( 'SELECT name FROM categories');
    $q->execute();
    $result= $q->fetchAll(PDO::FETCH_ASSOC);
    $txt = 'categories ';
    $i= 0;
    while($i < sizeof( $result) ){
        $cat_names[$i] = $result[$i][name];
        $txt = $txt . '<li onclick=\'load_list(' .$i .')\'>'.$cat_names[$i].'</li>';
        $i =$i +1;
    }
    $conn=null;
    echo $txt;
}

function loadlist(){
    $catid = $_REQUEST[catid]+1;
    $product_name = $_REQUEST[pid];
    if(!preg_match('/^\d+$/',$catid)){
        echo "invalid input";
        exit();
    }
    $conn = new PDO('sqlite:../cart.db');
    $q = $conn->prepare( 'SELECT * FROM products where catid = ?');
    $q->execute(array($catid));
    $result= $q->fetchAll(PDO::FETCH_ASSOC);

    $i=0;
    if(sizeof($result) == 0){
        echo '<li>This is a wrong category, please go to the home page</li>';
        exit();
    }
    while($i < sizeof( $result) ){
        $names[$i] = $result[$i][name];
        $catids[$i] = $result[$i][catid];
        $prices[$i] = $result[$i][price];
        $image_names[$i] = $result[$i][image_source];
        $description[$i] = $result[$i][description];
        $pids[$i]=$result[$i][pid];
        $i = $i + 1;
    }
    $txt = '';
    $conn=null;

    $j = 0;
    while($j < $i) {
        $txt = $txt . '<li><a onclick=\'load_prod('.$catids[$j] .','. $pids[$j]. ')\' ><img  src="img/' .$image_names[$j] .'"/></a>' . '<a onclick=\'load_prod('.$catids[$j] .','.$pids[$j] .')\' > name :'.$names[$j]. '</a><br />' . '<a> price : '.$prices[$j]. '</a><br />'
         . '<a><button id="tocart" onclick="addtocart('.$pids[$j].')">addToCart</button></a></li>';
        $j=$j+1;
    }
    echo $txt;

}



function loadprod()
{

    $product_name = $_REQUEST[pid];
    if(!preg_match('/^\d+$/',$product_name)){
        echo "invalid input";
        exit();
    }
    $conn_2 = new PDO('sqlite:../cart.db');
    $q_2 = $conn_2->prepare('SELECT * FROM products where pid = ?'  );
    $q_2->execute(array($product_name));
    $result = $q_2->fetchAll(PDO::FETCH_ASSOC);
    echo "<li>" . $result[0][name] . "</li>";
#var_dump($result);

    echo '<tr><td rowspan="4"><img src="img/' . $result[0][image_source] . '"> </img> </td><td>Item: ' . $result[0][name] . '<br /></td></tr><tr><td>price: $' . $result[0][price] . '</td></tr>'
        . '<tr><td>description: ' . $result[0][description] . '/td></tr>' . '<tr><td><a><button id="tocart" onclick="addtocart('.$product_name.')">addToCart</button></a></td></tr>';
}


function cartinfo()
{
    $product_name = $_REQUEST[pid];
    if(!preg_match('/^\d+$/',$product_name)){
        echo "invalid input";
        exit();
    }
    $conn_2 = new PDO('sqlite:../cart.db');
    $q_2 = $conn_2->prepare('SELECT name , price FROM products where pid = ?' );
    $q_2->execute(array($product_name));
    $result = $q_2->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
}





if ($_SERVER["REQUEST_METHOD"]=="GET") {

    if(empty($_REQUEST['action'])||!preg_match('/^[\w]+$/',$_REQUEST['action'])) {
        echo 'undefined action';
    }

    if(!(csrf_verfNonce($_REQUEST['action'],$_GET['nonce']))){
        echo "csrf attack";
        exit();
    }

    if(($returnVal=call_user_func($_REQUEST['action']))===false)
    {
        echo "false";exit();
    }

}



?>












=======
<?php
include_once('csrf.php');
session_start();
function loadcat(){

    $conn = new PDO('sqlite:../cart.db');
    $q = $conn->prepare( 'SELECT name FROM categories');
    $q->execute();
    $result= $q->fetchAll(PDO::FETCH_ASSOC);
    $txt = 'categories ';
    $i= 0;
    while($i < sizeof( $result) ){
        $cat_names[$i] = $result[$i][name];
        $txt = $txt . '<li onclick=\'load_list(' .$i .')\'>'.$cat_names[$i].'</li>';
        $i =$i +1;
    }
    $conn=null;
    echo $txt;
}

function loadlist(){
    $catid = $_REQUEST[catid]+1;
    $product_name = $_REQUEST[pid];
    if(!preg_match('/^\d+$/',$catid)){
        echo "invalid input";
        exit();
    }
    $conn = new PDO('sqlite:../cart.db');
    $q = $conn->prepare( 'SELECT * FROM products where catid = ?');
    $q->execute(array($catid));
    $result= $q->fetchAll(PDO::FETCH_ASSOC);

    $i=0;
    if(sizeof($result) == 0){
        echo '<li>This is a wrong category, please go to the home page</li>';
        exit();
    }
    while($i < sizeof( $result) ){
        $names[$i] = $result[$i][name];
        $catids[$i] = $result[$i][catid];
        $prices[$i] = $result[$i][price];
        $image_names[$i] = $result[$i][image_source];
        $description[$i] = $result[$i][description];
        $pids[$i]=$result[$i][pid];
        $i = $i + 1;
    }
    $txt = '';
    $conn=null;

    $j = 0;
    while($j < $i) {
        $txt = $txt . '<li><a onclick=\'load_prod('.$catids[$j] .','. $pids[$j]. ')\' ><img  src="img/' .$image_names[$j] .'"/></a>' . '<a onclick=\'load_prod('.$catids[$j] .','.$pids[$j] .')\' > name :'.$names[$j]. '</a><br />' . '<a> price : '.$prices[$j]. '</a><br />'
         . '<a><button id="tocart" onclick="addtocart('.$pids[$j].')">addToCart</button></a></li>';
        $j=$j+1;
    }
    echo $txt;

}



function loadprod()
{

    $product_name = $_REQUEST[pid];
    if(!preg_match('/^\d+$/',$product_name)){
        echo "invalid input";
        exit();
    }
    $conn_2 = new PDO('sqlite:../cart.db');
    $q_2 = $conn_2->prepare('SELECT * FROM products where pid = ?'  );
    $q_2->execute(array($product_name));
    $result = $q_2->fetchAll(PDO::FETCH_ASSOC);
    echo "<li>" . $result[0][name] . "</li>";
#var_dump($result);

    echo '<tr><td rowspan="4"><img src="img/' . $result[0][image_source] . '"> </img> </td><td>Item: ' . $result[0][name] . '<br /></td></tr><tr><td>price: $' . $result[0][price] . '</td></tr>'
        . '<tr><td>description: ' . $result[0][description] . '/td></tr>' . '<tr><td><a><button id="tocart" onclick="addtocart('.$product_name.')">addToCart</button></a></td></tr>';
}


function cartinfo()
{
    $product_name = $_REQUEST[pid];
    if(!preg_match('/^\d+$/',$product_name)){
        echo "invalid input";
        exit();
    }
    $conn_2 = new PDO('sqlite:../cart.db');
    $q_2 = $conn_2->prepare('SELECT name , price FROM products where pid = ?' );
    $q_2->execute(array($product_name));
    $result = $q_2->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
}





if ($_SERVER["REQUEST_METHOD"]=="GET") {

    if(empty($_REQUEST['action'])||!preg_match('/^[\w]+$/',$_REQUEST['action'])) {
        echo 'undefined action';
    }

    if(!(csrf_verfNonce($_REQUEST['action'],$_GET['nonce']))){
        echo "csrf attack";
        exit();
    }

    if(($returnVal=call_user_func($_REQUEST['action']))===false)
    {
        echo "false";exit();
    }

}



?>












>>>>>>> 2bea1ca962ad3462eed98d20520659aba74bdd9f
