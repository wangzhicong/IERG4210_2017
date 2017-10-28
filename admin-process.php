<!DOCTYPE html>
<html>
<body>

<h1>My first PHP page</h1>




<?php
#echo "Hello World! in php2";
#error_reporting(E_ALL);


function ierg4210_prod_add()
{
    $conn = new PDO('sqlite:../cart.db');
    if(!preg_match('/^[\w\- ]+$/',$_POST['name'])|| !preg_match('/^[\d]+\.\d\d$/',$_POST['price'])||!preg_match('/^[\w\- ]+$/',$_POST['description']) ){
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


        $new_name = "dummpy";
        $new_name = $conn->lastInsertId() . $_POST[image_type];
        $q = $conn->prepare('INSERT INTO products  VALUES (null,' . $_POST[catid] . ',\'' . $_POST[name] . '\',\'' . $new_name . '\',' . $_POST[price] . ',\'' . $_POST['description'] . '\' )');
        $q->execute();
        if($_FILES['myfile']['type']=="image/jpeg" )
            $new_name = $conn->lastInsertId() . ".jpg";
        elseif ($_FILES['myfile']['type']=="image/png")
            $new_name = $conn->lastInsertId() . ".png";
        else
            $new_name = $conn->lastInsertId() . ".gif";

        $q = $conn->prepare( 'UPDATE products SET  image_source = \''.$new_name.'\' WHERE name = \''. $_POST[name]  . '\'') ;

        $q->execute();#->fetcharray();
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
    
 #$item_name = "apple";
    echo "deleting ";
    echo $item_name ."<br>";


   $conn = new PDO('sqlite:../cart.db');  

    $q = $conn->prepare( 'DELETE FROM products WHERE name = \'' . $item_name .'\'' );
    #$q = $conn->prepare( 'SELECT name FROM categories';
    $q->execute();#->fetcharray();
    $conn = null;
    echo "........done!";

}
function ierg4210_prod_update()
{
    if(!preg_match('/^[\w\- ]+$/',$_POST['name'])|| !preg_match('/^[\d]+\.\d\d$/',$_POST['price'])||!preg_match('/^[\w\- ]+$/',$_POST['description']) ){
        echo "invalid input";
        exit();
    }
    $item_name = $_POST['name'];
    echo $item_name ." updating <br>";

   $conn = new PDO('sqlite:../cart.db');
    
    $q = $conn->prepare( 'UPDATE products SET catid = '.$_POST['catid'] .', price = '.$_POST['price'].',description =\''.$_POST['description'].'\' WHERE name = \''. $item_name. '\'') ;

    $q->execute();#->fetcharray();
    echo "Done";

}
function ierg4210_cat_add()
{
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

   $q = $conn->prepare( 'INSERT INTO categories VALUES (null,\''.$_POST['name'].'\');' );
   $q->execute();

   echo "Done!";


}
function ierg4210_cat_delete()
{
    $item_name = $_POST['name'];
    echo "deleting category".$item_name." <br>";


    $conn = new PDO('sqlite:../cart.db');  
    #$conn = new sqlite3($db_name);
    $q = $conn->prepare( 'DELETE FROM categories WHERE name = \''. $item_name .'\';') ;
    $q->execute();#->fetcharray();

    echo "Done!";

}
function ierg4210_cat_update()
{
    if(!preg_match('/^[\w\- ]+$/',$_POST['name']) ){
        echo "invalid input";
        exit();
    }

    $item_name = $_POST['name'];

    echo "updating ".$_POST['catid']." <br>";
    echo $item_name ."updating <br>";
   $conn = new PDO('sqlite:../cart.db');
    $q = $conn->prepare( 'UPDATE categories SET name = \''.$_POST['name']. '\' WHERE catid = '. $_POST['catid'] .' ;') ;

   $q->execute();

   echo "Done!";

}


if ($_SERVER["REQUEST_METHOD"]=="POST") {
$function_name =  $_REQUEST['action'] ; 
#echo $_FILES['myfile']['tmp_name'] ."<br>";

#echo " processing <br> " . $_REQUEST['action'] . "    pid <br>" ;
#echo  $_POST['catid'] . "    catid <br>" ;
#echo  $_POST['name'] . "     name <br>" ;
#echo  $_POST['price'] . "    price <br>" ;
#echo  $_POST['image_name'] . "  img name   <br>" ;

#echo $_FILES['myfile'] ."<br>";

call_user_func($function_name );

}

?>
</body>
</html>
