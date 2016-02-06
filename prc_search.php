<?php
session_start();

/**
 * Created by IntelliJ IDEA.
 * User: TipsyCoder
 * Date: 9/28/15
 * Time: 10:41 PM
 */

require_once 'php/core/util.php';
require_once 'php/core/data.php';

if($_GET) {
    $pageTitle = "Search";
    $htmlStr = "";

    if (1) {
        $errMsgs = array();
        $keyword = "";
        $option = "";
        $fullOptions = "";
        $procFunction = "";


        if(isset($_GET['uSearch']) && trim($_GET['uSearch'], " ") != "") {
            $keyword = $_GET['uSearch'];
        } else {
            array_push($errMsgs, "Keyword Empty");
        }

        if(isset($_GET['uOptions']) && trim($_GET['uOptions'], " ") != "") {
            $option = $_GET['uOptions'];

            switch($option) {
                case 'fn' : $fullOptions = "FIRST NAME";
                    $procFunction = "call searchFirstName(?,?,?)";
                    break;
                case 'ln' : $fullOptions = "LAST NAME";
                    $procFunction = "call searchLastName(?,?,?)";
                    break;
                case 'em' : $fullOptions = "EMAIL";
                    if(filter_var($keyword, FILTER_VALIDATE_EMAIL) !== false) {
                        $procFunction = "call searchEmail(?,?,?)";
                    } else {
                        array_push($errMsgs, "Invalid Email Address");
                    }
                    break;
                case 'als' : $fullOptions = "ALIASES";
                    $procFunction = "call searchAlias(?,?,?)";
                    break;
                case 'all' : $fullOptions = "ALL FIELDS";
                    $procFunction = "call searchWide(?,?,?)";
                    break;
                default:
                    array_push($errMsgs, "Invalid Search Filter");
            }

            if(count($errMsgs) <= 0) {
                array_push($labelArr, "Filter");
                array_push($dataArr, $fullOptions);
            }
        } else {
            array_push($errMsgs, "Search Filter Empty");
        }

        if(count($errMsgs) <= 0) {
            if(!isset($_GET) || (strtoupper($_GET['by']) !== "FNAME" && strtoupper($_GET['by']) !== 'LNAME')) {
                $_GET['by'] = 'FNAME';
                $nextSortBy = 'FNAME';
            } else if(strtoupper($_GET['by']) === "LNAME") {
                $nextSortBy = "LNAME";
            } else {
                $nextSortBy = "FNAME";
            }

            if(!isset($_GET) || (strtoupper($_GET['sort']) !== "ASC" && strtoupper($_GET['sort']) !== 'DESC')) {
                $_GET['sort'] = 'DESC';
                $nextSort = "ASC";
            } else if(strtoupper($_GET['sort']) === "ASC") {
                $nextSort = "DESC";
            } else {
                $nextSort = "ASC";
            }

            $searchHtml =  createSearchTable(searchFor($procFunction, $keyword, $_GET['sort'], $_GET['by']), $errMsgs, $nextSort, $nextSortBy, $keyword, $option);

            if (count($errMsgs) > 0) {
                $htmlStr .= generateErrorHtml($errMsgs);
            } else {
                $htmlStr .= "<div class='animated zoomInUp'><div class='successFilterLbl' style='margin-top: 0; text-align: center'>SEARCH - $keyword</div>";
                $htmlStr .= "<label><div class='filterLbl' style='text-align: center'>FILTER - $fullOptions</div></label><br><br><br>";
                $htmlStr .= $searchHtml . "</div>";
                $_SESSION['searchFilter'] = $option;
                $_SESSION['searchValue'] = $keyword;
                $_SESSION['htmlResult'] = $htmlStr;
                header("Location: search.php");
                exit();
            }
        } else {
            $htmlStr .= generateErrorHtml($errMsgs);
        }
    }
}

showResultHtml($pageTitle, $htmlStr, true);

function createSearchTable($searchedArray, &$errMsgs, $sort, $sortBy, $keyword, $opt) {
    $htmlStr = "";

    $fNameCaret = "fa-caret-up";
    $lNameCaret = "fa-caret-up";

    if($sort === "ASC" && $sortBy === 'FNAME') {
        $fNameCaret = "fa-caret-down";
    } else if($sort === "ASC" && $sortBy === 'LNAME') {
        $lNameCaret = "fa-caret-down";
    }

    if(count($searchedArray) > 0) {
        $htmlStr .= "<table style='max-width: 640px'>";
        $htmlStr .= "<tr class='tableHeader'><td>EMAIL</td><td id='fNameSort' onclick='searchNameSort(\"$sort\" , \"FNAME\", \"prc_search.php?uSearch=$keyword&uOptions=$opt\")'>FIRST NAME". getSpaces(2) ."<i class='fa $fNameCaret'></i></td><td id='lNameSort' onclick='searchNameSort(\"$sort\" , \"LNAME\", \"prc_search.php?uSearch=$keyword&uOptions=$opt\")'>LAST NAME". getSpaces(2) ."<i class='fa $lNameCaret'></i></td></tr>";
        for($i = 0; $i < count($searchedArray); $i++) {
            $email = $searchedArray[$i]['email'];
            $fName = $searchedArray[$i]['firstName'];
            $lName = $searchedArray[$i]['lastName'];
            $htmlStr .= "<tr><td style='padding-left: 5px'>$email</td><td>$fName</td><td>$lName</td><td><a href='tree.php?email=$email'><button>See Tree</button></a></td></tr>";
        }
        $htmlStr .= "</table>";
    } else {
        array_push($errMsgs, "No Record Found");
    }

    return $htmlStr;
}