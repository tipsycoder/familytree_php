<?php
/**
 * Created by PhpStorm.
 * User: TipsyCoder
 * Date: 11/17/15
 * Time: 11:44 AM
 */

require_once '../core/data.php';
require_once '../core/util.php';


function getAllUsers($sort = 'DESC', $sortBy = 'FNAME') {
    $conn = connectDatabase();
    $email = "";
    $fName = "";
    $lName = "";
    $resultArray = [];
    $resultArray['emails'] = [];
    $resultArray['fNames'] = [];
    $resultArray['lNames'] = [];



    $stmt = $conn->prepare("CALL getUsers(?,?)");
    $stmt->bind_param("ss", $sort, $sortBy);
    $stmt->execute();
    $stmt->bind_result($email, $fName, $lName);

    while($stmt->fetch()) {
        array_push($resultArray['emails'], $email);
        array_push($resultArray['fNames'], $fName);
        array_push($resultArray['lNames'], $lName);
    }

    return $resultArray;
}

function getAllRelations() {
    $conn = connectDatabase();
    $pEmail = "";
    $cEmail = "";
    $resultArray = [];
    $resultArray['pEmails'] = [];
    $resultArray['cEmails'] = [];

    $stmt = $conn->prepare("CALL getRelations()");
    $stmt->execute();
    $stmt->bind_result($pEmail, $cEmail);

    while($stmt->fetch()) {
        array_push($resultArray['pEmails'], $pEmail);
        array_push($resultArray['cEmails'], $cEmail);
    }

    return $resultArray;
}