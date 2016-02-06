<?php
/**
 * Created by PhpStorm.
 * User: TipsyCoder
 * Date: 11/15/15
 * Time: 10:06 PM
 */

require_once '../core/data.php';
require_once '../core/util.php';

header("Content-Type: text/html");


if(!isset($_SESSION['user'])) {
    header("Location:../../index.php");
}


if(isset($_GET)) {
    $errMsgs = array();
    $email = "";

    if(isset($_GET['email']) && trim($_GET['email'], " ") != "") {
        if(filter_var($_GET['email'], FILTER_VALIDATE_EMAIL) !== false) {
            $email = $_GET['email'];
        } else {
            array_push($errMsgs, "Invalid Email Address");
        }
    } else {
        array_push($errMsgs, "Email Address Empty");
    }

    if(count($errMsgs) <= 0) {
        $result = getUserInfo($email, $errMsgs);

        if($result['success']) {
            showHtml($result);
        } else {
            generateErrorHtml($errMsgs);
            showResultHtml("Error", generateErrorHtml($errMsgs));
        }
    } else {

        showResultHtml("Error", generateErrorHtml($errMsgs));
    }

}


function showHtml($userInfo) {
    $test = getTreeArray(generateTreeJson($userInfo));
    $html = "
    <head><title>Big Yaad Tree</title><meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'><script src=\"../../js/go.js\"></script><script id='code' src='../../js/tree.js'></script>" .
        "<link rel=\"stylesheet\" href=\"../../css/reset.css\"><link rel=\"shortcut icon\" href=\"../../img/favicon.ico\" type=\"image/x-icon\">" .
        "<link rel=\"icon\" href=\"../../img/favicon.ico\" type=\"image/x-icon\"><script src='../../js/sweetalert.min.js'></script><script src='../../js/index.js'></script>" .
        "<link rel='stylesheet prefetch' href='../../css/font-awesome.min.css'><link rel=\"stylesheet\" href=\"../../css/style.css\"><link rel=\"stylesheet\" href=\"../../css/animate.css\"></head>
    <div class=\"animated slideInDown\">
    <div class=\"nav\">
        <ul>
            <li class=\"logo\"><img class='animated infinite pulse' src=\"../../img/logo.png\"></li>
            <li class=\"home\"><a  href=\"home.php\">HOME</a></li>
            <li class=\"searchMenu\"><a class=\"active\" href=\"search.php\">SEARCH</a></li>
            <li class=\"childMenu\"><a href=\"#\">CLOSE ACCOUNT</a></li>
            <li class=\"logout\"><a href=\"../../index.php\">LOGOUT</a></li>
        </ul>
    </div></div>" .
        "<body onload=init('". $test . "');>".
        userDetailsHtml($userInfo) .
        "<div id=\"myDiagram\"></div>".
        "</body>";


    echo $html;
}