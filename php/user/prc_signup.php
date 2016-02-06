<?php
/**
 * Created by IntelliJ IDEA.
 * User: TipsyCoder
 * Date: 9/28/15
 * Time: 10:41 PM
 */

require_once '../core/util.php';
require_once '../core/data.php';

include_once '../../securimage/securimage.php';

$securimage = new Securimage();

if($_POST) {
    $pageTitle = "Success";
    $htmlStr = "";

    if($_POST['uSbm']) {
        $errMsgs = array();
        $email = "";
        $pass1 = "";
        $pass2 = "";
        $fName = "";
        $lName = "";
        $mNames = array();
        $aliases = array();
        $nation = "";
        $dob = array();
        $labelArr = array();
        $dataArr = array();

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


        if(!isset($_POST['uPass']) && preg_grep("/[^ ]/g", $_POST['uPass'])) {
            array_push($errMsgs, "Password Field Empty");
        } else {
            if(strlen($_POST['uPass']) < 6) {
                array_push($errMsgs, "Password Field Below 6 Characters");
            } else {
                if (!isset($_POST['uRPass']) && trim($_POST['uRPass'], " ") != "") {
                    array_push($errMsgs, "Repeat Password Field Empty");
                } else {
                    $pass1 = $_POST['uPass'];
                    $pass2 = $_POST['uRPass'];

                    if ($pass1 !== $pass2) {
                        array_push($errMsgs, "Password Mismatch");
                    } else {
                        array_push($labelArr, "PASSWORD");
                        array_push($dataArr, $pass1);
                    }
                }
            }
        }

        if(isset($_POST['uFName']) && trim($_POST['uFName'], " ") != "" && ctype_alpha($_POST['uFName'])) {
            $fName = $_POST['uFName'];
            array_push($labelArr, "FIRST NAME");
            array_push($dataArr, $fName);
        } else {
            array_push($errMsgs, "First Name Invalid");
        }

        if(isset($_POST['uLName']) && trim($_POST['uLName'], " ") != "" && ctype_alpha($_POST['uLName'])) {
            $lName = $_POST['uLName'];
            array_push($labelArr, "LAST NAME");
            array_push($dataArr, $lName);
        } else {
            array_push($errMsgs, "Last Name Invalid");
        }

        if(isset($_POST['uMNames']) && trim($_POST['uMNames'], " ") != "") {
            $dataObj = explodeNames($_POST['uMNames'], ";");
            if($dataObj['errMsg'] === "") {
                $mNames = $dataObj['data'];
                array_push($labelArr, "MIDDLE NAME");
                array_push($dataArr, $mNames);
            } else {
                array_push($errMsgs, "Middle Name Invalid");
            }
        }

        if(isset($_POST['uAliases']) && trim($_POST['uAliases'], " ") != "") {
            $dataObj = explodeNames($_POST['uAliases'], ";");
            if($dataObj['errMsg'] === "") {
                $aliases = $dataObj['data'];
                array_push($labelArr, "ALIASES");
                array_push($dataArr, $aliases);
            } else {
                array_push($errMsgs, "Aliases Invalid");
            }
        }

        if(isset($_POST['uNation']) && trim($_POST['uNation'], " ") != "") {
            $nation = $_POST['uNation'];
            array_push($labelArr, "NATIONALITY");
            array_push($dataArr, $nation);
        } else {
            array_push($errMsgs, "Nationality Empty");
        }

        if(isset($_POST['uDOB']) && trim($_POST['uDOB'], " ") != "") {
            $start_date = '1916-01-01';

            $time = strtotime("-1 month", time());
            $end_date = date("Y-m-d", $time);
            $dob = $_POST['uDOB'];

            if(check_in_range($start_date, $end_date, $dob)) {
                array_push($labelArr, "DOB");
                array_push($dataArr, $dob);
            } else {
                array_push($errMsgs, "Date Not In Range (1916 - Present)");
            }
        } else {
            array_push($errMsgs, "Date of Birth Empty");
        }

        if ($securimage->check($_POST['captcha_code']) == false) {
            // the code was incorrect
            // you should handle the error so that the form processor doesn't continue

            // or you can use the following code if there is no validation or you do not know how
            array_push($errMsgs, "The security code entered was incorrect.");
        }

        if(count($errMsgs) <= 0) {
            $conn = connectDatabase();

            while(1) {
                $hash = hashPassword($pass1);

                $stmt = $conn->prepare("call insertUser(?,?,?,?,?,?)");
                $stmt->bind_param("ssssss", $email, $hash, $fName, $lName, $dob, $nation);
                $stmt->execute();
                if (check_error($stmt->errno, $stmt->error, $errMsgs)) break;
                $stmt->close();

                if(!middleOrAlias($conn, "insertMiddleName", $email, $mNames, $errMsgs)) break;
                if(!middleOrAlias($conn, "insertAliasName", $email, $aliases, $errMsgs)) break;

                break;
            }
            closeDatabase($conn);
        }

        if(count($errMsgs) > 0) {
            $htmlStr .= generateErrorHtml($errMsgs);
        } else {
            $htmlStr .= "<script> goodSweet('SIGN UP SUCCESS', '') </script><div class='successLbl'>SIGN UP SUCCESS</div>";
            $htmlStr .= getTableLabelData($labelArr, $dataArr);
            $htmlStr .= "<a href='../../index.php'><button>LOGIN</button></a>";
        }
    }
}

function middleOrAlias($conn, $procName, $email, $nameArray, &$errMsgs) {
    if(count($nameArray) > 0) {
        $stmt = $conn->prepare("call $procName(?, ?)");
        for ($r = 0; $r < count($nameArray); $r++) {
            $stmt->bind_param("ss", $email, $nameArray[$r]);
            $stmt->execute();
        }
        if (check_error($stmt->errno, $stmt->error, $errMsgs)) return false;
        $stmt->close();
    }

    return true;
}

showResultHtml($pageTitle, $htmlStr);





