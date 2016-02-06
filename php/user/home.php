<?php
/**
 * Created by IntelliJ IDEA.
 * User: TipsyCoder
 * Date: 10/1/15
 * Time: 3:15 PM
 */

require_once '../core/data.php';
require_once '../core/util.php';

header("Content-Type: text/html");


if(!isset($_SESSION['user'])) {
    header("Location:../../index.php");
}

//var_dump(json_encode(getTreeArray(generateTreeJson($_SESSION['user']))), JSON_PRETTY_PRINT);
//die();
showHtml();


function showHtml() {
    $test = getTreeArray(generateTreeJson($_SESSION['user']));
    $html = "
    <head><title>Big Yaad Tree</title><meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'><script src=\"../../js/go.js\"></script><script id='code' src='../../js/tree.js'></script>" .
        "<link rel=\"stylesheet\" href=\"../../css/reset.css\"><link rel=\"shortcut icon\" href=\"../../img/favicon.ico\" type=\"image/x-icon\">" .
        "<link rel=\"icon\" href=\"../../img/favicon.ico\" type=\"image/x-icon\"><script src='../../js/sweetalert.min.js'></script><script src='../../js/jquery-2.1.4.min.js'></script><script src='../../js/index.js'></script><script src='../../js/home.js'></script>" .
        "<link rel='stylesheet prefetch' href='../../css/font-awesome.min.css'><link rel='stylesheet' href='../../css/sweetalert.css'><link rel=\"stylesheet\" href=\"../../css/style.css\"><link rel=\"stylesheet\" href=\"../../css/animate.css\"></head>
    <div class=\"animated slideInDown\">
    <div class=\"nav\">
        <ul>
            <li class=\"logo\"><img class='animated infinite pulse' src=\"../../img/logo.png\"></li>
            <li class=\"home\"><a class=\"active\" href=\"#\">HOME</a></li>
            <li class=\"searchMenu\"><a href=\"search.php\">SEARCH</a></li>
            <li class=\"childMenu\"><a onClick='closeAccount()' href=\"#\">CLOSE ACCOUNT</a></li>
            <li class=\"logout\"><a href=\"../../index.php\">LOGOUT</a></li>
        </ul>
    </div></div>" .
        "<body onload=init('". $test . "');>".
        userDetailsHtml($_SESSION['user'], "MY INFO") .
        "<div id=\"myDiagram\"></div>".
        "</body>";


    echo $html;
}