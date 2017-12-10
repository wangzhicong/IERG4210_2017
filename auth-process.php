

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

    if(empty($_REQUEST['action'])||!preg_match('/^\w+$/',$_REQUEST['action']))
    {
        echo 'undefined action';
        exit();
    }
    if($_REQUEST['action']=='logout')
    {
        if(!(csrf_verfNonce($_REQUEST['action'],$_GET['nonce']))){
            echo "csrf attack";
            exit();
        }
        if(($returnVal=call_user_func('ierg4210_'.$_REQUEST['action']))===false)
        {
            echo "failed";exit();
        }
    }


    if(!(csrf_verfNonce($_REQUEST['action'],$_POST['nonce']))){
        echo "csrf attack";
        exit();
    }
    if(($returnVal=call_user_func('ierg4210_'.$_REQUEST['action']))===false)
    {
        echo "failed";exit();
    }



function ierg4210_login()
{
    $email = $_POST['em'];
    $password = $_POST['password'];
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

        setcookie('auth', json_encode($token), $exp, '', '', true, true);
        $_SESSION['t4210'] = $token;




        echo $r[0][admin];
        if ($r[0][admin] == 0) {
            //echo $r[0][admin];
            header('Location:/', true, 302);
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





function ierg4210_signup()
{
    $email=$_POST[em];
    $pwd=$_POST[password];
    //echo $email.$pwd;
    if(!preg_match('/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+$/',$email)|| !preg_match('/^[\d]+$/',$pwd) ){
        echo "invalid input";
        exit();
    }
    $salt=mt_rand();
    $salted_pwd=hash_hmac('sha1', $pwd, $salt);

    $conn = new PDO('sqlite:../user.db');
    $q = $conn->prepare( 'INSERT INTO users VALUES (null,?,?,0,?,null,null);' );
    $q->execute(array($email,$salted_pwd,$salt));

    echo "Done!";


}

function ierg4210_change()
{
    $email=$_POST[em];
    $origin= $_POST[origin];
    $new_one=$_POST[password];
    if(!preg_match('/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+$/',$email)|| !preg_match('/^[\d]+$/',$origin)|| !preg_match('/^[\d]+$/',$new_one) ){
        echo "invalid input";
        exit();
    }


    $conn = new PDO('sqlite:../user.db');
    $q = $conn->prepare('SELECT * FROM users WHERE email = ?;');


    $q->execute(array($email));
    $r = $q->fetchAll(PDO::FETCH_ASSOC);
    if($r==null){
        echo "NO such user";
        exit();
    }
    $saltPassword = hash_hmac('sha1', $origin, $r[0]['salt']);
    if ($r[0][password] == $saltPassword) {
        if($r[0][admin]==1)
        {
            echo "cannot change admin password";
            exit();
        }
        $newsaltPassword = hash_hmac('sha1', $new_one, $r[0]['salt']);
        $conn = new PDO('sqlite:../user.db');
        $q = $conn->prepare('UPDATE users SET password=? WHERE email= ?');
        $q->execute(array($newsaltPassword,$email));
        ierg4210_logout();

    }


}


















?>