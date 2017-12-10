
<?php

error_reporting(E_ALL ^ E_NOTICE);

$header="";
$emailtext="";
error_log("0",0);

$req='cmd=_notify-validate';
if(function_exists('get_magic_quotes_gpc'))
{
    $get_magic_quotes_exists=true;
}
foreach ($_POST as $key=>$value)
{
    if($get_magic_quotes_exists==true && get_magic_quotes_gpc()== 1)
    {
        $value=urlencode(stripcslashes($value));
    }
    else{
        $value=urlencode($value);
    }
    //error_log("&$key=$value",0);
    $req .="&$key=$value";
}
$header .= "POST /cgi-bin/webscr HTTP/1.1\r\n";
$header .="Host:www.paypal.com\r\n";
$header .= "Content-Type:application/x-www-form-urlencoded\r\n";
$header .= "Content-Length:" . strlen($req)."\r\n\r\n";


$fp = fsockopen('ssl://www.sandbox.paypal.com',443,$errno,$errstr,30);


if(!$fp)
{
    error_log("listen error",0);
}
else {
    //error_log($header . $req,0);
    fputs($fp, $header . $req);
    error_log("resend success",0);
    while (!feof($fp)) {
        $res = fgets($fp, 1024);
        //error_log("reading ".$res,0);
        if (strcmp($res, "VERIFIED\r\n") == 0) {
            error_log($res, 0);
            if (empty($_POST['payment_status']) || $_POST['payment_status'] != 'Completed') {
                error_log("payment not completed", 0);
            }


            $total = $_POST['mc_gross'];
            $currency = $_POST['mc_currency'];
            $txn_id = $_POST['txn_id'];

            $em = $_POST['business'];

            //error_log($total, 0);
            //error_log($currency, 0);
            //error_log($txn_id, 0);
            //error_log($em, 0);
            $oid=$_POST['invoice']-100;


            $temp=1;
            $hash_info ="";
            while(!empty($_POST['item_name'.$temp])) {
                $item_name = $_POST['item_name'.$temp];
                $item_number = $_POST['quantity'.$temp];
                $item_price = $_POST['mc_gross_'.$temp];
                error_log($item_name, 0);
                error_log($item_number, 0);
                error_log($item_price, 0);
                $hash_info .=$item_name."-".(int)$item_number."-". (int)($item_price/$item_number)."-";
                $temp =$temp+1;
            }
            //error_log($hash_info, 0);



            $conn = new PDO('sqlite:../order.db');

            $q = $conn->prepare('select * from orders where oid=? and tid=\'empty\'');
            $q->execute(array($oid));
            $orders= $q->fetchAll(PDO::FETCH_ASSOC);
            error_log("return size ".sizeof($orders), 0);
            if(sizeof($orders)==0){
                error_log("no result of invoice ".$oid, 0);
            }
            else
            {
                //error_log("return salt ".$orders[0]['salt'], 0);

                $digest = hash_hmac('sha1', $currency .$em .$hash_info .(int)$total , $orders[0]['salt']);
                error_log($hash_info, 0);
                error_log($orders[0]['total'], 0);
                if ($digest == $orders[0]['digest']) {
                    error_log("success order ".$oid, 0);
                    $q = $conn->prepare('update orders set tid=? where oid=?');
                    $q->execute(array($txn_id, $orders[0]['oid']));
                }
            }


            foreach ($_POST as $key => $value) {
                $emailtext .= $key . "=" . $value . "\n\n";
            }
            error_log($emailtext . "\n\n" . $req, 0);
            exit();

        } else if (strcmp($res, "INVALID") == 0) {
            foreach ($_POST as $key => $value) {
                $emailtext .= $key . "=" . $value . "\n\n";
            }
            error_log($emailtext, 0);
            error_log($emailtext . "\n\n" . $req, 0);
            exit();
        }


    }
}



//check payment status completed


fclose ($fp);



?>









