<?php
session_start();
/**
 * Created by IntelliJ IDEA.
 * User: TipsyCoder
 * Date: 9/28/15
 * Time: 10:40 PM
 */

require_once '../core/util.php';
require_once '../core/data.php';

if(!isset($_SESSION['user'])) {
    header("Location:../../index.php");
}

if($_POST) {
    $pageTitle = "Success";
    $htmlStr = "";

    if ($_POST['uSbm']) {
        $errMsgs = array();
        $email = "";
        $parent = "";
        $fName = "";
        $lName = "";
        $labelArr = array();
        $dataArr = array();
        $user = $_SESSION['user'];

        if(isset($_POST['uEmail']) && trim($_POST['uEmail'], " ") != "") {
            if(filter_var($_POST['uEmail'], FILTER_VALIDATE_EMAIL) !== false) {
                $email = $_POST['uEmail'];
                array_push($labelArr, "EMAIL");
                array_push($dataArr, $email);
            } else {
                array_push($errMsgs, "Invalid Email Address");
            }
        } else {
            array_push($errMsgs, "Email Address Empty");
        }

        if(isset($_POST['uFName']) && trim($_POST['uFName'], " ") != "" && ctype_alpha($_POST['uFName'])) {
            $fName = $_POST['uFName'];
            array_push($labelArr, "FIRST NAME");
            array_push($dataArr, $fName);
        } else {
            array_push($errMsgs, "First Name Empty");
        }

        if(isset($_POST['uLName']) && trim($_POST['uLName'], " ") != "" && ctype_alpha($_POST['uLName'])) {
            $lName = $_POST['uLName'];
            array_push($labelArr, "LAST NAME");
            array_push($dataArr, $lName);
        } else {
            array_push($errMsgs, "Last Name Empty");
        }

        if(isset($_POST['uParent']) && trim($_POST['uParent'], " ") != "") {
            $parent = $_POST['uParent'];

            if(strtolower($parent) === "biological" || strtolower($parent) === "non-biological") {
                array_push($labelArr, "PARENT-TYPE");
                array_push($dataArr, $parent);
            } else {
                array_push($errMsgs, "Parent-Type Invalid");
            }
        } else {
            array_push($errMsgs, "Parent-Type Empty");
        }

        if(strtolower($user['email']) === strtolower($email)) {
            array_push($errMsgs, "You Can't Parent Yourself");
        }

        if(count($errMsgs) <= 0) {
            $conn = connectDatabase();

            while (1) {

                $stmt = $conn->prepare("call isRelationshipExist(?,?)");
                $stmt->bind_param("ss", $user['email'] ,$email);
                $stmt->execute();
                if (check_error($stmt->errno, $stmt->error, $errMsgs)) break;

                $stmt->bind_result($retVal);
                $stmt->fetch();

                if($retVal == 1) {
                    array_push($errMsgs, "Parent-Child Relationship already exist");
                    break;
                }

                $stmt->close();
                $stmt = null;

                $stmt = $conn->prepare("call passParentLimit(?)");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                if (check_error($stmt->errno, $stmt->error, $errMsgs)) break;

                $stmt->bind_result($retVal);
                $stmt->fetch();

                if($retVal == 1) {
                    array_push($errMsgs, "This Child Already Have Two Parents.");
                    break;
                }

                $stmt->close();
                $stmt = null;

                $stmt = $conn->prepare("call createRelationship(?,?,?)");
                $stmt->bind_param("sss", $user['email'] ,$email, $parent);
                $stmt->execute();
                echo $stmt->error;
                if (check_error($stmt->errno, $stmt->error, $errMsgs)) break;

                $stmt->close();
                break;
            }

            closeDatabase($conn);
        }

        if (count($errMsgs) > 0) {
            $htmlStr .= "<div class='errorLbl'>ERROR FOUND</div>";
            for ($i = 0; $i < count($errMsgs); $i++) {
                $htmlStr .= "<label><div class='passLbl'>" . $errMsgs[$i] . "</div></label><br>";
                $pageTitle = "Error";
            }
            $htmlStr .= "<a href='search.php'><button>SEARCH AGAIN</button></a>";
        } else {
            $htmlStr .= "<div class='successLbl'>CHILD ADD SUCCESS</div>";
            $htmlStr .= getTableLabelData($labelArr, $dataArr);
            $htmlStr .= "<a href='search.php'><button>SEARCH AGAIN</button></a>";
        }
    }
}

showResultHtml($pageTitle, $htmlStr);