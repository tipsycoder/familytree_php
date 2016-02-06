<?php
session_start();
session_destroy();
/**
 * Created by IntelliJ IDEA.
 * User: TipsyCoder
 * Date: 9/15/15
 * Time: 11:24 PM
 */

header("Content-Type: text/html");

require_once 'php/user/signup.php';
require_once 'php/core/data.php';

showHtml();


function showHtml() {
    $html = "<head><title>Login - Big Yaad Tree</title><meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'><link rel=\"stylesheet\" href=\"css/reset.css\"><link rel=\"shortcut icon\" href=\"img/favicon.ico\" type=\"image/x-icon\">
    <link rel=\"icon\" href=\"img/favicon.ico\" type=\"image/x-icon\">
    <link rel='stylesheet prefetch' href='css/font-awesome.min.css'><link rel=\"stylesheet\" href=\"css/style.css\"><link rel=\"stylesheet\" href=\"css/animate.css\"></head><header>
    <div class=\"animated fadeIn\">
    <div class=\"nav\">
        <ul>
            <li class=\"logo\"><img class=\"animated lightSpeedIn\" src=\"img/logo.png\" style='height: 40px'></li>
            <li class=\"home\"><a class=\"active\" href=\"#\">HOME</a></li>
            <li class=\"searchMenu\"><a href=\"search.php\">SEARCH</a></li>
        </ul>
    </div>
    </div>
</header>" .
        "<div class=\"content\"></div><body><div class=\"module form-module\"><div class=\"toggle\"><i class=\"fa fa-times fa-pencil\" style='margin-top: 30%;'></i>
    <div class=\"tooltip\" style='top: 5px; right: -68px;' >Sign Me Up</div>
</div><div class=\"form\"><h2>PLEASE LOGIN</h2><form class='animated zoomIn' action='php/user/prc_login.php' method='POST' name='loginForm' id='loginForm'>" .
        "<input type='email' id='uEmail' name='uEmail' placeholder='Enter Email Here' required />" .
        "<input type='password' id='uPass' name='uPass' placeholder='Enter Password Here' minlength='6' required />" .
        "<button type='submit' id='uSbm' name='uSbm' value='lol'>LOGIN</button>" .
        "</form></div>" . getSignUpHtml().
        "</div>" .
        "</div><script src='js/jquery-2.1.4.min.js'></script><script src=\"js/index.js\"></script></body></div>" .
        "";

    echo $html;
    getLatestMembersList();
}

function getLatestMembersList() {
    $resultArray = getLatestMembers(10);
    $htmlStr = "";

    $htmlStr .= "<div class='animated zoomInUp lateMembers'><table>
    <tr><th>Latest Members</th></tr>";

    for($i = 0; $i < count($resultArray); $i++) {
        $htmlStr .= "<tr><td><a href='tree.php?email=" . $resultArray[$i]['email'] . "'>" . $resultArray[$i]['firstName'] . " " . $resultArray[$i]['lastName'] . "</a></td></tr>";
    }

    $htmlStr .= "</table></div>";
    echo $htmlStr;
}