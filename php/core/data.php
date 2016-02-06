<?php
ob_start();
session_start();
session_regenerate_id();
/**
 * Created by IntelliJ IDEA.
 * User: TipsyCoder
 * Date: 10/3/15
 * Time: 3:10 PM
 */


//TODO FIX THIS

if(isset($_SESSION['user']) && isset($_GET)) {
    if($_GET['opt'] === 'deleteMe') {
        deleteMe($_SESSION['user']['email']);
        session_destroy();
    }
}

function connectDatabase() {
    $DB = null;

    $config = array(
        'host' => 'localhost',
        'username' => 'we_tipsy',
        'password' => '123456',
        'dbname' => 'bigyaadtree'
    );

    $DB = new mysqli($config['host'], $config['username'], $config['password'], $config['dbname']);

    if($DB->connect_error) {
        die('Connected failed: ' . $DB->connect_error);
    }

    return $DB;
 }

function closeDatabase($DB) {
    $DB->close();
    $DB = null;
}

function getUserInfo($email, &$errMsgs) {
    $result = array();

    $result['success'] = false;

    $con = connectDatabase();

    while(1) {
        $stmt = $con->prepare("CALL getUser(?)");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if (check_error($stmt->errno, $stmt->error, $errMsgs)) break;
        $stmt->bind_result($returnData);
        $stmt->fetch();

        $returnData = json_decode($returnData, true);

        if($returnData['success'] == true) {
            $result['success'] = true;
            $result['type'] = $returnData['type'];
            $result['email'] = $email;
            if ($returnData['type'] === 'user') {
                $result['firstName'] = $returnData['firstName'];
                $result['lastName'] = $returnData['lastName'];
                $result['nationality'] = $returnData['nationality'];
                $result['dob'] = $returnData['dob'];
                $result['middleName'] = getUserMiddleNames($email);
                $result['aliasName'] = getUserAliasNames($email);
            }
        }

        $stmt->close();

        break;
    }

    closeDatabase($con);

    return $result;

}

function _updateUserLastLogin($email) {
    $con = connectDatabase();
    $lastLogin = null;

    while(1) {
        $stmt = $con->prepare("CALL updateLastLogin(?)");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($lastLogin);
        $stmt->fetch();
        $stmt->close();

        break;
    }

    $con->close();
}

function deleteMe($email) {
    $con = connectDatabase();
    $lastLogin = null;

    while(1) {
        $stmt = $con->prepare("CALL deleteUser(?)");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->close();

        break;
    }

    $con->close();
}
function checkUser($email, $password, &$errMsgs) {
    $result = array();
    $returnData = "";

    $result['success'] = false;

    $con = connectDatabase();

    while(1) {
        $stmt = $con->prepare("CALL getUser(?)");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if (check_error($stmt->errno, $stmt->error, $errMsgs)) break;
        $stmt->bind_result($returnData);
        $stmt->fetch();

        $returnData = json_decode($returnData, true);

        if($returnData['success'] == true) {
            if(password_verify($password, $returnData['hash'])) {
                $result['success'] = true;
                $result['type'] = $returnData['type'];
                $result['email'] = $email;
                if($result['type'] === 'user') {
                    $result['firstName'] = $returnData['firstName'];
                    $result['lastName'] = $returnData['lastName'];
                    $result['nationality'] = $returnData['nationality'];
                    $result['dob'] = $returnData['dob'];
                    $result['middleName'] = getUserMiddleNames($email);
                    $result['aliasName'] = getUserAliasNames($email);
                }
                _updateUserLastLogin($result['email']);

            } else {
                array_push($errMsgs, "Wrong Username/Password Combination");
            }
        } else {
            array_push($errMsgs, "Wrong Username/Password Combination");
        }

        $stmt->close();

        break;
    }

    closeDatabase($con);

    return $result;
}

function hashPassword($pass) {
    return password_hash($pass, PASSWORD_DEFAULT);
}

function session_valid_id($session_id)
{
    return preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $session_id) > 0;
}

function getLatestMembers($_amount) {
    $email = "";
    $firstName = "";
    $lastName = "";
    $dateCreated = "";
    $result = [];
    $resultArray = array();

    $conn = connectDatabase();

    $stmt = $conn->prepare("CALL getLatestMembers(?)");
    $stmt->bind_param("i", $_amount);
    $stmt->execute();

    $stmt->bind_result($email, $firstName, $lastName, $dateCreated);

    while($stmt->fetch()) {
        $result['email'] = $email;
        $result['firstName'] = $firstName;
        $result['lastName'] = $lastName;
        $result['dateCreated'] = $dateCreated;
        array_push($resultArray, $result);
    }

    closeDatabase($conn);
    return $resultArray;
}

function getUserAliasNames($email) {
    $aliasNames = "";
    $returnData = "";
    $cnt = 0;

    $con = connectDatabase();

    while(1) {
        $stmt = $con->prepare("CALL getAliases(?)");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        //if (check_error($stmt->errno, $stmt->error, $errMsgs)) break;
        $stmt->bind_result($returnData);

        $aliasNames .= '{ "aliasName" : [';

        while($stmt->fetch()) {
            if($cnt > 0) {
                $aliasNames .= ",";
            }

            $aliasNames .= "\"$returnData\"";
            $cnt++;
        }

        $aliasNames .= "]}";
        $stmt->close();

        break;
    }

    closeDatabase($con);

    $ret = json_decode($aliasNames, true);
    return $ret['aliasName'];
}

function getUserMiddleNames($email) {
    $middleNames = "";
    $returnData = "";
    $cnt = 0;

    $con = connectDatabase();

    while(1) {
        $stmt = $con->prepare("CALL getMiddleNames(?)");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        //if (check_error($stmt->errno, $stmt->error, $errMsgs)) break;
        $stmt->bind_result($returnData);
        
        $middleNames .= '{ "middleName" : [';
        
        while($stmt->fetch()) {
            if($cnt > 0) {
                $middleNames .= ",";
            }
            
            $middleNames .= "\"$returnData\"";
            $cnt++;
        }
        
        $middleNames .= "]}";
        $stmt->close();
        
        break;
    }
    
    closeDatabase($con);

    $ret = json_decode($middleNames, true);
    return $ret['middleName'];
}

function generateTreeJson($user) {
    $email = $user['email'];
    $fName = $user['firstName'];
    $lName = $user['lastName'];

    $jsonVal = "{\"key\": 0, \"wifeHack\": -99, \"firstName\" : \"$fName\", \"lastName\" : \"$lName\", \"email\" : \"$email\",";
    $jsonVal .= "\"parent\" : [";

    $conn = connectDatabase();

    while(1) {

        $jsonVal .= _getParentHelper($email);

        $jsonVal .= "}";

        break;
    }

    closeDatabase($conn);


    return json_decode($jsonVal, true);
}

/*function fixUpTree($jsonVal, $_test) {
    $test = $_test;
    for($r = 0; $r < count($jsonVal['parent']); $r++) {
        $p = $jsonVal['parent'];

        if(count($jsonVal['parent']) === 1) {
          $jsonVal['parent'][1] = json_decode(getDummy(++$jsonVal['parent'][0]['key']), true);
        }
        $test = fixUpTree($p[$r], $jsonVal);
    }

    return $test;
}*/

function _getParentHelper($_email) {
    STATIC $keyCnt = 0;
    $cnt = 0;
    $email = "";
    $fName = "";
    $lName = "";
    $val = "";
    $commaSwitch = false;

    $keyCnt--;

    $conn = connectDatabase();
    $stmt = $conn->prepare("call getParents(?)");
    $stmt->bind_param("s", $_email);
    $stmt->execute();
    $stmt->bind_result($email, $fName, $lName);
    $prevKey = null;

    while($stmt->fetch()) {
        if($commaSwitch) {
            $val .= ",";
        }

        $val .= "{\"key\": $keyCnt, \"firstName\" : \"$fName\", \"lastName\" : \"$lName\", \"email\" : \"$email\",";

        $val .= "\"parent\" : [";
        $val .= _getParentHelper($email);
        $val .= "}";

        $commaSwitch = true;
        $cnt++;
    }

    if($cnt === 1) {
        $tempVal = rand(0, 10000);
        $val .= "," . getDummy($tempVal * -1);
    }

    $val .= "]";

    $stmt->close();

    closeDatabase($conn);
    return $val;
}

function getDummy($keyCnt) {
    return "{\"key\": $keyCnt, \"firstName\" : \"Unknown\", \"lastName\" : \"\", \"email\" : \"\", \"parent\" : []}";
}

function searchFor($procFunc, $keyword, $sort, $by) {
    $email = "";
    $firstName = "";
    $lastName = "";
    $result = [];
    $resultArray = array();

    $conn = connectDatabase();

    $stmt = $conn->prepare($procFunc);
    $stmt->bind_param("sss", $keyword, $sort, $by);
    $stmt->execute();
    $stmt->bind_result($email, $firstName, $lastName);

    while($stmt->fetch()) {
        $result['email'] = $email;
        $result['firstName'] = $firstName;
        $result['lastName'] = $lastName;
        array_push($resultArray, $result);
    }

    closeDatabase($conn);
    return $resultArray;
}