<?php
/**
 * Created by PhpStorm.
 * User: TipsyCoder
 * Date: 11/17/15
 * Time: 3:01 PM
 */

require_once '../core/data.php';

if(isset($_SESSION['user']) && isset($_GET)) {
    if($_GET['opt'] === 'deleteUser') {
        deleteMe($_GET['em']);
    }
}

if(isset($_SESSION['user']) && isset($_GET)) {
    if($_GET['opt'] === 'deleteRelation') {
        deleteRelation($_GET['pEm'], $_GET['cEm']);
    }
}

if(isset($_SESSION['user']) && isset($_GET)) {
    if($_GET['opt'] === 'changePassword') {
        changePassword($_GET['em'], $_GET['pass']);
    }
}

function deleteRelation($pEmail, $cEmail) {
    $con = connectDatabase();
    $lastLogin = null;

    while(1) {
        $stmt = $con->prepare("CALL deleteRelationship(?,?)");
        $stmt->bind_param("ss", $pEmail, $cEmail);
        $stmt->execute();
        $stmt->close();

        break;
    }

    $con->close();
}

function changePassword($email, $pass) {
    $hash = hashPassword($pass);

    $con = connectDatabase();

    while(1) {
        $stmt = $con->prepare("CALL changePassword(?,?)");
        $stmt->bind_param("ss", $email, $hash);
        $stmt->execute();
        $stmt->close();

        break;
    }

    $con->close();
}