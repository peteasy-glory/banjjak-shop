<?php include "../include/top.php"; ?>

<style>
    @font-face {
        font-family: 'SCDream2';
        src: url("../fonts/SCDream2.otf");
    }

    html,
    div,
    p,
    button,
    input {
        font-family: 'SCDream2';
        font-weight: bold;
    }

    a {
        text-decoration: none;
    }

    a:link {
        color: #ffffff;
    }

    a:visited {
        color: #ffffff;
    }

    a:hover {
        color: #ffffff;
    }

    a:active {
        color: #ffffff;
    }

    .none {
        display: none;
    }

    input[type=text],
    input[type=password] {
        width: 100%;
        height: 30px;
        display: inline-block;


        border: 0;
        outline: 0;
        /* background: transparent; */
        border-bottom: 1px solid #f5bf2e;
        text-align: left;
        font-size: 14px;
    }

     .go_login {
        -webkit-appearance: none;
        border-radius: 0;
        background-color: #f5bf2e;
        -webkit-border-top-left-radius: 6px;
        -moz-border-radius-topleft: 6px;
        border-top-left-radius: 6px;
        -webkit-border-top-right-radius: 6px;
        -moz-border-radius-topright: 6px;
        border-top-right-radius: 6px;
        -webkit-border-bottom-right-radius: 6px;
        -moz-border-radius-bottomright: 6px;
        border-bottom-right-radius: 6px;
        -webkit-border-bottom-left-radius: 6px;
        -moz-border-radius-bottomleft: 6px;
        border-bottom-left-radius: 6px;
        text-indent: 0;
        border: 0px solid #f5bf2e;
        display: inline-block;
        color: #ffffff;
        font-family: Arial;
        font-size: 15px;
        font-weight: bold;
        font-style: normal;
        line-height: 30px;
        width: 100%;
        text-decoration: none;
        text-align: center;
        padding: 5px 20px;
        margin: 8px 0;
    }

    .go_login:hover {
        background-color: #f5bf2e;
    }

    .go_login:active {
        position: relative;
        top: 1px;
    }

    .go_login2 {

        background-color: #f5bf2e;
        -webkit-border-top-left-radius: 6px;
        -moz-border-radius-topleft: 6px;
        border-top-left-radius: 6px;
        -webkit-border-top-right-radius: 6px;
        -moz-border-radius-topright: 6px;
        border-top-right-radius: 6px;
        -webkit-border-bottom-right-radius: 6px;
        -moz-border-radius-bottomright: 6px;
        border-bottom-right-radius: 6px;
        -webkit-border-bottom-left-radius: 6px;
        -moz-border-radius-bottomleft: 6px;
        border-bottom-left-radius: 6px;
        text-indent: 0;
        border: 1px solid #f5bf2e;
        display: none;
        color: #ffffff;
        font-family: Arial;
        font-size: 18px;
        font-weight: bold;
        font-style: normal;
        /* height:50px; */
        line-height: 30px;
        width: 100%;
        text-decoration: none;
        text-align: right;
        padding: 5px 20px;
        margin: 8px 0;
    }

    .go_login2:hover {
        background-color: #f5bf2e;
    }

    .go_login2:active {
        position: relative;
        top: 1px;
    }

    select {
        float: right;
        height: 30px;
        padding-left: 7px;
        font-size: 14px;
        color: #000000;
        border: 1px solid #999999;
        border-radius: 3px;
        width: 50%;
    }

    .layer_pop {
        border: 1px solid #999999;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        position: absolute;
        background: #fff;
        z-index: 5000;
        left: 0;
        top: 106px;
        text-align: center;
    }

    .layer_con {
        border: 1px solid #999999;
        padding: 3px;
        position: relative;
    }

    .layer_con .layer_close {
        position: absolute;
        right: 5px;
        top: 5px;
    }

    .layer_con .allow_bg {
        position: absolute;
        left: 42px;
        bottom: -8px;
    }

    .fwb {
        font-weight: bold !important;
    }

    .header_reg {
        height: 55px;
        border-bottom: 0.5px solid #e1e1e1;
    }

    .btn_back {
        height: 55px;
        float: left;
        margin-left: 10px;
        position: absolute;
    }

    .btn_back img {
        margin-top: 12px;
    }

    .title_reg {
        text-align: center;
        line-height: 55px;
        font-size: 14px;
    }

    .section_reg01 {
        margin: 20px 0px;

    }

    .email_reg {
        width: 100%;
        float: left;
    }

    .password_01 {
        margin: 30px 0px;
    }

    .next_btn {
        position: absolute;
        bottom: 15px;
        right: 15px;
        width: 80px;
    }

    .next_btn img {
        width: 100%;
    }
</style>

<div class="layer_pop" style="width:90%;left:5%;top:170px;display:none;opacity:1;" id="popl">
    <div class="layer_con">
        <p class="fwb" id="poplmsg"></p>
    </div>
</div>

<div class="header_reg">
    <div class="btn_back"><a href="registration_1.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="30px"></a></div>
    <div class="title_reg">
        <font style="font-size:18px;">회원가입 2/2</font>
    </div>
</div>

<!-- registration_2_process.php -->
<form action="registration_2_process.php" id="next_form" method="POST">   
    <input type="hidden" name="gobeauty_nickname" id="gobeauty_nickname" value="">
    <div style="width:90%; margin: 0px auto; font-weight:bold;">
        <div>
            <div height="8px"></div>
        </div>
        <div class="section_reg01">
            <b>이메일 주소</b>
        </div>
        <div>
            <div height="8px"></div>
        </div>
        <div>
            <div class="email_reg">
                <input type="text" placeholder="이메일 입력" name="gobeauty_4_check_email" id="gobeauty_4_check_email" required>
            </div>

        </div>
        <div>
            <div colspan="3" align="center">
                <input type="button" class="go_login" value="중복확인" id="sendsms_button" name="gobeauty_send_button" onclick="ck_id()" required>
            </div>
        </div>
        <div>
            <div height="20px"></div>
        </div>
    </div>

    <div style="width:90%; margin: 0px auto; font-weight:bold;display:none;" id="next_div">
        <div>
            <div style="font-size:16px; margin-top: 20px;">
                <b>비밀번호</b>
            </div>
        </div>
        <div>
            <div class="password_01">
                <input type="password" placeholder="비밀번호입력(6~16자 영문,숫자조합)" name="gobeauty_pwd" id="gobeauty_pwd" onkeyup="ck_pwd()" required>
                <span id="MsgPw" class="none">유효성체크</span>
            </div>
        </div>
        <div>
            <div class="password_02">
                <input type="password" placeholder="비밀번호확인" name="gobeauty_pwd_ck" id="gobeauty_pwd_ck" onkeyup="ck_pwd2()" required>
                <span id="MsgPwck" class="none">유효성체크</span>
            </div>
        </div>
        <div>
            <div height="20px" colspan="3" align="right">
            </div>
        </div>
        <div>
            <div class="next_btn"><img id="next_img" src="../images/next_off.png" onclick="login_submit()"></div>
        </div>
    </div>
</form>

<script>
    $("form").keypress(function(e) {
        //Enter key disable
        if (e.which == 13) {
            return false;
        }
    });

    function closelayer() {
        $('#popl').fadeOut();
    }

    function innermsglayer(msg, left, top) {
        clearTimeout(timer);
        $('#poplmsg').html(msg);
        $('#popl').show();
        var timer = setTimeout(closelayer, 1500);
    }

	$('#gobeauty_4_check_email').on("keyup", function(){ // 공백제거
		$(this).val($.trim($(this).val()));
	});

    var email_regex = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;

    function ck_id() {
        var input_email = $.trim(document.getElementById("gobeauty_4_check_email").value);

        if (!input_email) {
            innermsglayer("이메일을 입력해주세요.", "center", "center");
            $('#next_div').hide();
            return;
        }

        if (email_regex.test(input_email) === false) {
            innermsglayer("이메일 형식을 확인해 주세요.", "center", "center");
            $('#next_div').hide();
            return;
        }
        $.ajax({
            type: "post",
            url: "check_id.php",
            data: {
                id: input_email
            },
            dataType: "JSON",
            success: function s(a) {
                if (a.result == "error") {
                    innermsglayer(a.msg, "center", "center");
                    return;
                } else {
                    innermsglayer(a.msg, "center", "center");
                    $('#next_div').show();
                    var first_nick = input_email.split("@")[0];
                    document.getElementById("gobeauty_nickname").value = first_nick.substring(0, first_nick.length - 4) + "****";
                }
            },
            error: function error() {
                alert('시스템 문제발생');
            }
        });
    }

    function ck_pwd() {
        var pwd = document.getElementById("gobeauty_pwd");
        var MsgPw = document.getElementById("MsgPw");
        var isPwd = /^(?=.*[a-zA-Z])((?=.*\d)|(?=.*\W)).{6,20}$/;

        if (!isPwd.test(pwd.value)) {
            MsgPw.style.display = "block";
            MsgPw.className = 'error'
            MsgPw.innerHTML = "<font style='font-size:13px;color:red;'>숫자포함 최소 6자리 이상</font>"
            ck_pwd2();
            return false;
        } else {
            MsgPw.className = 'vaild'
            MsgPw.innerHTML = "<font style='font-size:13px;color:green;'>확인되었습니다.</font>"
            ck_pwd2();
            return true;
        }
    }

    function ck_pwd2() {
        var pwd_ck = document.getElementById("gobeauty_pwd_ck");
        var pwd = document.getElementById("gobeauty_pwd").value;
        var MsgPwck = document.getElementById("MsgPwck");

        if (pwd_ck.value != pwd || pwd_ck.value == "") {
            MsgPwck.style.display = "block";
            MsgPwck.className = 'error'
            MsgPwck.innerHTML = "<font style='font-size:13px;color:red;'>비밀번호가 일치하지 않습니다.</font>"
            var element = document.getElementById("next_img");
            element.src = "../images/next_off.png";
//            var nextlink = document.getElementById("next_form");
//            nextlink.action = "";
            return false;
        } else {
            MsgPwck.className = 'vaild'
            MsgPwck.innerHTML = "<font style='font-size:13px;color:green;'>확인되었습니다.</font>"
            var element = document.getElementById("next_img");
            element.src = "../images/next_on.png";
//            var nextlink = document.getElementById("next_form");
//            nextlink.action = "registration_2_process.php";
            $("#nextlink").show();
            return true;
        }
    }

	function login_submit() {
		 var input_email = $.trim(document.getElementById("gobeauty_4_check_email").value);

        if (!input_email) {
            innermsglayer("이메일을 입력해주세요.", "center", "center");
            return;
        }

        if (email_regex.test(input_email) === false) {
            innermsglayer("이메일 형식을 확인해 주세요.", "center", "center");
            return;
        }

		var pwd_ck = document.getElementById("gobeauty_pwd_ck");
        var pwd = document.getElementById("gobeauty_pwd").value;
		if (pwd_ck.value != pwd || pwd_ck.value == "") {
			innermsglayer("비밀번호를 확인해 주세요.", "center", "center");
            return;
		}

		document.getElementById('next_form').submit();
	}
</script>

<?php include "../include/bottom.php"; ?>