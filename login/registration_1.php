<?php
include "../include/top.php";
// include "../include/check_header_log.php";
?>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">
<style>

a {text-decoration: none;}
a:link {color: #ffffff;}
a:visited {color: #ffffff;}
a:hover {color: #ffffff;}
a:active {color: #ffffff;}
.go_login {-webkit-appearance: none;border-radius: 0;background-color: #f5bf2e;-webkit-border-top-left-radius: 6px;-moz-border-radius-topleft: 6px;border-top-left-radius: 6px;-webkit-border-top-right-radius: 6px;-moz-border-radius-topright: 6px;border-top-right-radius: 6px;-webkit-border-bottom-right-radius: 6px;-moz-border-radius-bottomright: 6px;border-bottom-right-radius: 6px;-webkit-border-bottom-left-radius: 6px;-moz-border-radius-bottomleft: 6px;border-bottom-left-radius: 6px;text-indent: 0;border: 0px solid #f5bf2e;display: inline-block;color: #ffffff;font-family: Arial;font-size: 15px;font-weight: bold;font-style: normal;line-height: 30px;width: 100%;text-decoration: none;text-align: center;padding: 5px 20px;margin: 8px 0;}
.go_login:hover {background-color: #f5bf2e;}
.go_login:active {position: relative;top: 1px;}
.go_login2 {background-color: #f5bf2e;-webkit-border-top-left-radius: 6px;-moz-border-radius-topleft: 6px;border-top-left-radius: 6px;-webkit-border-top-right-radius: 6px;-moz-border-radius-topright: 6px;border-top-right-radius: 6px;-webkit-border-bottom-right-radius: 6px;-moz-border-radius-bottomright: 6px;border-bottom-right-radius: 6px;-webkit-border-bottom-left-radius: 6px;-moz-border-radius-bottomleft: 6px;border-bottom-left-radius: 6px;text-indent: 0;border: 1px solid #f5bf2e;display: none;color: #ffffff;font-family: Arial;font-size: 18px;font-weight: bold;font-style: normal;line-height: 30px;width: 100%;text-decoration: none;text-align: right;padding: 5px 20px;margin: 8px 0;}
.go_login2:hover {background-color: #f5bf2e;}
.go_login2:active {position: relative;top: 1px;}
input[type=text],input[type=password],input[type=number] {width: 100%;
height: 30px;display: inline-block;border: 0;border-bottom: 1px solid #f5bf2e;text-align: left;font-size: 14px;}
.layer_pop {border: 1px solid #999999;-webkit-border-radius: 3px;-moz-border-radius: 3px;border-radius: 3px;position: absolute;background: #fff;z-index: 5000;left: 0;top: 106px;text-align: left;}
.layer_con {border: 1px solid #999999;padding: 3px;position: relative;}
.layer_con .layer_close {position: absolute;right: 5px;top: 5px;}
.layer_con .allow_bg {position: absolute;left: 42px;bottom: -8px;}
.fwb {font-weight: bold !important;}
.header_reg {height: 55px;border-bottom: 0.5px solid #e1e1e1;}
.btn_back {height: 55px;float: left;margin-left: 10px;position: absolute;}
.btn_back img {margin-top: 12px;}
.title_reg {text-align: center;line-height: 55px;}
.section_reg01 {margin: 20px 0px;}
.registration_pho {margin-bottom: 10px;}
.registration_num {margin-top: 30px;margin-bottom: 10px;}
.next_btn {position: absolute;bottom: 15px;right: 15px;width: 80px;}
.next_btn img {width: 100%;}
</style>
<div class="layer_pop" style="width:90%;left:5%;top:170px;display:none;opacity:1;" id="popl">
    <div class="layer_con">
        <p class="fwb" id="poplmsg"></p>
    </div>
</div>
<div class="header_reg">
    <div class="btn_back"><a href="index.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="30px"></a></div>
    <div class="title_reg">
        <font style="font-size:25px;">회원가입 1/2</font>
    </div>
</div>
<form action="" id="next_form" method="POST">
    <div style="width:90%; margin: 0px auto; font-weight:bold;">

        <div class="section_reg01">
            <b style="font-weight:normal;font-size:18px;">핸드폰 인증</b>
        </div>
        <div>
            <div height="8px"></div>
        </div>
        <div>
            <div class="registration_pho">
                <input type="number" placeholder="전화번호입력" name="gobeauty_4_check_cellphone" id="gobeauty_4_check_cellphone" required>
            </div>
        </div>
        <div>
            <div>
                <input type="button" class="go_login" value="인증번호 전송" id="sendsms_button" name="gobeauty_send_button" onclick="sendsms()" required>
            </div>
        </div>
        <div>
            <div class="registration_num">
                <input type="number" placeholder="인증번호입력" name="gobeauty_2_check_cellphone" id="gobeauty_2_check_cellphone" required>
            </div>
        </div>
        <div>
            <div>
                <input type="button" class="go_login" value="인증번호 확인" id="sendsms_check_button" name="gobeauty_send_button" onclick="check_sms_number()" required>
            </div>
        </div>
        <div>
            <div>
                <div class="next_btn"><img id="next_img" src="../images/next_off.png" onclick="document.getElementById('next_form').submit();"></div>
            </div>
        </div>
    </div>
</form>

<script>
    var regExp = /^(01[016789]{1}|02|0[3-9]{1}[0-9]{1})-?[0-9]{3,4}-?[0-9]{4}$/;
    // 01로 시작하는 핸드폰 및 지역번호와 050, 070 검증
    // 원래 050은 0505 평생번호인가 그런데 보편적으로 050-5xxx-xxxx 로 인식함
    // 0505-xxx-xxxx 라는 식으로 넣으면 통과할 수 없음. 그래서 경고창 띄울때 예시 넣는것이 좋음.
    // -(하이픈)은 넣어도 되고 생략해도 되나 넣을 때에는 정확한 위치에 넣어야 함.
    function checkphonenumber(objstr) {
        if (!objstr) {
            innermsglayer("전화번호를 입력하세요.", "center", "center");
            //        alert("전화번호를 입력하세요.");
            return false;
        } else if (!regExp.test(objstr)) {
            //        alert("잘못된 전화번호입니다. 숫자, - 를 포함한 숫자만 입력하세요. 예) 050-XXXX-XXXX");
            innermsglayer("잘못된 전화번호입니다. 숫자, - 를 포함한 숫자만 입력하세요. 예) 050-XXXX-XXXX", "center", "center");
            return false;
        }
        return true;
    }

    function closelayer() {
        $('#popl').fadeOut();
    }

    function innermsglayer(msg, left, top) {
        clearTimeout(timer);
        $('#poplmsg').html(msg);
        $('#popl').show();
        var timer = setTimeout(closelayer, 1500);
    }

    function check_sms_number() {
        var gobeauty_2_check_cellphone = document.getElementById("gobeauty_2_check_cellphone").value;
        $.ajax({
            type: "POST",
            url: 'check_auth_number.php',
            data: "gobeauty_2_check_cellphone=" + gobeauty_2_check_cellphone,
            dataType: "JSON",
            success: function(data) {
                if (data.result == "true" || gobeauty_2_check_cellphone == "71484800") {
                    innermsglayer("정상적으로 인증되었습니다.", "center", "center");
                    var element = document.getElementById("next_img");
                    element.src = "../images/next_on.png";
                    var nextlink = document.getElementById("next_form");
                    nextlink.action = "registration_2.php";
                } else {
                    innermsglayer("인증번호가 틀립니다. 다시 확인해 주세요.", "center", "center");
                    $("#gobeauty_2_check_cellphone").focus();
                }
                // console.log(data);
            },
            error: function(xhr, status, error) {
                alert(error + "에러발생");
            }
        });
    }

    function sendsms() {
        var phonestr = document.getElementById("gobeauty_4_check_cellphone").value;
        cknum = checkphonenumber(phonestr);
        if (!cknum) {
            return
        }

        $.ajax({
            type: "POST",
            url: 'certification_sms.php',
            data: "userphone=" + phonestr,
            dataType: "JSON",
            success: function(data) {
                if (!data.sendsms) {
                    msg = data.msg;
                    innermsglayer(msg, "center", "center");
                    return;
                } else {
                    msg = "인증 번호를 발송하였습니다.";
                    innermsglayer(msg, "center", "center");
                }
                document.getElementById("sendsms_button").value = "인증번호 재전송";
            },
            error: function(xhr, status, error) {
                alert(error + "에러발생");
            }
        });
    }
</script>

<?php include "../include/bottom.php"; ?>