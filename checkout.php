
<?php


include_once('csrf.php');
session_start();

if(!(csrf_verfNonce('checkout',$_GET['nonce']))){
    echo "csrf attack";
    exit();
}

$data=$_GET[data];

$out = explode("-",$data);
$em = $_GET['em'];
$user= $_GET['user'];

//echo $em;

$total = 0;
$currency = "HKD";
$salt = mt_rand();

$hash_info='';
for($i=0;$i<sizeof($out)/2;$i++) {

    $conn_2 = new PDO('sqlite:../cart.db');
    $q_2 = $conn_2->prepare('SELECT * FROM products where pid = ?');
    $q_2->execute(array($out[2*$i]));
    $result = $q_2->fetch(PDO::FETCH_ASSOC);
    //echo $result[price]." " .$result[name] . "  " . $out[2*$i-1] . "    ";
    $total +=  (int)$result[price] * $out[2*$i+1];
    $hash_info .= $result[name] .'-' .$out[2*$i+1].'-'. (int)$result[price] .'-';
    $conn_2=null;
}

//echo $hash_info;

$digest = hash_hmac('sha1', $currency .$em .$hash_info .$total , $salt);

$conn = new PDO('sqlite:../order.db');


$q = $conn->prepare('insert into orders  values (null,?,?,?,?,?,?)');
//$q->execute(array($_POST[catid],$_POST[name],$new_name,$_POST[price],$_POST['description']));
$q->execute(array($digest,$salt,'empty',$user,$currency,$hash_info));
$last= $conn->lastInsertId();
$last +=100;
$conn=null;

echo json_encode(array('custom'=>htmlspecialchars($digest),'invoice'=>htmlspecialchars($last),'hash_info'=>htmlspecialchars($hash_info),'email'=>htmlspecialchars($user)));



?>









