<?php include "../include/top.php"; ?>
<?php include "../include/Crypto.class.php"; ?>
<?php include "../include/MCASH_SEED.php"; ?>

<style>
a{text-decoration:none; }
a:link {color:#fff;}
a:visited {color:#fff;}
a:hover {color:#fff;}
a:active {color:#fff;}
</style>
<style type="text/css">
.cell_confirm {
	/*background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #c123de), color-stop(1, #a20dbd) );*/
	/*background:-moz-linear-gradient( center top, #c123de 5%, #a20dbd 100% );*/
	/*filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#c123de', endColorstr='#a20dbd');*/
	background-color:#f5bf2e;
	-webkit-border-top-left-radius:6px;
	-moz-border-radius-topleft:6px;
	border-top-left-radius:6px;
	-webkit-border-top-right-radius:6px;
	-moz-border-radius-topright:6px;
	border-top-right-radius:6px;
	-webkit-border-bottom-right-radius:6px;
	-moz-border-radius-bottomright:6px;
	border-bottom-right-radius:6px;
	-webkit-border-bottom-left-radius:6px;
	-moz-border-radius-bottomleft:6px;
	border-bottom-left-radius:6px;
	text-indent:0;
	border:1px solid #f5bf2e;
	display:inline-block;
	color:#ffffff;
	font-family:Arial;
	font-size:20px;
	font-weight:bold;
	font-style:normal;
	height:40px;
	line-height:20px;
	width:100%;
	text-decoration:none;
	text-align:center;
}
/*
.cell_confirm:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #a20dbd), color-stop(1, #c123de) );
	background:-moz-linear-gradient( center top, #a20dbd 5%, #c123de 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#a20dbd', endColorstr='#c123de');
	background-color:#a20dbd;
}
.cell_confirm:active {
	position:relative;
	top:1px;
}
*/
input[type=text], input[type=password] {
  width: 100%;
  height:30px;
  border: 1px solid #f5bf2e;
  text-align:center;
  font-size:15px;
}
.none{
    display: none;
}
.header { position: relative; width: 100%; height: 50px; line-height: 50px; border-bottom: 1px solid #ccc; }
.header .header-back-btn { position: absolute; left: 10px; top: 12px; }
.header .header-back-btn img { width: 26px; }
.header .header-title { width:100%;height:50px;line-height:50px;text-align:center;font-size:18px;font-weight:bold; }
.header .header-title p { padding: 0px; margin: 0px; }
.header .header-home-btn { position: absolute; right: 10px; top: 12px; }
.header .header-home-btn img { width: 50px; }

</style>
<?php
        function cipher($seedStr, $seedKey) {
                if( $seedStr == "" ) return "";
                return decodeString($seedStr, getKey($seedKey));
        }

        function getKey( $value ){
                $padding = "123456789123456789";
                $tmpKey = $value;
                $keyLength = strlen( $value );
                if( $keyLength < 16 ) $tmpKey = $tmpKey.substr($padding, 0, 16-$keyLength);
                else  $tmpKey = substr( $tmpKey, strlen( $tmpKey )-16,  strlen( $tmpKey ) );
                for($i = 0 ; $i < 16; $i++) {
                        $result = $result.chr(ord( substr( $tmpKey, $i, 1 ))^($i+1));
                }
                return $result;
        }

        $Svcid      = $_POST["Svcid"     ];
        $Name       = $_POST["Name"];
        $No         = $_POST["No"        ];
        $Commid           = $_POST["Commid"    ];
        $Resultcd   = $_POST["Resultcd"  ];

        $Name                   = cipher($Name, "1901230619012306");
        $No                             = cipher($No, "1901230619012306");
        $Commid         = cipher($Commid, "1901230619012306");

        $Name = iconv("euc-kr", "utf-8", $Name);

	$sequence = $_REQUEST['sequence'];
	if (!$sequence) {
		$sequence = 1;
	}
?>
	<div class="header">
        <div class="header-back-btn"><a href="<?=$login_directory?>/"><img src="<?=$image_directory?>/btn_back_2.png"></a></div>
        <div class="header-title"><p>아이디/비밀번호 찾기</p></div>
        <div class="header-home-btn"><a href="<?=$mainpage_directory?>/"><img src="<?=$image_directory?>/main_logo.png"></a></div>
	</div>

	<table width="100%">
	 <tr>
<?php
if ($Resultcd == '0000') {
	if ($sequence == 1) {
?>
	  <td width="50%">
		<a href="find_id_password.php?sequence=1">
		<div width="100%" style="vertical-align:middle;height:40px;text-align:center;background-color:#f5bf2e;color:#fff;font-weight:bold;">
			<table width="100%" height="40px"><tr><td>아이디찾기</td></tr></table>
		</div>
		</a>
	  </td>
	  <td width="50%">
		<a href="find_id_password.php?sequence=2">
		<div width="100%" style="vertical-align:middle;height:40px;text-align:center;background-color:#cecaca;color:#fff;font-weight:bold;">
			<table width="100%" height="40px"><tr><td>비밀번호찾기</td></tr></table>
		</div>
		</a>
	  </td>
<?php
	} else {
?>
          <td width="50%">
		<a href="find_id_password.php?sequence=1">
                <div width="100%" style="vertical-align:middle;height:40px;text-align:center;background-color:#cecaca;color:#fff;font-weight:bold;">
                        <table width="100%" height="40px"><tr><td>아이디찾기</td></tr></table>
                </div>
		</a>
          </td>
          <td width="50%">
		<a href="find_id_password.php?sequence=2">
                <div width="100%" style="vertical-align:middle;height:40px;text-align:center;background-color:#f5bf2e;color:#fff;font-weight:bold;">
                        <table width="100%" height="40px"><tr><td>비밀번호찾기</td></tr></table>
                </div>
		</a>
          </td>
<?php
	}
?>
	 </tr>
	 <tr><td height="40px"></td></tr>
	 <tr>
	  <td colspan="2" align="center">
<?php
    $crypto = new Crypto();
    $cellphone = $crypto->encode(trim($No), $access_key, $secret_key);
    $sql = "select * from tb_customer where cellphone = '".$cellphone."';";
    $result = mysql_query($sql);
    for ($ddddd_i = 0 ; $rows = mysql_fetch_object($result) ; $ddddd_i = $ddddd_i + 1) {
		if ($sequence == 1) {
			if($rows->enable_flag == 0){ // 2020-04-27 ulmo 회원탈퇴
?>
		<font style="font-size:16px;">인증하신 전화번호는 이미 탈퇴하신 고객입니다.<br/>재가입을 원하시면 고객센터(<?=$company_help_number?>)로 연락 주십시요.</font><br/><br/>
<?php
			}else{
?>
		<font style="font-size:16px;">고객님이 사용하신 이메일 아이디의 일부분입니다.</font><br>
<?php
			$result_id = $rows->id;
			$id_pos = strpos($result_id, "@");
			if ($id_pos > 3)
			{
				$result_id = substr($result_id, 0, $id_pos - 3)."***".substr($result_id, $id_pos, strlen($rows->id));
			} else {
				$result_id = substr($result_id, 0, 3)."***".substr($result_id, 6, strlen($rows->id));
			}
?>
		<?=$result_id?><br><br>
<?php
			}
		} else {
?>
		<form action="change_password.php" id="next_form" method="POST">
		<input type="hidden" name="customer_id" value="<?=$rows->id?>">
		<table width="300px">
		 <tr>
		  <td>
			<font style="font-size:16px;">이메일 아이디를 입력해주세요.</font>
		  </td>
		 </tr>
		 <tr><td height="10px"></td></tr>
		 <tr>
		  <td align="center">
			<input type="text" placeholder="이메일아이디 입력" name="gobeauty_4_check_email" id="gobeauty_4_check_email" required><br>
			<font style="font-size:12px;color:red;">* 이메일 아이디를 정확히 입력하지 않으면<br> 비밀번호가 변경 되지 않습니다.</font>
		  </td>
		 </tr>
		 <tr><td height="10px"></td></tr>
		 <tr>
		  <td>
			<font style="font-size:16px;">새로운 비밀번호를 입력해주세요.</font>
                  </td>
                 </tr>
                 <tr><td height="10px"></td></tr>
                 <tr>
                  <td align="center">
			<input type="password" placeholder="비밀번호입력(6~16자 영문,숫자조합)" name="gobeauty_pwd" id="gobeauty_pwd" onblur="ck_pwd()" required>
			<span id="MsgPw" class="none">유효성체크</span>
                  </td>
                 </tr>
                 <tr><td height="10px"></td></tr>
                 <tr>
                  <td align="center">
			<input type="password" placeholder="비밀번호확인" name="gobeauty_pwd_ck" id="gobeauty_pwd_ck" onblur="ck_pwd2()" required>
			<span id="MsgPwck" class="none">유효성체크</span>
		  </td>
		 </tr>
		 <tr><td height="10px"></td></tr>
		 <tr>
		  <td align="center">
			<input type="submit" onclick="return check_all();" href="#" class="cell_confirm" value="비밀번호 변경"></input>
		  </td>
		 </tr>
		</table>
		</form>
<?php
			break;
		}
    }
    if ($ddddd_i == 0 && $sequence == 1) {
?>
        <br>
        <font style="font-size:16px;">인증하신 전화번호로 가입된<br>아이디를 찾을 수 없습니다.</font><br>
        <br>
<?php
    }
}
?>
<script>
function ck_pwd(){
        var pwd = document.getElementById("gobeauty_pwd")
        var MsgPw = document.getElementById("MsgPw")
        var isPwd = /^(?=.*[a-zA-Z])((?=.*\d)|(?=.*\W)).{6,20}$/
        
        if(!isPwd.test(pwd.value)){
            MsgPw.style.display="block";
            MsgPw.className='error'
            MsgPw.innerHTML="<font style='font-size:13px;color:red;'>숫자포함 최소 6자리 이상</font>"
//            pwd.focus()
            return false;
        } else{
            MsgPw.className='vaild'
            MsgPw.innerHTML="<font style='font-size:13px;color:green;'>ok</font>"
            return true;
        }   
}


function ck_pwd2(){
        var pwd_ck = document.getElementById("gobeauty_pwd_ck")
        var pwd = document.getElementById("gobeauty_pwd").value
        var MsgPwck = document.getElementById("MsgPwck")
        
        if(pwd_ck.value!=pwd || pwd_ck.value == ""){
            MsgPwck.style.display="block";
            MsgPwck.className='error'
            MsgPwck.innerHTML="<font style='font-size:13px;color:red;'>비밀번호가 일치하지 않습니다.</font>"
//            pwd_ck.focus()
            return false;
        } else{
            MsgPwck.className='vaild'
            MsgPwck.innerHTML="<font style='font-size:13px;color:green;'>ok</font>"
            return true;
        }   
}
function check_all () {
        if (!ck_pwd()) {
                $.MessageBox({
                    buttonDone      : "확인",
                    message         : "비밀번호를 확인해주세요."
                }).done(function(){
                });
                return false;
        }
        if (!ck_pwd2()) {
                $.MessageBox({
                    buttonDone      : "확인",
                    message         : "비밀번호 확인을 확인해주세요."
                }).done(function(){
                });
                return false;
        }
/*      if (!ck_name()) {
                $.MessageBox({
                    buttonDone      : "확인",
                    message         : "닉네임을 확인해주세요."
                }).done(function(){
                        var id = document.getElementById("name");
                        id.focus();
                });
                return false;
        }
*/
//        document.getElementById('next_form').submit();
        return true;
}

</script>
	  </td>
	 </tr>
	</table>
<br><br><br><br>
<?php include "../include/bottom.php"; ?>
