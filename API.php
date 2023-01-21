<?php
require "Functions.php";

header("Content-Type: application/json");
header("Accept: application/json");

$_POST = json_decode(file_get_contents('php://input'), true);
// print_r($_POST);
if (isset($_POST["singup"])) {
    $data = $_POST["singup"];
    $refeare_code = isset($data["refeare"]) ? $data["refeare"] : null;
    //
    if (Validtion::allFill($data)) {
        json(array("status" => false, "message" => "Fill All Requiered Inputs"));
    }
    if ($qq->isInDb("users", array("phone" => $data["phone"]))) {
        json(array("status" => false, "message" => "Phone All Ready Used"));
    }
    //
    if (empty($data["name"]) || !preg_match("/^[a-zA-Z\s]+$/", $data["name"])) {
        json(array("status" => false, "message" => "Name is Invalid"));
    }
    //
    if (Validtion::Phone($data["phone"])) {
        json(array("status" => false, "message" => "Not Valid Phone Number"));
    }
    //
    if (Validtion::Password($data["password"])) {
        json(array("status" => false, "message" => "Not Valid Password Must Be More Then 5 char"));
    }
    //
    if (isset($data["refeare"])) {
        $refeareCount = $qq->nums($qq->select("users", array("refeare_code" => $data["refeare"]))) == 0;
        if ($refeareCount) {
            json(array("status" => false, "message" => "Not Valid Refeare Code"));
        }
        unset($data["refeare"]);
    }
    //
    $refeare = "";
    do {
        $refeare = uniqid();
    } while ($qq->nums($qq->select("users", array("refeare_code" => $refeare))) > 0);
    //
    $data["refeare_code"] = $refeare;
    $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
    //
    $query = $qq->insert("users", $data);
    $status = $query == true;
    if ($refeare_code != null && $status) {
        $re = $qq->last();
        $r = $qq->fetch($qq->select("users", array("refeare_code" => $refeare_code, array("uid"))))["uid"];
        $refQuery = $qq->insert("referral", array("referring" => $r, "referraled" => $re));
        if ($refQuery != true) {
            $status = true;
            $qq->delete("users", array("uid" => $re));
        }
    }
    json(array("status" => $status));
}

if (isset($_POST["singin"])) {
    $data = $_POST["singin"];
     //
     if (Validtion::allFill($data)) {
        json(array("status" => false, "message" => "Fill All Requiered Inputs"));
    }
    //
    if (Validtion::Phone($data["phone"])) {
        json(array("status" => false, "message" => "Not Valid Phone Number"));
    }
    //
    if (Validtion::Password($data["password"])) {
        json(array("status" => false, "message" => "Not Valid Password Must Be More Then 5 char"));
    }
    $query=$qq->select("users",array("phone"=>$data["phone"]));
    if($qq->nums($query)==0){
        json(array("status" => false, "message" => "Incorrect Information 1"));
    }
    $fetch=$qq->fetch($query);
    $password=$fetch["password"];
    
    if(!password_verify($data["password"],$password )){
        json(array("status" => false, "message" => "Incorrect Information 2"));
    }
    json(array("status"=>true,"data"=>array(
        "id"=>$fetch["uid"],
        "name"=>$fetch["name"],
    )));
}