<?php
/**
 * Created by PhpStorm.
 * User: TipsyCoder
 * Date: 11/15/15
 * Time: 10:23 PM
 */


require_once 'php/core/data.php';
require_once 'php/core/util.php';

header("Content-Type: text/html");


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
            showResultHtml("Error", generateErrorHtml($errMsgs), true);
        }
    } else {

        showResultHtml("Error", generateErrorHtml($errMsgs), true);
    }

}


function showHtml($userInfo) {
    $test = getTreeArray(generateTreeJson($userInfo));
    $html = "<head><title>View - Big Yaad Tree</title><meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'><link rel=\"stylesheet\" href=\"css/reset.css\"><link rel=\"shortcut icon\" href=\"img/favicon.ico\" type=\"image/x-icon\">
    <link rel=\"icon\" href=\"img/favicon.ico\" type=\"image/x-icon\"><script src=\"js/go.js\"></script><script id='code' src='js/tree.js'></script>
    <link rel='stylesheet prefetch' href='css/font-awesome.min.css'><link rel=\"stylesheet\" href=\"css/style.css\"><link rel=\"stylesheet\" href=\"css/animate.css\"></head><header>
    <div class=\"animated fadeIn\">
    <div class=\"nav\">
        <ul>
            <li class=\"logo\"><img class=\"animated lightSpeedIn\" src=\"img/logo.png\" style='height: 40px'></li>
            <li class=\"home\"><a href=\"index.php\">HOME</a></li>
            <li class=\"searchMenu\"><a class=\"active\" href=\"search.php\">SEARCH</a></li>
        </ul>
    </div></div>" .
        "<body onload=init('". $test . "');>".
        userDetailsHtml($userInfo) .
        "<div id=\"myDiagram\"></div>".
        "</body>";


    echo $html;
}