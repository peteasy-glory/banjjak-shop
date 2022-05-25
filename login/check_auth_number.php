<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
// include "../include/check_header_log.php";

$result = "false";

if (isset($_SESSION["gobeauty_certification_number"]) && isset($_REQUEST["gobeauty_2_check_cellphone"])){
    if ($_SESSION["gobeauty_certification_number"] == $_REQUEST["gobeauty_2_check_cellphone"]){
        $result = "true";
    }
}
?>
{
    "result": "<?=$result?>",
    "certification_number": "<?=$_SESSION["gobeauty_certification_number"]?>",
    "check_cellphone":"<?=$_REQUEST["gobeauty_2_check_cellphone"]?>"
}
