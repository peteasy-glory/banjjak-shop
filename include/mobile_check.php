<?php
class module
{
    function mobileConcertCheck()
    {
        $mobileArray = array(
            "iphone", "lgtelecom", "skt", "mobile", "samsung", "nokia", "blackberry", "android", "sony", "phone"
        );

        $checkCount = 0;
        for ($num = 0; $num < sizeof($mobileArray); $num++) {
            if (preg_match("/$mobileArray[$num]/", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                $checkCount++;
                break;
            }
        }
        return ($checkCount >= 1) ? "mobile" : "computer";
    }
}
