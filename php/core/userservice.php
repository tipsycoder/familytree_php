<?php
/**
 * Created by PhpStorm.
 * User: TipsyCoder
 * Date: 11/17/15
 * Time: 11:25 PM
 */

require_once '../core/data.php';

if(isset($_SESSION['user']) && isset($_GET)) {
    $opt = $_GET['opt'];
    $email = $_SESSION['user']['email'];

    if($opt === "update") {
        $field = $_GET['field'];
        $result = "";
        $errMsg = "Something Went Wrong!";
        $success = false;
        $value = $_GET['value'];
        switch ($field) {
            case 'fName':
                $success = updateUserField($email, $value, "updateFirstName", $errMsg);
                if($success) {
                    $_SESSION['user']['firstName'] = $value;
                }
                break;
            case 'lName':
                $success = updateUserField($email, $_GET['value'], "updateLastName", $errMsg);
                if($success) {
                    $_SESSION['user']['lastName'] = $value;
                }
                break;
            case 'dob':
                $success = updateUserField($email, $_GET['value'], "updateDOB", $errMsg);
                if($success) {
                    $_SESSION['user']['dob'] = $value;
                }
                break;
            case 'nation':
                $success = updateUserField($email, $_GET['value'], "updateNationName", $errMsg);
                if($success) {
                    $_SESSION['user']['nationality'] = $value;
                }
                break;
        }

        $retJson = jsonResult($success, $errMsg);

        echo $retJson;
    }
}

function updateUserField($email, $value, $procName, &$errMsg = "") {
    $returnVal = true;

    $conn = connectDatabase();

    $stmt = $conn->prepare("Call $procName(?,?)");
    $stmt->bind_param("ss", $email, $value);
    $stmt->execute();

    if($stmt->errno !== 0) {
        $returnVal = false;
    }

    $errMsg = $stmt->error;

    $stmt->close();

    closeDatabase($conn);

    return $returnVal;
}

function jsonResult($didSuccess, $msg) {
    $mBool = "false";
    if($didSuccess) {
        $mBool = "true";
    }

    //if($msg !== "")
        //$msg = "Contact Admin";

    return '{"success" : ' . $mBool . ', "errMsg" : "' . $msg  .'"}';
}