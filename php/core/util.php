<?php
/**
 * Created by IntelliJ IDEA.
 * User: TipsyCoder
 * Date: 9/30/15
 * Time: 10:27 PM
 */




function getTableLabelData($labelArr, $dataArr) {
     $dataCnt = count($dataArr);
     $strRet = "";

     $strRet .= "<table style='padding-right: 10px'>";

     for($i = 0; $i < $dataCnt; $i++) {
         if(is_array($dataArr[$i]) == 1) {
             $strRet .= "<tr><td class='labelTD'>$labelArr[$i]</td><td class='dataTD'>" . getSelect($dataArr[$i]) . "</td></tr>";
         } else {
             $strRet .= "<tr><td class='labelTD'>$labelArr[$i]</td><td class='dataTD'>$dataArr[$i]</td></tr>";
         }
     }

     $strRet .= "</table>";

     return $strRet;
 }

function getSelect($dataArr) {
    $strHtml = "<div class=\"select-style\"><select>";

    foreach($dataArr as $value) {
        $strHtml .= "<option>$value</option>";
    }

    $strHtml .= "</select></div>";

    return $strHtml;
}

function getTableData($dataArr) {
    $dataCnt = count($dataArr);
    $strRet = "";

    $strRet .= "<table>";

    for($i = 0; $i < $dataCnt; $i++) {
        $strRet .= "<tr><td>$dataArr[$i]</td></tr>";
    }

    $strRet .= "</table>";

    return $strRet;
}

function explodeNames($str, $delimiter) {
    $explodeRet = explode($delimiter, $str);

    return prepareData("", $explodeRet);
}

function prepareData($errMsg, $data) {
    return array('errMsg' => $errMsg, 'data' => $data);
}

function check_in_range($start_date, $end_date, $date_from_user)
{
    // Convert to timestamp
    $start_ts = strtotime($start_date);
    $end_ts = strtotime($end_date);
    $user_ts = strtotime($date_from_user);

    // Check that user date is between start & end
    return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
}

function check_error($errno, $errorMsg, &$errMsgsArray) {
    if($errno === 0 || $errMsgsArray === null) {
        return false;
    }

    switch($errno) {
        case 1062:
            array_push($errMsgsArray, "Email Already Exist");
            break;
        default:
            array($errMsgsArray, $errorMsg . " - Error Num: " . $errno);
    }

    return true;
}

function generateErrorHtml($errMsgs) {
    $htmlStr = "<script> badSweet('ERROR FOUND', '') </script><div class='errorLbl'>ERROR FOUND</div>";
    for($i = 0; $i < count($errMsgs); $i++) {
        $htmlStr .=  "<label><div class='passLbl'>". $errMsgs[$i] . "</div></label><br>";
        $pageTitle = "Error";
    }
    $htmlStr .= "<a href='javascript:history.back()'><button>GO BACK</button></a>";

    return $htmlStr;
}

function showResultHtml($pTitle, $htmlStr, $hack = false) {

    if(!isset($pTitle)) {
        $pTitle = "Big Yaad Tree";
    } else {
        $pTitle .= " - Big Yaad Tree";
    }

    $html = "<head><title>$pTitle</title><meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1'><link rel=\"stylesheet\" href=\"../../css/reset.css\"><link rel=\"shortcut icon\" href=\"../../img/favicon.ico\" type=\"image/x-icon\">
    <link rel=\"icon\" href=\"../../img/favicon.ico\" type=\"image/x-icon\"><script src='../../js/sweetalert.min.js'></script><script src='../../js/jquery-2.1.4.min.js'></script><script src='../../js/index.js'></script>
    <link rel='stylesheet prefetch' href='../../css/font-awesome.min.css'><link rel='stylesheet' href='../../css/sweetalert.css'><link rel=\"stylesheet\" href=\"../../css/style.css\"><link rel=\"stylesheet\" href=\"../../css/animate.css\"></head><header>
    <div class=\"animated fadeIn\">
    <div class=\"nav\">
        <ul>
            <li class=\"logo\"><img class=\"animated lightSpeedIn\" src=\"../../img/logo.png\" style='height: 40px'></li>
        </ul>
    </div>
    </div>
</header><body>";

    if($htmlStr !== "") {
        $html .= "<div class=\"panel\">
            $htmlStr<br>

        </div>";
    }

    $html .= "</body>";

    if($hack) {
        $html = str_replace("../../", "", $html);
    }

    echo $html;
}

function getTreeArray($jsonTree) {

   $strArr = "[";
    $strArr .= _getNodeHelper($jsonTree);
    $strArr .= _treeLimbHelper($jsonTree);
    $strArr = substr($strArr, 0, strlen($strArr) - 1);
    $strArr .= "]";


    return $strArr;
}

function _treeLimbHelper($jsonTreeLimb) {
    STATIC $keyCnt = 0;
    $strLimb = "";
    $keyCnt--;
    $prevKey = null;

    for($r = 0; $r < count($jsonTreeLimb['parent']); $r++) {
        $temp = _treeLimbHelper($jsonTreeLimb['parent'][$r]);
        $strLimb .= $temp;
        if($r == 0) {
            $prevKey = $jsonTreeLimb['parent'][$r]['key'];
        }
        $strLimb .= _getNodeHelper($jsonTreeLimb['parent'][$r], $r == 1 ? $prevKey : null);
    }

    return $strLimb;
}

function _getNodeHelper($parentJson, $prevKey = null) {
    $strLimb = "";

    $fName = $parentJson['firstName'];
    $lName = $parentJson['lastName'];
    $email = $parentJson['email'];
    $key = $parentJson['key'];

    $strLimb .= "{\"key\":$key,\"n\":\"$fName"."#"."$lName\",\"s\":\"M\",";



    for($r = 0; $r < count($parentJson['parent']); $r++) {


        $pKey = $parentJson['parent'][$r]['key'];
        if($r === 0) {
            $strLimb .= "\"m\":$pKey,";
        } else {
            $strLimb .= "\"f\":$pKey,";

        }
    }

    if($prevKey !== null) {
        $strLimb .= "\"ux\":$prevKey,";
    }

    if($email === "") {
        $strLimb .= "\"a\":[\"S\"]";
    } else {
        $strLimb .= "\"a\":[\"A\",\"F\",\"K\"]";
    }

    //\"email\":\"$email\",

    $strLimb .= "},";

    return $strLimb;
}


function userDetailsHtml($userInfo, $header = "USER INFO") {
    $numOfSpaces = 5;
    $email = $userInfo['email'];
    $nationality = $userInfo['nationality'];
    $fName = $userInfo['firstName'];
    $lName = $userInfo['lastName'];
    $dob = $userInfo['dob'];
    $middleNames = $userInfo['middleName'];
    $aliases = $userInfo['aliasName'];

    $strHtml = "<div class='userInfo'>";
    $strHtml .= "<h1>$header" . getSpaces(3);
    if($header === "MY INFO") {
        $strHtml .= "<i class='animated swing infinite fa fa-pencil-square-o' id='editMe'></i>";
    }
    $strHtml .= "</h1>";
    $strHtml .= "<div class='userInfoDetails'>";
    $strHtml .= "<div class='emailEdit'>Email: $email</div>". getSpaces($numOfSpaces) ."<div class='fNameEdit'>".
        "First Name: $fName</div>" . getSpaces($numOfSpaces) . "<div class='lNameEdit'>Last Name: $lName</div>". getSpaces($numOfSpaces) ."<div class='dobEdit'>DOB: $dob</div>" . getSpaces($numOfSpaces);

    $strHtml .= "<div class='nationEdit'>Nationality: $nationality</div>". getSpaces($numOfSpaces);

    $strHtml .= "</br><table style='margin-top: 8px' '><tr><td></td><td width='200px'>";

    if(count($middleNames) > 0) {
        $strHtml .= "<div class='dummyEdit'>Middle Name(s): </td><td width='200px'>" . getSelect($middleNames) . "</div>";
    } else {
        $strHtml .= "<div class='dummyEdit'>Middle Name(s): N/A</div>";
    }

    $strHtml .= "</td><td width='200px'>";

    if(count($aliases) > 0) {
        $strHtml .= "<div class='dummyEdit'>Aliase(s): </td><td width='200px'>" . getSelect($aliases) . "</div>";
    } else {
        $strHtml .= "<div class='dummyEdit'>Aliase(s): N/A</div>";
    }


    $strHtml .= "</td><td></td></tr></table></div></div>";

    return $strHtml;
}

function getSpaces($amount) {
    $str = "";

    for($i = 0; $i < $amount; $i++) {
        $str .= "&nbsp";
    }

    return $str;
}


/*function getTreeHtml($jsonTree) {
    $htmlStr = "";
    $fName = $jsonTree['firstName'];
    $lName = $jsonTree['lastName'];
    $email = $jsonTree['email'];

    //$htmlStr .= "<ul>";
    //for($r = 0; $r < count($jsonTree['parent']); $r++) {

    //}
    //$htmlStr .= "</ul>";
    $htmlStr .= "<ul>";
    $htmlStr .= "<li><a>$fName</a>";
    $htmlStr .= _treeHelper($jsonTree);
    $htmlStr .= "</ul>";

    return $htmlStr;

}

function _treeHelper($rowLimbJson) {
    $htmlStr = "";
    $fName = $rowLimbJson['firstName'];
    $lName = $rowLimbJson['lastName'];
    $email = $rowLimbJson['email'];



    for($r = 0; $r < count($rowLimbJson['parent']); $r++) {
        $pfName =  $rowLimbJson['parent'][$r]['firstName'];
        $temp = _treeHelper($rowLimbJson['parent'][$r]);
        $htmlStr .= $temp;
        if ($temp == "") {
            $htmlStr .= "<ul>";
        }

        $htmlStr .= "<li><a>$pfName</a>";


        if($temp !== "") {
            $htmlStr .= "</li></ul>";
        }



    }

    return $htmlStr;
}

/*function _treeHelper($rowLimbJson) {
    $htmlStr = "";
    $fName = $rowLimbJson['firstName'];
    $lName = $rowLimbJson['lastName'];
    $email = $rowLimbJson['email'];


    for($r = 0; $r < count($rowLimbJson['parent']); $r++) {
        $temp = _treeHelper($rowLimbJson['parent'][$r]);

        $htmlStr .= $temp;
        $pfName =  $rowLimbJson['parent'][$r]['firstName'];


        $htmlStr .= "<li><a>$pfName</a>";

        if($temp !== "") {
            $htmlStr .= "</li></ul>";
        }

        //if($r !== 0) {
            if ($temp === "") {
                $htmlStr .= "<ul>";
            }
        //}


    }

    return $htmlStr;
}
function test($parent) {
    $fName = $parent['firstName'];
    $lName = $parent['lastName'];
    $email = $parent['email'];

    return "<li><a href='#'>$fName</a></li>";
}
*/