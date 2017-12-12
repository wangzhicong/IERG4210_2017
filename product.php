<?php
include_once('csrf.php');
session_start();
function loadcat(){

    $conn = new PDO('sqlite:../cart.db');
    $q = $conn->prepare( 'SELECT name FROM categories');
    $q->execute();
    $result= $q->fetchAll(PDO::FETCH_ASSOC);
    $i= 0;
    while($i < sizeof( $result) ){
        $result[$i]['name'] = htmlspecialchars($result[$i]['name']);
        $i =$i +1;
    }
    $conn=null;

    echo json_encode($result);
}

function loadlist(){
    $catid = $_REQUEST['catid']+1;
   // $product_name = $_REQUEST['pid'];
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
        $result[$i][name] = htmlspecialchars($result[$i][name]);
        $result[$i][catid] = htmlspecialchars($result[$i][catid]);
        $result[$i][price] = htmlspecialchars($result[$i][price]);
        $result[$i][image_source] = htmlspecialchars($result[$i][image_source]);
        $result[$i][description] = htmlspecialchars($result[$i][description]);
        $result[$i][pid]=htmlspecialchars($result[$i][pid]);
        $i = $i + 1;
    }

    $conn=null;
    echo json_encode($result);

}



function loadprod()
{

    $product_name = $_REQUEST['pid'];
    if(!preg_match('/^\d+$/',$product_name)){
        echo "invalid input";
        exit();
    }
    $conn_2 = new PDO('sqlite:../cart.db');
    $q_2 = $conn_2->prepare('SELECT * FROM products where pid = ?'  );
    $q_2->execute(array($product_name));
    $result = $q_2->fetchAll(PDO::FETCH_ASSOC);
    $result[0][image_source]=htmlspecialchars($result[0][image_source]);
    $result[0]['name']=htmlspecialchars($result[0]['name']);
    $result[0]['price']=htmlspecialchars($result[0]['price']);
    $result[0]['description']=htmlspecialchars($result[0]['description']);
    echo json_encode($result);


}


function cartinfo()
{
    $product_name = $_REQUEST['pid'];
    if(!preg_match('/^\d+$/',$product_name)){
        echo "invalid input";
        exit();
    }
    $conn_2 = new PDO('sqlite:../cart.db');
    $q_2 = $conn_2->prepare('SELECT name , price FROM products where pid = ?' );
    $q_2->execute(array($product_name));
    $result = $q_2->fetchAll(PDO::FETCH_ASSOC);
    $result[name]=htmlspecialchars($result[name]);
    $result[price]=htmlspecialchars($result[price]);
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












