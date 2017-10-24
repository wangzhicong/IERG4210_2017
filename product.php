<?php
#echo "Hello World! in php2";
#error_reporting(E_ALL);
function load_cat(){
    $conn = new PDO('sqlite:../cart.db');
    $q = $conn->prepare( 'SELECT name FROM categories');
    $q->execute();
    $result= $q->fetchAll(PDO::FETCH_ASSOC);
    $txt = 'categories ';
    $i= 0;
#var_dump($result);
#$names=array();
    while($i < sizeof( $result) ){
        $cat_names[$i] = $result[$i][name];
        $txt = $txt . '<li onclick=\'load_list(' .$i .')\'>'.$cat_names[$i].'</li>';
        $i =$i +1;
    }
    $conn=null;
    echo $txt;
}

function load_list(){
    $catid = $_REQUEST[catid]+1;
    $conn = new PDO('sqlite:../cart.db');
    $q = $conn->prepare( 'SELECT * FROM products where catid = '.$catid );
    $q->execute();
    $result= $q->fetchAll(PDO::FETCH_ASSOC);

    $i=0;
#var_dump($result);
#$names=array();
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
        $i = $i + 1;
    }
    $txt = '';
    $conn=null;

    $j = 0;
    while($j < $i) {
        $txt = $txt . '<li><a onclick=\'load_prod('.$catids[$j] .',"'. $names[$j]. '")\' ><img  src="img/' .$image_names[$j] .'"></>' . '<a onclick=\'load_prod('.$catids[$j] .',"'.$names[$j].'")\' > name :'.$names[$j]. '</a><br />' . '<a> price : '.$prices[$j]. '</a><br />'
         . '<a><button>addToCart</button></a></li>';
        $j=$j+1;
    }
    echo $txt;

}



function load_prod()
{
    $product_name = $_REQUEST[product];
    $conn_2 = new PDO('sqlite:../cart.db');
    $q_2 = $conn_2->prepare('SELECT * FROM products where name = "' . $product_name . '" ');
    $q_2->execute();
    $result = $q_2->fetchAll(PDO::FETCH_ASSOC);
    echo "<li>" . $product_name . "</li>";
#var_dump($result);

    echo '<tr><td rowspan="4"><img src="img/' . $result[0][image_source] . '"> </img> </td><td>Item: ' . $result[0][name] . '<br /></td></tr><tr><td>price: $' . $result[0][price] . '</td></tr>'
        . '<tr><td>description: ' . $result[0][description] . '/td></tr>' . '<tr><td><a><button>addToCart</button></a></td></tr>';
}


if ($_SERVER["REQUEST_METHOD"]=="GET") {
    $function_name = $_REQUEST['action'];
    call_user_func($function_name);

}



?>












