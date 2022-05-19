<?php
//	include "../include/top.php";
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header_shop.php");

	
	$r_email = ($_GET["email"] && $_GET["email"] != "")? $_GET["email"] : "";
	$r_sub = ($_GET["sub"] && $_GET["sub"] != "")? $_GET["sub"] : "";
	$r_status = ($_GET["status"] && $_GET["status"] != "")? $_GET["status"] : "";
    // apple
    $_SESSION['gobeauty_user_id'] = 'pettester@peteasy.kr';
    $_SESSION['my_shop_flag']='1';
?>
<style>
	ul { list-style: none; padding: 0px; margin: 0px; }
	input[type='text'], input[type='number'], button { -webkit-appearance: none; -moz-appearance: none; appearance: none; padding: 0px; margin: 0px; }
	.top_menu { position: fixed; left: 0px; top: 0px; width: 100%; height: 50px; border-bottom: 1px solid #ccc; background-color: rgba(255, 255, 255, 0.8); z-index: 1; }
	.top_menu .top_back { position: absolute; left: 0px; top: 0px; width: 50px; height: 40px; text-align: center; padding-top: 10px; }
	.top_menu .top_title { position: absolute; left: 0px; top: 0px; width: 100%; text-align: center; } 
	#apple_cellphone { margin-top: 61px; display: none; }
	#apple_cellphone.on { display: block; }
	#apple_cellphone h4 { margin: 30px 10px; font-size: 14px; text-align: center; }
	#apple_cellphone ul.agree_wrap { border: 1px solid #ccc; margin: 20px; padding: 10px; }
	#apple_cellphone ul.agree_wrap li:first-child { border-bottom: 1px solid #ccc; margin-bottom: 10px; }
	#apple_cellphone ul.agree_wrap li { text-align: left; }
	#apple_cellphone ul.agree_wrap li>div { height: 40px; line-height: 40px; font-size: 14px; }
	#apple_cellphone ul.agree_wrap li input[type="checkbox"] { display: none; margin: 0px; padding: 0px; font-size: 0px; border: 0px; }
	#apple_cellphone ul.agree_wrap li input[type="checkbox"]+label>span { position: absolute; right: 10px; top: 10px; display: inline-block; border: 1px solid #ccc; background-color: #eee; width: 24px; height: 24px; }
	#apple_cellphone ul.agree_wrap li input[type="checkbox"]:checked+label>span { background-color: #000; border: 1px solid #000; color: #fff; }
	#apple_cellphone ul.agree_wrap li input[type="checkbox"]:checked+label>span:before { content: ' '; position: absolute; left: 8px; top: 1px; display: inline-block; border-right: 3px solid #ccc; border-bottom: 3px solid #ccc; width: 7px; height: 14px; transform: rotate(45deg); }
	#apple_cellphone ul li { position: relative; padding: 2px; text-align: center; height: 50px; }
	#apple_cellphone input[type='number'] { position: absolute; left: 10px; top: 0px; width: calc(100% - 140px); height: 30px; border: 0px; border-bottom: 1px solid #ccc; padding: 5px; }
	#apple_cellphone ul li button { position: absolute; right: 10px; top: 0px; min-width: 100px; height: 40px; border: 1px solid #ccc; border-radius: 10px; }
	#apple_cellphone button.on { border: 1px solid #000; background-color: #000; color: #fff; }
	#apple_cellphone .btn_wrap { width: 100%; text-align: center; }
	#apple_cellphone button.set_insert_apple_btn { height: 40px; border: 1px solid #ccc; border-radius: 10px; width: calc(100% - 20px); margin-top: 20px; }
</style>
<div class="top_menu">
	<!--div class="top_back"><a href="<?= $mainpage_directory ?>/"><img src="<?= $image_directory ?>/btn_back_2.png" width="26px"></a></div-->
	<div class="top_title">
		<p>Apple Login</p>
	</div>
</div>

<div id="apple_cellphone">
	<h4>
		기존에 반짝을 이용하셨던 분이라면<br/> 핸드폰 번호를 이용하여 이전 테이터를 가져올 수 있습니다.
	</h4>
	<form id="apple_login_cellphone" method="POST">
		<input type="hidden" name="apple_email" value="<?=$r_email ?>" />
		<input type="hidden" name="sub" value="<?=$r_sub ?>" />

		<ul class="agree_wrap">
			<li>
				<div>전체선택</div>
				<input type="checkbox" id="agree_all" />
				<label for="agree_all"><span></span></label>
			</li>
			<li>
				<div>반짝 이용약관 동의</div>
				<input type="checkbox" id="agree_1" />
				<label for="agree_1"><span></span></label>
			</li>
			<li>
				<div>개인정보 수집 및 이용 동의</div>
				<input type="checkbox" id="agree_2" />
				<label for="agree_2"><span></span></label>
			</li>
			<li>
				<div>만 14세 이상입니다</div>
				<input type="checkbox" id="agree_3" />
				<label for="agree_3"><span></span></label>
			</li>
			<li>
				<div>마케팅정보 수신동의(선택)</div>
				<input type="checkbox" id="agree_4" />
				<label for="agree_4"><span></span></label>
			</li>
		</ul>
		<ul>
			<li>
				<input type="number" name="cellphone" placeholder="'-'없이 전화번호 입력" />
				<button type="button" class="send_sms_btn">인증번호 전송</button>
			</li>
			<li>
				<input type="number" name="checknum" placeholder="인증번호 입력" />
				<button type="button" class="confirm_sms_btn">인증 확인</button>
			</li>
		</ul>
		<div class="btn_wrap">
			<button type="button" class="set_insert_apple_btn">저장하기</button>
		</div>
	</form>
</div>

<script>
var is_pc = "0";
var auth_data = "";
var closelayer = 0;
var is_auth = false;

$(function(){
	$.ajax({
		type: "POST",
		url: 'apple_cellphone_ajax.php',
		data: {
			mode: "get_customer_cnt",
			email: "<?=$r_email ?>",
			sub: "<?=$r_sub ?>"
		},
		dataType: "JSON",
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				if(data.data > 0){
					//location.href = "apple_process.php?email=<?=$r_email ?>";
					login_process();
				}else{
					/*
					$.MessageBox({
						buttonDone  : "확인",
						message  : "<center>등록된 회원 아이디가 없습니다.</center>"
					}).done(function(){
						location.href = "/pet/login/index.php";
					});
					*/
					$("#apple_cellphone").addClass("on"); // 회원으로 등록되지 않았을 경우 회원가입 표시
				}
			}else{
				alert(data.data+"("+data.code+")");
			}
		},
		error: function(xhr, status, error) {
			alert(error + "에러발생");
		}
	});
});

// 전체선택
$(document).on("click", "#agree_all", function(){
	if($(this).is(":checked")){
		$("input[type='checkbox']").each(function(){
			$(this).prop("checked", true);
		});
	}else{
		$("input[type='checkbox']").each(function(){
			$(this).prop("checked", false);
		});
	}
});

// 전체선택 해제
$(document).on("click", "input[type='checkbox']", function(){
	if(!$(this).is(":checked") && $(this).attr("id") != "agree_all"){
		$("#agree_all").prop("checked", false);
	}
});

function login_process(){
	$.ajax({
		type: "POST",
		url: 'apple_cellphone_ajax.php',
		data: {
			mode: "set_login_process",
			email: "<?=$r_email ?>"
		},
		dataType: "JSON",
		success: function(data) {
			if(data.code == "000000"){
				console.log(data.data);
				if(data.data == "APP_GOBEAUTY_iOS"){
					try {
					window.webkit.messageHandlers.onAppLogin.postMessage('<?=$r_email ?>');
					} catch(e) {
						console.log(e);
					}
				}else if(data.data == "APP_GOBEAUTY_AND"){
					window.Android.onAppLogin('<?=$r_email ?>');
					location.href = "../home_main";
				}else{
					location.href = data.data;
				}
			}else{
				alert(data.data+"("+data.code+")");
			}
		},
		error: function(xhr, status, error) {
			alert(error + "에러발생");
		}
	});
}

// 쿠키 가져오기
function getCookie(cName) {
	cName = cName + '=';
	var cookieData = document.cookie;
	var start = cookieData.indexOf(cName);
	var cValue = '';
	if(start != -1){
		start += cName.length;
		var end = cookieData.indexOf(';', start);
		if(end == -1)end = cookieData.length;
		cValue = cookieData.substring(start, end);
	}
	return unescape(cValue);
}

// 인증번호 전송
$(document).on("click", "#apple_cellphone .send_sms_btn", function(){
	var cellphone = $("#apple_cellphone input[name='cellphone']").val();
	console.log(cellphone);
	if(cellphone != ""){
		if(isMobile(cellphone)){
			var validate_cellphone = cellphone.replace(/[^0-9]/g,'');
			console.log(validate_cellphone);
			sendsms(validate_cellphone);
			//tmp_code();
		}else{
			$.MessageBox("연락처를 정확히 입력해주세요.");
		}
	}else{
		$.MessageBox("인증받을 연락처를 입력해주세요.");
	}
});

// 인증 확인
$(document).on("click", "#apple_cellphone .confirm_sms_btn", function(){
	var checknum = $("#apple_cellphone input[name='checknum']").val();
	console.log(checknum);
	if(checknum != ""){
		check_sms_number(checknum);
	}else{
		$.MessageBox("인증번호를 입력해주세요.");
	}
});

function check_sms_number(checknum) {
	$.ajax({
		type: "POST",
		url: 'apple_cellphone_ajax.php',
		data: {
			mode: "chk_code",
			code: checknum,
			auth: auth_data
		},
		dataType: "JSON",
		success: function(data) {
			if (data.code == "000000"){
				innermsglayer("인증되었습니다.", "center", "center");
				$("input[name='cellphone']").prop("readonly", true);
				$("input[name='checknum']").prop("readonly", true);
				$("button.send_sms_btn").prop("disabled", true);
				$("button.confirm_sms_btn").prop("disabled", true);
				is_auth = true;
			} else {
				innermsglayer(data.data+"("+data.code+")", "center", "center");
				console.log(data.data);
			}
		},
		error: function(xhr, status, error) {
			alert(error + "에러발생");
		}
	});
}

function tmp_code(){
	$.ajax({
		type: "POST",
		url: 'apple_cellphone_ajax.php',
		data: {
			mode: 'tmp_code'
		},
		dataType: "JSON",
		success: function(data) {
			if (data.code == "000000"){
				console.log(data.data);
				auth_data = data.data.code;
			} else {
				alert(data.data+"("+data.code+")");
			}
		},
		error: function(xhr, status, error) {
			alert(error + "에러발생");
		}
	});
}

function sendsms(phonestr) {
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
				console.log(data.data);
				auth_data = data.data;
				innermsglayer("인증 번호를 발송 하였습니다.", "center", "center");
			}
			$(".send_sms_btn").text("재전송");

		},
		error: function(xhr, status, error) {
			alert(error + "에러발생");
		}
	});
}

$(document).on("click", ".set_insert_apple_btn", function(){
	validate();
});

function validate(){
	if(!$("#agree_1").is(":checked")){
		$.MessageBox("이용약관에 동의 해주세요.");
		return false;
	}
	if(!$("#agree_2").is(":checked")){
		$.MessageBox("이용약관에 동의 해주세요.");
		return false;
	}
	if(!$("#agree_3").is(":checked")){
		$.MessageBox("이용약관에 동의 해주세요.");
		return false;
	}
	if(!is_auth){
		$.MessageBox("휴대폰 인증을 해주세요.");
		return false;
	}

	if(validate){
		apple_join();
	}
}

function apple_join(){
	var tmp_url = "";
	var post_data = $("#apple_login_cellphone").serialize();
	console.log(post_data);
	post_data += "&mode=set_insert_customer";
	//console.log("<?=$r_email ?>"+"/"+$("input[name='cellphone']").val());

	$.ajax({
		type: "POST",
		url: 'apple_cellphone_ajax.php',
		data: post_data,
		dataType: "JSON",
		success: function(data) {
			if (data.code == "000000"){
				tmp_url = data.data;
				//$.ajax({
				//	type: "POST",
				//	url: 'apple_cellphone_ajax.php',
				//	data: {
				//		mode: "set_update_customer_data",
				//		email: "<?=$r_email ?>",
				//		cellphone: $("input[name='cellphone']").val()
				//	},
				//	dataType: "JSON",
				//	success: function(data) {
				//		if (data.code == "000000"){
							if(tmp_url == "APP_GOBEAUTY_iOS"){
								try {
								window.webkit.messageHandlers.onAppLogin.postMessage('<?=$r_email ?>');
								} catch(e) {
									console.log(e);
								}
							}else if(tmp_url == "APP_GOBEAUTY_AND"){
								window.Android.onAppLogin('<?=$r_email ?>');
								location.href = "/pet/mainpage/index.php";
							}else{
								location.href = tmp_url;
							}
				//		} else {
				//			alert(data.data+"("+data.code+")");
				//		}
				//	},
				//	error: function(xhr, status, error) {
				//		alert(error + "에러발생");
				//	}
				//});
			} else {
				alert(data.data+"("+data.code+")");
			}
		},
		error: function(xhr, status, error) {
			alert(error + "에러발생");
		}
	});
}


function innermsglayer(msg, left, top) {
	clearTimeout(timer);
	$.MessageBox(msg);
	var timer = setTimeout(closelayer, 1500);
}

/**
 * 핸드폰번호 형식 체크
 *
 * @param 데이터 
 */ 
function isMobile(phoneNum) {
	var regExp =/(01[016789])([1-9]{1}[0-9]{2,3})([0-9]{4})$/; 
	var myArray; 
	if(regExp.test(phoneNum)){ 
		myArray = regExp.exec(phoneNum); 
		// console.log(myArray[1]); 
		// console.log(myArray[2]); 
		// console.log(myArray[3]); 
		return true; 
	} else { 
		return false; 
	} 
}

</script>

<?php
	include "../include/bottom.php";
?>
