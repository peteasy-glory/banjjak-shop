<?php include "../include/top.php"; ?>
<link rel="stylesheet" href="<?= $css_directory ?>/m_new.css?<?= filemtime($upload_static_directory . $css_directory . '/m_new.css') ?>">

<script>
    function wrapWindowByMask() {
        //화면의 높이와 너비를 구한다.
        var maskHeight = $(document).height();
        var maskWidth = $(window).width();

        //마스크의 높이와 너비를 화면 것으로 만들어 전체 화면을 채운다.
        $('#mask').css({
            'width': maskWidth,
            'height': maskHeight
        });

        //애니메이션 효과 - 일단 1초동안 까맣게 됐다가 80% 불투명도로 간다.
        $('#mask').fadeIn(1000);
        $('#mask').fadeTo("slow", 0.8);

        var windows = document.getElementById('window');
        windows.style.top = '100px';
        $('.window').show();
    }
    $(document).ready(function() {
        //닫기 버튼을 눌렀을 때
        $('.window .close_button').click(function(e) {
            //링크 기본동작은 작동하지 않도록 한다.
            e.preventDefault();
            $('#mask, .window').hide();
        });

        //검은 막을 눌렀을 때
        $('#mask').click(function() {
            $(this).hide();
            $('.window').hide();
        });
    });
</script>
<div id="cs_registration_agree">
	<div id="mask"></div>
	<div class="window" id="window">
		<div>
			<div colspan="2" style="margin:10px;color:#000000;font-size:14px;" id="detail_value">정보통신망 이용촉진 및 정보보호 등의 관한 법률에는 만 14세미만 아동의 개인정보 수집시 법정대리인의 동의를 받도록 규정하고 있으며, 만 14세 미만 아동이 법정대리인 동의없이 회원가입을 하는 경우 회원탈퇴 또는 서비스 이용이 제한 될 수 있습니다.</div>
		</div>
	</div>
	<div class="btn_back" valign="top"><a href="index.php"><img src="<?= $image_directory ?>/btn_back_2.png" width="30px"></a></div>
	<div class="wrap">
		<div>
			<div class="agree_ment">
				우리 만난 적은 없지만,<br>
				약관동의가 필요해요 <br>

			</div>
		</div>
		<div>
			<div height="8px"></div>
		</div>
		<div>
			<div class="check_01">
				<div>전체선택</div>
				<div><input type="checkbox" id="checkall"><label for="checkall"></label></div>
			</div>
		</div>

		<div>
			<div height="13px" colspan="2" align="right">
			</div>
		</div>
		<div class="check_02">
			<div><a href="<?= $mainpage_directory ?>/terms_of_service.php"><u>반짝 이용약관 동의</u></a></div>
			<div class="checkpop"><input type="checkbox" id="agree1" class="agree_list"><label for="agree1" class="ab"></label></div>
		</div>
		<div class="check_02">
			<div><a href="<?= $mainpage_directory ?>/privacy_policy.php"><u>개인정보 수집 및 이용 동의</u></a></div>
			<div class="checkpop"><input type="checkbox" id="agree2" class="agree_list"><label for="agree2" class="ab"></label></div>
		</div>
		<div class="check_02">
			<div><u onclick="wrapWindowByMask();">만 14세 이상입니다</u>
				<p><br />만 14세 이상 고객만 가입 가능합니다.</p>
			</div>
			<div class="checkpop"><input type="checkbox" id="agree3" class="agree_list"><label for="agree3" class="ab"></label></div>
		</div>
		<div class="check_02">
			<div>마켓팅정보 수신동의(선택)</div>
			<div class="checkpop"><input type="checkbox" id="agree4" class="agree_list"><label for="agree4" class="ab"></label></div>
		</div>
		<div>
			<div height="36px"></div>
		</div>
		<div>
			<div colspan="3" style="font-size:30px;" align="right">
				<div class="next_table" id="next_table" style="color:#999999;position:absolute;z-index:5;right:15px;bottom:15px;">
					<a href="#" id="nextlink"><img id="next_img" src="<?= $image_directory ?>/next_off.png" width="80px"></a>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
    $("#checkall").click(function() {
        //만약 전체 선택 체크박스가 체크된상태일경우
        if ($("#checkall").prop("checked")) {
            //해당화면에 전체 checkbox들을 체크해준다
            $("input[type=checkbox]").prop("checked", true);
            var element = document.getElementById("next_img");
            element.src = "<?= $image_directory ?>/next_on.png";
            var nextlink = document.getElementById("nextlink");
            nextlink.href = "registration_1.php";
            // 전체선택 체크박스가 해제된 경우
        } else {
            //해당화면에 모든 checkbox들의 체크를해제시킨다.
            $("input[type=checkbox]").prop("checked", false);
            var element = document.getElementById("next_img");
            element.src = "<?= $image_directory ?>/next_off.png";
            var nextlink = document.getElementById("nextlink");
            nextlink.href = "#";
        }
    });

    $(".agree_list").click(function() {
        checkall_toggle();
        if ($("#agree1").prop("checked") && $("#agree2").prop("checked") && $("#agree3").prop("checked")) {
            var element = document.getElementById("next_img");
            element.src = "<?= $image_directory ?>/next_on.png";
            var nextlink = document.getElementById("nextlink");
            nextlink.href = "registration_1.php";
        } else {
            var element = document.getElementById("next_img");
            element.src = "<?= $image_directory ?>/next_off.png";
            var nextlink = document.getElementById("nextlink");
            nextlink.href = "#";
        }
    });

    function checkall_toggle() {
        if ($("#agree1").prop("checked") && $("#agree2").prop("checked") && $("#agree3").prop("checked") && $("#agree4").prop("checked")) {
            $("#checkall").prop("checked", true);
        } else {
            $("#checkall").prop("checked", false);
        }
    }
</script>

<?php include "../include/bottom.php"; ?>