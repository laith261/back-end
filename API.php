<?php
require "Functions.php";

header("Content-Type: application/json");
header("Accept: application/json");

$_POST = json_decode(file_get_contents('php://input'), true);
// print_r($_POST);
if (isset($_POST["singup"])) {
    $singup = $_POST["singup"];
    $refeare_code=isset($singup["refeare"])?$singup["refeare"]:null;
    //
    if (allFill($singup)) {
        json(array("status" => false, "message" => "Fill All Requiered Inputs"));
    }
    if (isInDb("users", array("phone" => $singup["phone"]))) {
        json(array("status" => false, "message" => "Phone All Ready Used"));
    }
    //
    if (empty($singup["name"])) {
        json(array("status" => false, "message" => "Name Can`t Be Empty"));
    }
    //
    if (validPhone($singup["phone"])) {
        json(array("status" => false, "message" => "Not Valid Email"));
    }
    //
    if (validPassword($singup["password"])) {
        json(array("status" => false, "message" => "Not Valid Password Must Be More Then 5 char"));
    }
    //
    if (isset($singup["refeare"])) {
        $refeareCount = $qq->nums($qq->select("users", array("refeare_code" => $singup["refeare"]))) == 0;
        if ($refeareCount) {
            json(array("status" => false, "message" => "Not Valid Refeare Code"));
        }
        unset($singup["refeare"]);
    }
    //
    $refeare = "";
    do {
        $refeare = uniqid();
    } while ($qq->nums($qq->select("users", array("refeare_code" => $refeare))) > 0);
    //
    $singup["refeare_code"] = $refeare;
    $singup["password"] = password_hash($singup["password"], PASSWORD_DEFAULT);
    //
    $query = $qq->insert("users", $singup);
    $status = $query == true;
    if ($refeare_code!=null && $status) {
        $re = $qq->last();
        $r = $qq->fetch($qq->select("users", array("refeare_code" => $refeare_code, array("uid"))))["uid"];
        $refQuery = $qq->insert("refeares", array("r_uid" => $r, "re_uid" => $re));
        if ($refQuery != true) {
            $status = true;
            $qq->delete("users", array("uid" => $re));
        }
    }
    json(array("status" => $status));
}
