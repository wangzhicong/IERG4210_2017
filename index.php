<!doctype html>

<?php
include_once('csrf.php');
session_start();
?>

<style>
    ul.table{width:80%;height:100%;
        margin:0;padding:0;list-style:none;
        overflow:auto}
    ul.table{
        position:absolute;
        left:200px;
        top:400px;
    }
    ul.table li{width:150px;height:250px;margin: 10px;background-color: white;
        float:left;border:1px solid #CCC}
    ul.table img{width:150px;
        height: 150px;}
    ul.p {color: white;}
    .clear{clear:both}
</style>

<style>
    nav{
        position:absolute;
        left: 70%;
        top: 300px;
        z-index: 100;
        color: white;
    }
    nav ul{display:none;position:absolute;margin: -20px;width: 400px; color: black;
    }
    nav input{
        width: 30px;
        padding: 0px 0px;
        margin: 8px 0;
        position: relative;left: 30px;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    nav:hover ul{display:block;background-color: green;}
</style>

<style>
    .cats a:link{color:white;}
    .cats a:hover{font-weight:bold}
    .cats a:visited{color:yellow;}
    .cats
    {
        width: 160px;
        color: white;
        position: absolute;
        left: 0%;
        top: 395px;
    }
</style>
<style>
    h3 {
        position:absolute;
        left: 200px;
        top: 350px;
    }
    h3 a:link{color:white}
    h3 a:hover{font-weight:bold}
    h3 a:visited{color:yellow;}

</style>
<style>
    h1.title {
        color: white;
        text-align: center;
        width: 500px;
        height:210px;
        position: absolute;
        left:30%;
        top:0%;}
    footer.foot {position:fixed;
        left:10%;bottom:0%;width:80%;height:100px;background-color: #888888;}
    body {background:url("img/background.jpeg");background-size:100%; z-index: 0;}
    h3 {color: white;}
</style>

<style>
    table {
        background-color: white;
        position: absolute;
        left:200px;
        top:400px;
        width:60%;

    }
</style>

<body   onload="load_cats();GetRequest();refresh_cart()"></body>

<h1 class="title" id="demo"><img src="img/three%20bros.gif">wangzhicong's weapon store</h1>
<h3 id="nave">
    <a onclick='home_page()'>home</a>
</h3>

<ul class="cats" id ="cat_list" ></ul>
<ul class="table" id="list"></ul>




<table id="prod_info">

</table>

<footer class="foot" >
    <p id="user_name"><?php if(isset($_SESSION['t4210']['em'])) echo $_SESSION['t4210']['em']; else echo "Guest";?></p>
    <button onclick="logout()">Logout</button>
    <button onclick="login()">Login</button>
    <button onclick="buyer()">Buyer Portal</button>
</footer>



<script>
    function buyer(){
        self.location="user-portal.php";
    }

    function addtocart(pid){
        //alert('add to cart action');
        if(localStorage.getItem(pid)== null)
            localStorage.setItem(pid,1);
        //change the list info
        refresh_cart();

    }

    function refresh_cart()
    {
        //get information from php
        document.getElementById("cart_list").innerHTML='';
        var total_price=0;
        for(var i=0;i<localStorage.length;i++){
            //alert(localStorage.key(i));
            var pid = parseInt(localStorage.key(i));
            //alert(pid+'num '+localStorage.getItem(pid));


            var xhttp = new XMLHttpRequest();

            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var info = JSON.parse(this.response);
                    info = info[0];
                    total_price += info.price * parseInt(localStorage.getItem(pid));
                    var tmp ='<li>       '+info.name+ '      $'+ info.price + '         <button onclick=\"increase_num('+   pid  +  ')\">+</button> '+ localStorage.getItem(pid) + '<button onclick=\"decrease_num(' +  pid + ')\">-</button></li>';
                    //  '<tr><th>'+info.name+ '</th><th>'+ info.price + '</th><th> <button onclick=\"increase_num('+   pid  +  ')\">+</button> </th><th>'+ localStorage.getItem(pid) + '</th><th><button onclick=\"decrease_num(' +  pid + ')\">-</button></th></tr>';
                    document.getElementById("cart_list").innerHTML = document.getElementById("cart_list").innerHTML + tmp;
                    document.getElementById("total").innerHTML = "Total price = " + total_price;
                    document.getElementById("total").innerHTML = "Shopping cart :  Total price = " + total_price;
                }
            };
            xhttp.open("GET", "product.php?pid="+pid +"&action=cartinfo&nonce="+<?php echo getNonce('cartinfo')?>, false);
            xhttp.send();

        }
        document.getElementById("cart_list").innerHTML = document.getElementById("cart_list").innerHTML + '<button onclick="myFunction()">pay</button>';


    }
    function decrease_num(pid){
        var num = localStorage.getItem(pid) ;
        if(parseInt(num)>0)
            localStorage.setItem(pid,(parseInt(num) - 1));
        num = localStorage.getItem(pid) ;
        if(parseInt(num)<=0) {
            localStorage.removeItem(pid);
            document.getElementById("total").innerHTML = "Shopping cart :  Total price = " + 0;
        }
        //alert(localStorage.getItem(pid));
        refresh_cart();
    }
    function increase_num(pid){
        var num = localStorage.getItem(pid) ;
        localStorage.setItem(pid,(parseInt(num) + 1));
        //alert(localStorage.getItem(pid));
        refresh_cart();
    }




    function home_page(){
        document.getElementById("prod_info").innerHTML ='';
        document.getElementById("list").innerHTML = '<li>Home page with nothing</li>';
        document.getElementById("nave").innerHTML ="<a onclick=\'home_page()\'>home</a>";
        var url = window.location.href;


        var tmp = url.replace(/\?catid=[%20]*[\d]/gi,"");


        history.replaceState(null,url,tmp);
    }

    function load_cats() {
        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("prod_info").innerHTML ='';
                document.getElementById("cat_list").innerHTML = this.responseText;
                document.getElementById("list").innerHTML = '';
            }
        };
        xhttp.open("GET", "product.php?action=loadcat&nonce="+<?php echo getNonce('loadcat')?>, true);
        xhttp.send();


    }



    function load_list(id) {
        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var url = window.location.href;

                //var tmp = url.replace(/&prod=[\w\-\s]*/gi,"");
                var tmp = url.replace(/\?catid=[%20]*[\d]*/gi,"");
                history.replaceState(null,url,tmp+'?catid='+(id+1));


                //location.href = '?catid='+(id+1);
                document.getElementById("prod_info").innerHTML ='';
                document.getElementById("list").innerHTML = '';
                document.getElementById("list").innerHTML = this.responseText;
                document.getElementById("nave").innerHTML = '';
                document.getElementById("nave").innerHTML = "<a onclick=\'home_page()\'>home</a>" + '   >   category ' + (id+1);

            }
        };
        xhttp.open("GET", "product.php?catid="+id+"&action=loadlist&nonce="+<?php echo getNonce('loadlist')?>, true);
        xhttp.send();


    }

    function load_prod(id,pid) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("nave").innerHTML = '';
                document.getElementById("nave").innerHTML = "<a onclick=\'home_page()\'>home</a>" + "<a onclick=\'load_list("+ (id-1) +")\'>   >   category "+id +"</a>" +  '  >  product pid  ' + pid;
                document.getElementById("prod_info").innerHTML = this.responseText;
                document.getElementById("list").innerHTML = '';
            }
        };
        xhttp.open("GET", "product.php?pid="+pid +"&action=loadprod&nonce="+<?php echo getNonce('loadprod')?>, true);
        xhttp.send();


    }


    function GetRequest() {
        var url = location.search; //获取url中"?"符后的字串
        var theRequest = new Object();
        if (url.indexOf("?") != -1) {
            var str = url.substr(1);
            strs = str.split("&");
            for(var i = 0; i < strs.length; i ++) {
                theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
            }
            load_list(theRequest['catid']-1);
        }
        else
            return;
        //alert(theRequest['catid']);

    }


    function logout() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("user_name").innerHTML ="Guest";
            }
        };
        xhttp.open("GET", "auth-process.php?action=logout&nonce="+<?php echo getNonce('logout')?>, true);
        xhttp.send();
    }

    function login() {
        self.location='admin.php';
    }

    function myFunction()
    {



        //alert('before');
        //transfer pids and nums


        var em = document.getElementById("user_name").innerHTML;

        if(em == "Guest")
        {
            alert('please login first');
            return false;
        }

        //alert(em);
        var email=document.getElementById("m-em").value;
        //alert(email);
        var temp='';
        var jsonString = JSON.stringify(localStorage);
        //alert(jsonString.toString());
        if(localStorage.length == 0){
            alert("empty cart");
            return false;
        }
        var pid = parseInt(localStorage.key(0));
        temp+=pid + "-"+ parseInt(localStorage.getItem(pid));
        for(var i=1;i<localStorage.length;i++) {
            //alert(localStorage.key(i));
            var pid = parseInt(localStorage.key(i));
            temp+="-"+pid + "-"+ parseInt(localStorage.getItem(pid));
        }

        //alert(email);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //alert(this.responseText);
                var info = JSON.parse(this.response);
                document.getElementById("custom").value=info.custom;
                document.getElementById("invoice").value=info.invoice;
                //alert(document.getElementById("invoice").value);
                var tuple=info.hash_info.split('-');
                //alert(tuple);

                for(var i=localStorage.length-1;i>-1;i--) {
                    var list=document.getElementById("form");


                    var node4=document.createElement("input");
                    node4.name='amount_'+(i+1);
                    node4.type="hidden";
                    node4.setAttribute('value',tuple[3*i+2]);
                    //alert(parseInt(tuple[3*i+2]));
                    //node4.value=tuple[3*i-2];
                    document.getElementById("form").insertBefore(node4,list.childNodes[0]);
                    //alert(node4);

                    var node3=document.createElement("input");
                    node3.name='quantity_'+(i+1);

                    node3.type="hidden";
                    //node3.value=tuple[3*i-1];
                    node3.setAttribute('value',parseInt(tuple[3*i+1]));
                    document.getElementById("form").insertBefore(node3,list.childNodes[0]);

                    var node2=document.createElement("input");
                    node2.name='item_number_'+(i+1);
                    node2.type="hidden";
                    //node2.value=tuple[3*i-2];
                    node2.setAttribute('value',tuple[3*i+1]);
                    document.getElementById("form").insertBefore(node2,list.childNodes[0]);

                    var node1=document.createElement("input");
                    node1.name='item_name_'+(i+1);
                    node1.type="hidden";
                    //node1.value=tuple[3*i];
                    node1.setAttribute('value',tuple[3*i]);
                    document.getElementById("form").insertBefore(node1,list.childNodes[0]);

                }


                localStorage.clear();
                document.getElementById("form").submit();
            }
        };
        xhttp.open("GET", "checkout.php?data="+ temp+"&em="+email+"&user="+em+"&nonce="+<?php echo getNonce('checkout')?>, true);
        xhttp.send();


        return false;




    }
    //onsubmit="return myFunction();">
    //<input type="hidden" name="item_amount_1" value="2" />
    //"wangzhc08-facilitator@gmail.com" >



</script>
<nav>
    <p id = "total">Shopping cart</p>
    <ul id ="cart_list"></ul>

</nav>

<form method="POST"  id="form" action="https://www.sandbox.paypal.com/cgi-bin/webscr"  >
    <input type="hidden" name="cmd" value="_cart" >
    <input type="hidden" name="upload" value="1" >
    <input id="m-em" type="hidden" name="business" value="wangzhc08-facilitator@gmail.com" >
    <input type="hidden" name="currency_code" value="HKD" >
    <input type="hidden" name="charset" value="utf-8" >
    <input id="custom" type="hidden" name="custom" value="0" >
    <input id="invoice" type="hidden" name="invoice" value="0" >
</form>