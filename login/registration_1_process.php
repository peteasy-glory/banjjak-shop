<?php

include "../include/top.php";

closeDB();

if ($_SESSION["gobeauty_certification_number"] != $_REQUEST["gobeauty_2_check_cellphone"])
{
?>
	<script language="javascript">
                        $.MessageBox({
                                buttonDone      : "확인",
                                message         : "인증번호가 다릅니다. 재시도해주세요."
                        }).done(function(){
				location.href="registration_1.php";
                        });
	</script>
<?php
} else {
?>
	
        <script language="javascript">
//                        $.MessageBox({
//                                buttonDone      : "확인",
//                                message         : "적용 성공"
//                        }).done(function(){
                                location.href="registration_2.php";
//                        });
        </script>
<?php
}
include "../include/bottom.php";
?>
