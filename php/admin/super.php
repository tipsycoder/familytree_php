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
    $nextSort = "";
    $nextSortBy = "";

    $html = "
    <head><title>Big Yaad Tree</title><meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'><script src=\"../../js/go.js\"></script><script id='code' src='../../js/tree.js'></script>" .
        "<link rel=\"stylesheet\" href=\"../../css/reset.css\"><link rel=\"shortcut icon\" href=\"../../img/favicon.ico\" type=\"image/x-icon\">" .
        "<link rel=\"icon\" href=\"../../img/favicon.ico\" type=\"image/x-icon\"><script src='../../js/sweetalert.min.js'></script><script src='../../js/jquery-2.1.4.min.js'></script><script src='../../js/index.js'></script>" .
        "<link rel='stylesheet prefetch' href='../../css/font-awesome.min.css'><link rel='stylesheet' href='../../css/sweetalert.css'><link rel=\"stylesheet\" href=\"../../css/style.css\"><link rel=\"stylesheet\" href=\"../../css/animate.css\"></head>
    <div class=\"animated slideInDown\">
    <div class=\"nav\">
        <ul>
            <li class=\"logo\"><img class='animated infinite pulse' src=\"../../img/logo.png\"></li>
            <li class=\"home\"><a class=\"active\" href=\"#\">REMOVE USER</a></li>
            <li class=\"searchMenu\"><a href=\"removeRelation.php\">REMOVE RELATIONSHIP</a></li>
            <li class=\"childMenu\"><a href=\"changePassword.php\">CHANGE PASSWORD</a></li>
            <li class=\"logout\"><a href=\"../../index.php\">LOGOUT</a></li>
        </ul>
    </div></div>" .
        "<body><div class=\"panel\" style='max-width: 550px;'>";


    if(!isset($_GET) || (strtoupper($_GET['by']) !== "FNAME" && strtoupper($_GET['by']) !== 'LNAME')) {
        $_GET['by'] = 'FNAME';
        $nextSortBy = 'FNAME';
    } else if(strtoupper($_GET['by']) === "LNAME") {
        $nextSortBy = "LNAME";
    } else {
        $nextSortBy = "FNAME";
    }

    if(!isset($_GET) || (strtoupper($_GET['sort']) !== "ASC" && strtoupper($_GET['sort']) !== 'DESC')) {
        $_GET['sort'] = 'DESC';
        $nextSort = "ASC";
    } else if(strtoupper($_GET['sort']) === "ASC") {
        $nextSort = "DESC";
    } else {
        $nextSort = "ASC";
    }



    $html .= createUserTable(getAllUsers($_GET['sort'], $_GET['by']), $errMsgs, $nextSort, $nextSortBy) .
        "</div></body>";


    echo $html;
}


function createUserTable($users, &$errMsgs, $sort, $sortBy) {
    $fNameCaret = "fa-caret-up";
    $lNameCaret = "fa-caret-up";

    if($sort === "ASC" && $sortBy === 'FNAME') {
        $fNameCaret = "fa-caret-down";
    } else if($sort === "ASC" && $sortBy === 'LNAME') {
        $lNameCaret = "fa-caret-down";
    }
    $htmlStr = "";

    $htmlStr .= "<div class='successFilterLbl' style='margin-top: 0; padding: 20px; text-align: center'>USERS</div>";

    if(count($users) > 0) {
        $htmlStr .= "<div> <table style='max-width: 550px;'>";
        $htmlStr .= "<tr class='tableHeader'><td>EMAIL</td><td id='fNameSort' onclick='nameSort(\"$sort\" , \"FNAME\")'>FIRST NAME". getSpaces(2) ."<i class='fa $fNameCaret'></i></td><td id='lNameSort' onclick='nameSort(\"$sort\" , \"LNAME\")'>LAST NAME". getSpaces(2) ."<i class='fa $lNameCaret'></i></td></tr>";
        for($i = 0; $i < count($users['emails']); $i++) {
            $email = $users['emails'][$i];
            $fName = $users['fNames'][$i];
            $lName = $users['lNames'][$i];

            $htmlStr .= "<tr ><td style='padding-left: 5px'>$email</td><td>$fName</td><td>$lName</td><td ><a onclick=\"removeUser('$email', '$fName $lName')\"><button>REMOVE</button></a></td></tr>";
        }
        $htmlStr .= "</table></div>";
    } else {
        array_push($errMsgs, "No Record Found");
    }

    return $htmlStr;
}