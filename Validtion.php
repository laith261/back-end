<?php

class Validtion
{
    // for emails
    public static function Email($email)
    {
        $req = "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/";
        return !preg_match($req, $email);
    }
    // for phone numbers
    public static function Phone($phone)
    {
        $req = "/^009640?7(7|5|8)\d{8}$/";
        return !preg_match($req, $phone);
    }
    // for passwords
    public static function Password($password)
    {
        if (strlen($password) < 6) {
            return true;
        }
        return false;
    }

    // is all data not empty
    public static function allFill($data = array())
    {
        foreach (array_values($data) as $value) {
            if ($value == "") {
                $status = true;
                break;
            }
        }
        return false;
    }
}
