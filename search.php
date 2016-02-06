<?php
session_start();

/**
 * Created by IntelliJ IDEA.
 * User: TipsyCoder
 * Date: 9/15/15
 * Time: 11:57 PM
 */


header("Content-Type: text/html");

showHtml();


function showHtml() {
    $searchValue = $_SESSION['searchValue'];
    $html = "
    <head><title>Search - Big Yaad Tree</title><meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'>" .
        "<link rel=\"stylesheet\" href=\"css/reset.css\"><link rel=\"shortcut icon\" href=\"img/favicon.ico\" type=\"image/x-icon\">" .
        "<link rel=\"icon\" href=\"img/favicon.ico\" type=\"image/x-icon\"><script src='js/jquery-2.1.4.min.js'></script><script src='js/index.js'></script>" .
        "<link rel='stylesheet prefetch' href='css/font-awesome.min.css'><link rel=\"stylesheet\" href=\"css/style.css\"><link rel=\"stylesheet\" href=\"css/animate.css\"></head>
        <div class=\"animated slideInDown\">
        <div class=\"nav\">
        <ul>
            <li class=\"logo\"><img class=\"animated lightSpeedIn\" src=\"img/logo.png\" style='height: 40px'></li>
            <li class=\"home\"><a href=\"index.php\">HOME</a></li>
            <li class=\"searchMenu\"><a class=\"active\" href=\"#\">SEARCH</a></li>
        </ul>
    </div></div>" .
        "<body><div class=\"module form-module\" style='max-width:550px'>" .
            "<div class=\" form\" style='display: block'>" .
                "<h2>WHO TO SEARCH FOR</h2>" .
                "<form class='animated zoomIn' action='prc_search.php' method='GET' name='searchForm' id='searchForm'>" .
                "<input type='text' id='uSearch' name='uSearch' placeholder='Enter Search Value Here' value = '$searchValue' required />" .
                getOptionsSelect() .
                "<button type='submit' id='uSbm' name='uSbm' value='lol'>SEARCH</button>" .
                "</form>" .
                "<script src='js/jquery-2.1.4.min.js'></script><script src=\"/js/index.js\"></script>" .
            "</div></div><div style='padding-bottom: 100px'><div class=\"searchTable\" style='max-width: 550px'>". $_SESSION['htmlResult'] . "</div></div>" .
        "</body>";


    echo $html;
    //$_SESSION['searchValue'] = "";
    //$_SESSION['htmlResult'] = "";
    //$_SESSION['searchFilter'] = "";
}

function getOptionsSelect() {
    $option = $_SESSION['searchFilter'];
    $fn = $option === 'fn' ? 'selected' : null;
    $ln = $option === 'ln' ? 'selected' : null;
    $em = $option === 'em' ? 'selected' : null;
    $als = $option === 'als' ? 'selected' : null;
    $all = $option === 'all' ? 'selected' : null;

    $htmlSelect = "<div class=\"select-style\"><select name='uOptions'>";
    $htmlSelect .= "<option value='fn' $fn>By First Name</option>
    <option value='ln' $ln>By Last Name</option>
    <option value='em' $em>By Email</option>
    <option value='als' $als>By Aliases</option>
    <option value='all' $all>By All Fields</option>
</select></div>";

    return $htmlSelect;
}