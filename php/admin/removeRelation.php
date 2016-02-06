<?php
session_start();
/**
 * Created by PhpStorm.
 * User: TipsyCoder
 * Date: 11/1/15
 * Time: 11:19 AM
 */

require_once 'super_data.php';

if(!isset($_SESSION['user'])) {
    header("Location:../../index.php");
}

showHtml();


function showHtml() {
    $errMsgs = [];

    $html = "
    <head><title>Big Yaad Tree</title><meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'><script src=\"../../js/go.js\"></script><script id='code' src='../../js/tree.js'></script>" .
        "<link rel=\"stylesheet\" href=\"../../css/reset.css\"><link rel=\"shortcut icon\" href=\"../../img/favicon.ico\" type=\"image/x-icon\">" .
        "<link rel=\"icon\" href=\"../../img/favicon.ico\" type=\"image/x-icon\"><script src='../../js/sweetalert.min.js'></script><script src='../../js/jquery-2.1.4.min.js'></script><script src='../../js/index.js'></script>" .
        "<link rel='stylesheet prefetch' href='../../css/font-awesome.min.css'><link rel='stylesheet' href='../../css/sweetalert.css'><link rel=\"stylesheet\" href=\"../../css/style.css\"><link rel=\"stylesheet\" href=\"../../css/animate.css\"></head>
    <div class=\"animated slideInDown\">
    <div class=\"nav\">
        <ul>
            <li class=\"logo\"><img class='animated infinite pulse' src=\"../../img/logo.png\"></li>
            <li class=\"home\"><a href=\"super.php\">REMOVE USER</a></li>
            <li class=\"searchMenu\"><a class=\"active\" href=\"#\">REMOVE RELATIONSHIP</a></li>
            <li class=\"childMenu\"><a href=\"changePassword.php\">CHANGE PASSWORD</a></li>
            <li class=\"logout\"><a href=\"../../index.php\">LOGOUT</a></li>
        </ul>
    </div></div>" .
        "<body><div class=\"panel\" style='max-width: 550px;'>".
        createUserTable(getAllRelations(), $errMsgs) .
        "</div></body>";


    echo $html;
}


function createUserTable($users, &$errMsgs) {
    $htmlStr = "";

    $htmlStr .= "<div class='successFilterLbl' style='margin-top: 0; padding: 20px; text-align: center'>RELATIONSHIPS</div>";

    if(count($users) > 0) {
        $htmlStr .= "<div> <table style='max-width: 550px;'>";
        $htmlStr .= "<tr class='tableHeader'><td>PARENT</td><td>CHILD</td></tr>";
        for($i = 0; $i < count($users['pEmails']); $i++) {
            $pEmail = $users['pEmails'][$i];
            $cEmail = $users['cEmails'][$i];

            $htmlStr .= "<tr ><td style='padding-left: 5px'>$pEmail</td><td>$cEmail</td><td ><a onclick=\"removeRelation('$pEmail', '$cEmail')\"><button>REMOVE RELATION</button></a></td></tr>";
        }
        $htmlStr .= "</table></div>";
    } else {
        array_push($errMsgs, "No Record Found");
    }

    return $htmlStr;
}