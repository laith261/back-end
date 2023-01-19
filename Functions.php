<?php
session_start();
require "quickQueries.php";
$qq = new quickQueries("localhost", "root", "", "new");

$uid = getUserInfo("a");
$gid = getUserInfo("x");

// search for array in array
function inArray($data, $in)
{
    foreach ($data as $key => $value) {
        if (!in_array($key, array_keys($in)) || $data[$key] != $in[$key]) {
            return false;
            break;
        }
    }
    return true;
}
// get pages pagination
function pages($page, $stmt)
{
    global $qn;
    $page = $page;
    $count = 10;
    $offset = $qn->nums($qn->quiry($stmt));
    $offset = $offset > $count ? ceil($offset / $count) : 0;
    return array("stmt" => "LIMIT $offset,$count", "isFinish" => $offset == 0 || $page == $offset);
}
// show errors
function errors()
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
// get user info
function getUserInfo($data)
{
    $respon = "";
    if (isset($_COOKIE[$data]) && !isset($_SESSION[$data])) {
        $_SESSION[$data] = $_COOKIE[$data];
    }
    $respon = isset($_SESSION[$data]) ? $_SESSION[$data] : "";
    return dehash($respon);
}

// hash the string
function enhash($text)
{
    $text = strrev($text);
    $text = base64_encode($text);
    $text = strrev($text);
    $text = base64_encode($text);
    $text = substr($text, 0, 2) . strrev(substr($text, 2, -2)) . substr($text, -2);
    return $text;
}

// unhash the string
function dehash($text)
{
    $text = substr($text, 0, 2) . strrev(substr($text, 2, -2)) . substr($text, -2);
    $text = base64_decode($text);
    $text = strrev($text);
    $text = base64_decode($text);
    $text = strrev($text);
    return $text;
}

// is user allowed
function allowed($data = array(), $in = array(), $to = null)
{
    if (!inArray($data, $in)) {
        if (is_null($to)) {
            die();
        }
        header("location:$to");
    }
}

// print json array
function json($mess)
{
    echo json_encode($mess);
    exit();
}

// is all data not empty
function allFill($data = array())
{
    foreach (array_values($data) as $value) {
        if ($value == "") {
            $status = true;
            break;
        }
    }
    return false;
}
//
function validEmail($email)
{
    $req = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/";
    return !preg_match($req, $email);
}
//
function validPhone($phone)
{
    $req = "/^009640?7(7|5|8)\d{8}$/";
    return !preg_match($req, $phone);
}
//
function validPassword($password)
{
    if (strlen($password) < 6) {
        return true;
    }
    return false;
}

function isInDb($table, $data)
{
    global $qq;
    $query = $qq->select($table, $data);
    $nums = $qq->nums($query);
    return $nums > 0;
}


// function singIn($data)
// {
//     global $qn;
//     if(isRequred($data)){
//         return array("st" => false, "mess" => "please fill all inputs");
//     }
//     $q = $qn->select("users", array("number" => $data["number"]));

//     if ($qn->nums($q) == 1) {
//         $row = $qn->fetch($q);
//         if (password_verify($data["password"], $row["password"])) {
//             $row["gid"]=enhash($row["gid"]);
//             $row["uid"]=enhash($row["uid"]);
//             return array("st" => true, "data" => $row);
//         }
//     }
//     return array("st" => false, "mess" => "incorrect information");
// }

// function SingUp(array $data=array())
// {
//     global $qn;
//     if (isRequred($data)) {
//         return array("st" => false, "mess" => "Plase fill all required input");
//     }
//     if ($data["password"]!=$data["repassword"]) {
//         return array("st" => false, "mess" => "Passwords don't match");
//     }
//     if ($qn->nums($qn->select("users",array("number"=>$data["number"]))) > 0) {
//         return array("st" => false, "mess" => "this email already in use");
//     }
//     unset($data["repassword"]);
    
//     $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
//     $data["gid"]=1;
//     $q = $qn->insert("users", $data);
//     return array("st" => $q==true, "mess" => $q==true ? "Done" : "Unknown error");
// }