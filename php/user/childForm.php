<?php
session_start();
/**
 * Created by IntelliJ IDEA.
 * User: TipsyCoder
 * Date: 9/15/15
 * Time: 11:53 PM
 */


header("Content-Type: text/html");

if(!isset($_SESSION['user'])) {
    header("Location:../../index.php");
} else if(isset($_SESSION['user']) && !isset($_GET)) {
    header("Location:search.php");
}

showHtml();


function showHtml() {
    $email = $_GET['em'];
    $fName = $_GET['fn'];
    $lName = $_GET['ln'];

    $html = "<head><title>Child Form - Big Yaad Tree</title><meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'>" .
        "<link rel=\"stylesheet\" href=\"../../css/reset.css\"><link rel=\"shortcut icon\" href=\"../../img/favicon.ico\" type=\"image/x-icon\">" .
        "<link rel=\"icon\" href=\"../../img/favicon.ico\" type=\"image/x-icon\"><script src='../../js/sweetalert.min.js'></script><script src='../../js/index.js'></script>" .
        "<link rel='stylesheet prefetch' href='../../css/font-awesome.min.css'><link rel='stylesheet' href='../../css/sweetalert.css'><link rel=\"stylesheet\" href=\"../../css/style.css\"><link rel=\"stylesheet\" href=\"../../css/animate.css\"></head>
        <div class=\"animated slideInDown\">
        <div class=\"nav\">
        <ul>
            <li class=\"logo\"><img class='animated infinite pulse' src=\"../../img/logo.png\"></li>
            <li class=\"home\"><a href=\"home.php\">HOME</a></li>
            <li class=\"searchMenu\"><a class=\"active\" href=\"search.php\">SEARCH</a></li>
            <li class=\"childMenu\"><a onClick='closeAccount()' href=\"#\">CLOSE ACCOUNT</a></li>
            <li class=\"logout\"><a href=\"../../index.php\">LOGOUT</a></li>
        </ul>
    </div></div>" .
        "<body><div class=\"module form-module\">" .
        "<div class=\" form\" style='display: block'>" .
        "<h2>CHILD FORM</h2>" .
        "<form class='animated zoomIn' action='prc_childForm.php' method='POST' name='childForm' id='childForm'>" .
        "<input type='email' id='uEmail' name='uEmail' value=$email placeholder='Enter Email Here' required readonly/>" .
        "<input type='text' id='uFName' name='uFName' value=$fName placeholder='Enter First Name Here' required readonly/>" .
        "<input type='text' id='uLName' name='uLName' value=$lName placeholder='Enter Last Name Here' required readonly/>" .
        "<div class=\"select-style\"><select name='uParent' required>
    <option value='' disabled selected>Choose Parent Type</option>
    <option value='Biological'>Biological</option>
    <option value='Non-Biological'>Non-Biological</option>
</select></div>" .
        "<button type='submit' id='uSbm' name='uSbm' value='lol'>ADD PICKNEY</button>" .
        "</form>" .
        "<script src='../../js/jquery-2.1.4.min.js'></script><script src=\"../../js/index.js\"></script>" .
        "</div></div>" .
        "</body>";


    echo $html;
}