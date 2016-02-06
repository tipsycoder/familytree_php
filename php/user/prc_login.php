<?php
/**
 * Created by IntelliJ IDEA.
 * User: TipsyCoder
 * Date: 9/28/15
 * Time: 10:40 PM
 */


require_once '../core/util.php';
require_once '../core/data.php';

if($_POST) {
    $pageTitle = "Home";
    $htmlStr = "";

    if ($_POST['uSbm']) {
        $errMsgs = array();
        $email = "";
        $pass = "";
        $labelArr = array();
        $dataArr = array();
        $result = null;
        $redirectPage = "home.php";

        if(isset($_POST['uEmail']) && trim($_POST['uEmail'], " ") != "") {
            $email = $_POST['uEmail'];
        } else {
            array_push($errMsgs, "Email Invalid");
        }

        if(isset($_POST['uPass']) && trim($_POST['uPass'], " ") != "") {
            $pass = $_POST['uPass'];
        } else {
            array_push($errMsgs, "Password Empty");
        }

        if(count($errMsgs) === 0) {
            $result = checkUser($email, $pass, $errMsgs);

            if($result['success']) {
                if($result['type'] === "admin") {
                    $redirectPage = "../admin/super.php";
                }
            }
        }

        if(count($errMsgs) > 0) {
            $htmlStr .= generateErrorHtml($errMsgs);
        } else {
            header("Location:" . $redirectPage);
            $_SESSION['user'] = $result !== null ? $result : null;
        }
    }
}

showResultHtml($pageTitle, $htmlStr);