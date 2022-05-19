<?php
exit;
include("../user/join2_naver.php");

exit;
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

?>

<style>
    @font-face {
        font-family: 'SCDream2';
        src: url("../fonts/SCDream2.otf");
    }

    html,
    div,
    p,
    button {
        font-weight: bold;
        font-family: 'SCDream2';
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
        font-weight: bold;
        font-family: 'SCDream2';
        font-size: 0.9em;
        font-style: normal;
        line-height: 30px;
        width: 100px;
        text-decoration: none;
        text-align: center;
        float: right;

    }

    .go_login_02 {
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
        font-weight: bold;
        font-family: 'SCDream2';
        font-size: 0.9em;
        font-style: normal;
        line-height: 30px;

        width: 100px;
        text-decoration: none;
        text-align: center;
        float: right;

    }

    .go_login2 {
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
        display: none;
        text-indent: 0;
        border: 1px solid #f5bf2e;
        color: #ffffff;
        font-family: 'SCDream2';
        font-size: 16px;
        font-weight: bold;
        font-style: normal;
        line-height: 30px;
        width: 100%;
        text-decoration: none;
        text-align: center;
        padding: 2px 0px;
        margin-top: 50px;
    }

    .go_login3 {
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
        border: 1px solid #f5bf2e;
        color: #ffffff;
        font-family: 'SCDream2';
        font-size: 16px;
        font-weight: bold;
        font-style: normal;
        line-height: 30px;
        width: 100%;
        text-decoration: none;
        text-align: center;
        padding: 2px 0px;
        margin-top: 50px;
    }

    input[type=text],
    input[type=password],
    input[type=number] {
        width: 100%;
        height: 30px;
        display: inline-block;
        border: 0;
        border-bottom: 1px solid #f5bf2e;
        font-size: 15px;
        padding-left: 10px;
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

    .top_menu {
        height: 51px;
        position: relative;
    }

    .top_title {
        width: 100%;
        height: 19px;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        padding: 15px 0px 15px 0px;
        display: table;
        border-bottom: 0.5px solid #e1e1e1;
    }

    .top_title p {
        margin: 0px;
    }

    .top_back {
        position: absolute;
        bottom: 11px;
        left: 10px;
    }

    .content {
        margin: 30px;
        text-align: center;
    }

    .phone_number01 {
        float: left;
        width: 55%;
    }

    .phone_number02 {
        float: left;
        width: 55%;
    }

    .section_01 {
        height: 60px;
        width: 100%;
    }
</style>

<form action="naver_cellphone_process.php" id="next_form" method="POST">
    <div class="top_menu">
        <div class="top_back"><a href="<?= $mainpage_directory ?>/"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div>
        <div class="top_title">
            <p>핸드폰 인증</p>
        </div>
    </div>
    <div style="width:90%; margin: 0px auto; font-weight:bold;">

        <div>
            <div class="content">
                기존에 반짝을 이용하셨던 분이라면 핸드폰 번호를 이용하여 이전 데이터를 가져올 수 있습니다.
            </div>
        </div>

        <div class="section_01">
            <div class="phone_number01">
                <input type="number" placeholder="전화번호입력" name="gobeauty_4_check_cellphone" id="gobeauty_4_check_cellphone" required>
            </div>
            <div>
                <input type="button" class="go_login" value="인증 번호 전송" id="sendsms_button" name="gobeauty_send_button" onclick="sendsms()" required>
            </div>
        </div>
        <div class="section_02">
            <div class="phone_number02">
                <input type="number" placeholder="인증 번호 입력" name="gobeauty_2_check_cellphone" id="gobeauty_2_check_cellphone" required>
            </div>
            <div>
                <input type="button" class="go_login_02" value="인증 확인" id="sendsms_button" name="gobeauty_send_button" onclick="check_sms_number()" required>
            </div>
        </div>
        <div>
            <div height="80px" colspan="3" align="right"></div>
        </div>
        <div>
            <div colspan="3">
                <input type="button" href="#" id="nextlink" onclick="document.getElementById('next_form').submit();" class="go_login2" value="저장하기">
            </div>
        </div>
        <!--div>
            <div class="">
                <input type="button" class="go_login3" value="저장없이 진행하기" id="without_save" name="without_save_button" onclick="javascript:without_save()">
            </div>
        </div-->
    </div>
</form>

<div class="layer_pop" style="width:90%;left:5%;top:170px;display:none;opacity:1;" id=popl>
    <div class="layer_con">
        <p class="fwb" id=poplmsg>이름표 / 이메일을 입력하여 주세요.</p>
    </div>
</div>

<script>
    var regExp = /^(01[016789]{1}|02|0[3-9]{1}[0-9]{1})-?[0-9]{3,4}-?[0-9]{4}$/;

    function checkphonenumber(objstr) {
        if (!objstr) {
            innermsglayer("전화번호를 입력하세요.", "center", "center");
            return false;
        } else if (!regExp.test(objstr)) {
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
/*
    function without_save() {
        if (confirm("핸드폰 번호를 등록하지 않으실 경우, 샵을 통해 예약된 건을 확인하시기 어렵습니다. 번호 등록 없이 진행하시겠습니까?")) {
            location.href = "<?php echo $mainpage_directory ?>/";
        }
    }
*/
    function check_sms_number() {
        var gobeauty_2_check_cellphone = document.getElementById("gobeauty_2_check_cellphone").value;
        $.ajax({
            type: "POST",
            url: 'check_auth_number.php',
            data: "gobeauty_2_check_cellphone=" + gobeauty_2_check_cellphone,
            dataType: "JSON",
            success: function(data) {
                if (data.result == "true") {
                    $("#nextlink").show();
                    //$("#without_save").hide();
                    innermsglayer("인증되었습니다. 하단에 저장하기를 눌러주세요.", "center", "center");
                } else {
                    innermsglayer("인증번호가 틀립니다. 다시 확인해 주세요.", "center", "center");
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
        if (!(checkphonenumber(phonestr))) {
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
                    innermsglayer(msg, "center", "center")
                    return;
                } else {
                    msg = "인증 번호를 발송 하였습니다.";
                    innermsglayer(msg, "center", "center")
                }
                document.getElementById("sendsms_button").value = "재전송";

            },
            error: function(xhr, status, error) {
                alert(error + "에러발생");
            }
        });
    }
</script>

<?php include "../include/bottom.php"; ?>