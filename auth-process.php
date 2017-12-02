

<?php
/*
if(!empty( $_SESSION['t4210']))
    login();
if(!empty( $_COOKIE['t4210']))
{
    if($t = json_decode(stripcslashes($_COOKIE['t4210']),true))
    {
        if(time()>$t['exp'])return false;
        $db = new PDO('sqlite:../user.db');
        $q=$db->prepare('SELECT * FROM users WHERE email = ?');
        $q->execute(array($t['em']));
        if($r=$q->fetch()){
            $realk=hash_hmac('sha1',$exp . $r['password'],$r['salt']);
            if ($realk==$t['k'])
            {
                $_SESSION['t4210']= $t;
                return $t['em'];
            }
        }
    }
}
return false;
*/
include_once('csrf.php');
session_start();

    if(empty($_REQUEST['action'])||!preg_match('/^\w+$/',$_REQUEST['action']))
    {
        echo 'undefined action';
        exit();
    }
    if(!(csrf_verfNonce($_REQUEST['action'],$_GET['nonce']))){
        echo "csrf attack";
        exit();
    }
    if(($returnVal=call_user_func('ierg4210_'.$_REQUEST['action']))===false)
    {
        echo "failed";exit();
    }



function ierg4210_login()
{
    $email = $_GET['em'];
    $password = $_GET['password'];
    if(!preg_match('/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+$/',$email)|| !preg_match('/^[\d]+$/',$password) ){
        echo "invalid input";
        exit();
    }

    $conn = new PDO('sqlite:../user.db');
    $q = $conn->prepare('SELECT * FROM users WHERE email = ?;');
//echo '<br>SELECT * FROM users WHERE email = \''.$email.'\' ' ;

    $q->execute(array($email));
    $r = $q->fetchAll(PDO::FETCH_ASSOC);
    if($r==null)
        echo "NO such user";
    $saltPassword = hash_hmac('sha1', $password, $r[0]['salt']);
    echo $password;
//echo '<br>' . $r[0][password];
    if ($r[0][password] == $saltPassword) {
        echo "password right";
        session_regenerate_id(true);


        $exp = time() + 3600 * 24 * 3;
        $token = array(
            'em' => $r[0]['email'],
            'exp' => $exp,
            'k' => hash_hmac('sha1', $exp . $r[0]['password'], $r[0]['salt'])
        );

        setcookie('auth', json_encode($token), $exp, '', '', false, true);
        $_SESSION['t4210'] = $token;




        echo $r[0][admin];
        if ($r[0][admin] == 0) {
            //echo $r[0][admin];
            header('Location:index.php?catid=1', true, 302);
            //return true;
            exit();
        }
        if ($r[0][admin] == 1) {
            //echo $r[0][admin];
            header('Location:panel.php', true, 302);
            //return true;
            exit();
        }


    }

    echo  '<br> no result';
}


function ierg4210_logout()
{
    setcookie('auth','',time()-3600);
    unset($_SESSION['t4210']);
    //echo  'Logout successfully';
    header('Location:admin.php');
    exit();
}

























?>