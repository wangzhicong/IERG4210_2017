<!doctype html>



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
        left: 75%;
        top: 300px;
        z-index: 100;
        color: white;
    }
    nav ol{display:none;position:absolute;margin:-20px;width: 200px; color: black;
        }
    button{
        position: relative ;right: 0px;bottom: 0px;
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
    nav:hover ol{display:block;background-color: #ffffff}
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
        left:10%;bottom:0%;width:80%;height:60px;background-color: #888888;}
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

<body   onload="load_cats();GetRequest()"></body>

<h1 class="title" id="demo"><img src="img/three%20bros.gif">wangzhicong's weapon store</h1>
<h3 id="nave">
    <a onclick='home_page()'>home</a>
</h3>

<ul class="cats" id ="cat_list" ></ul>
<ul class="table" id="list"></ul>


<nav>
    <p>shopping list</p>
    <ol>
        <li>item1   $33<input id="num_1" name="num_1" placeholder="1"></li>
        <li>item2   $33<input id="num_2" name="num_2" placeholder="1"></li>
        <li>item3   $33<input id="num_3" name="num_3" placeholder="1"></li>
        <a href="https://www.paypal.com"><button>Checkout</button></a>
    </ol>

</nav>

<table id="prod_info">

</table>

<footer class="foot">more info</footer>


<script>
    //var url = window.location.href;
    //alert(url);
    function home_page(){
        document.getElementById("prod_info").innerHTML ='';
        document.getElementById("list").innerHTML = '<li>Home page with nothing</li>';
        document.getElementById("nave").innerHTML ="<a onclick=\'home_page()\'>home</a>";
        var url = window.location.href;


        var tmp = url.replace(/\?catid=[\d]/gi,"");


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
        xhttp.open("GET", "product.php?action=load_cat", true);
        xhttp.send();


    }



    function load_list(id) {
        var xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var url = window.location.href;

                //var tmp = url.replace(/&prod=[\w\-\s]*/gi,"");
                var tmp = url.replace(/\?catid=[\d]*/gi,"");
                history.replaceState(null,url,tmp+'?catid='+(id+1));


                //location.href = '?catid='+(id+1);
                document.getElementById("prod_info").innerHTML ='';
                document.getElementById("list").innerHTML = '';
                document.getElementById("list").innerHTML = this.responseText;
                document.getElementById("nave").innerHTML = '';
                document.getElementById("nave").innerHTML = "<a onclick=\'home_page()\'>home</a>" + '   >   category ' + (id+1);

            }
        };
        xhttp.open("GET", "product.php?catid="+id+"&action=load_list", true);
        xhttp.send();


    }

    function load_prod(id,prod_name) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //window.location.href = url + '?catid='+(id+1)+'&prod='+prod_name;
                //var url = window.location.href;
                //var tmp = url.replace(/&prod=[\w\-\s]*/gi,"");
                //history.replaceState(null,url,tmp+'&prod='+prod_name);

                document.getElementById("nave").innerHTML = '';
                document.getElementById("nave").innerHTML = "<a onclick=\'home_page()\'>home</a>" + "<a onclick=\'load_list("+ (id-1) +")\'>   >   category "+id +"</a>" +  '  >   ' + prod_name;
                document.getElementById("prod_info").innerHTML = this.responseText;
                document.getElementById("list").innerHTML = '';
            }
        };
        xhttp.open("GET", "product.php?product="+prod_name +"&action=load_prod", true);
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
        }
        //alert(theRequest['catid']);
        load_list(theRequest['catid']-1);
    }





</script>