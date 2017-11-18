
<?php
function getNonce($action){
    $nonce = mt_rand();
    if(!isset($_SESSION['csrf_nonce']))
        $_SESSION['csrf_nonce']=array();
    $_SESSION['csrf_nonce'][$action]=$nonce;
    return $nonce;

}

function csrf_verfNonce($action,$receNonce){
    if(isset($receNonce)&&$_SESSION['csrf_nonce'][$action]==$receNonce)
        return true;

    return false;
}
?>