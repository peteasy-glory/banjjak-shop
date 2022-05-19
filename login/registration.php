<?php include "../include/top.php"; ?>

<style>
.header{ height:50px;text-align:center; }

.main{ margin: 0 auto;
    width: 90%;
}

.main h4{ margin: 60px auto;
text-align: center; font-size: 2em; }

fieldset{
    border: none;
}

fieldset > div{
    position: relative;
    margin-bottom: 10px;
}


.none{
    display: none;
}


.error{ 
    font-size: 0.8em;
    position: absolute;
    width: auto;
    top: 18px; right: 10px;
    text-align: right;
    color: red;
}

.vaild{
    font-size: 0.8em;
    position: absolute;
    width: 90%;
    top: 18px; right: 10px;
    text-align: right;
    color: forestgreen;
}

input[type=text], select, input[type=password], input[type=id], input[type=phone] {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type=submit],input[type=boutton] {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px auto;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type=submit]:hover,input[type=boutton]:hover {
    background-color: #45a049;
}

#wrap_gender{
    border-radius: 4px;
    border: 1px solid #ccc;
    display: flex;
}

.gender{
    display: block;
    height: 100%; width: 50%;
    border-right: 1px solid #ccc;
}

.no_line{
    border-right:none;
}

.gender > label {
    display: block;
    /*width: 100%; height: 100%;*/
    padding: 10px 0;
    text-align: center;
    font-size: 0.8em;
    color: #666;
}

input[type=radio]{
   display: none;
}

.gender_act{
    display: inline-block;
    height: 100%; width: 50%;
    background-color: #45a049;
    color:#fff;
}

.gender_act > label {
    display: inline-block;
    width: 100%; height: 100%;
    padding: 10px 0;
    text-align: center;
    font-size: 0.8em;
    color: #fff;
}
</style>

<a href="<?=$login_directory?>/"><img src="<?=$image_directory?>/back.png" width="40px"/></a>
<div class="container">
	<div class="header">
		<div class="logo"><h3>- 회원가입 -</h3></div>
	</div>
        <div class="main">
            <div>
                <form action="<?=$login_directory?>/registration_process.php" method="POST">
                    <fieldset>
                        <div>
                            <label for="id"></label> <input type="id" id="id" name="gobeauty_id" placeholder="아이디" onblur="ck_id()" required> <div id="idch"></div>
                            <span id="MsgId" class="none">aa</span>
                        </div>
			<div>
                            <label for="phone"></label> <input type="phone" id="phone" name="gobeauty_phone" placeholder="전화번호" onblur="ck_phone()" required> 
                            <span id="MsgPhone" class="none">aa</span>
                        </div>
                        <div>
                            <label for="pwd"></label> <input type="password" id="pwd" name="gobeauty_pwd" placeholder="비밀번호" onblur="ck_pwd()" required>
                            <span id="MsgPw" class="none">유효성체크</span>
                        </div>  
                         <div>   
                            <label for="pwd_ck"></label> <input type="password" id="pwd_ck" name="gobeauty_pwd_ck" placeholder="비밀번호 확인" onblur="ck_pwd2()" required>
                             <span id="MsgPwck" class="none">유효성체크</span>
                        </div>    
                        <div>    
                            <label for="name"></label> <input type="text" id="name" name="gobeauty_name" placeholder="닉네임" onblur="ck_name()" required>
                            <span id="MsgName" class="none">유효성체크</span>
                        </div>

                        <input type="submit" onclick="javascript:return check_all();" value="Submit">
                    </fieldset>
                </form>
            </div>
        </div>
    </div>

<script>
$(document).ready(function(){
    
    $('#id').blur(function(){    
            $.ajax({
                type: "post",
                url : "check_id.php",
                data : {
                    id : $('#id').val()
                },
                success : function s(a){ $('#idch').html(a); },
                error : function error(){ alert('시스템 문제발생');}
            });
    });
    
});

function ck_id(){
        var id = document.getElementById("id")
        var MsgId = document.getElementById("MsgId")

        if(id.value.length < 5){
            MsgId.style.display="block";
            MsgId.className='error'
            MsgId.innerHTML="최소 5자리 이상 등록 하세요."
//            phone.focus()
            return false;
        } else{
            MsgId.className='vaild'
            MsgId.innerHTML="ok"
	    return true;
        }   
}

function ck_phone(){
        var phone = document.getElementById("phone")
        var MsgPhone = document.getElementById("MsgPhone")

        if(isNaN(phone.value) || phone.value.length < 10){
            MsgPhone.style.display="block";
            MsgPhone.className='error'
            MsgPhone.innerHTML="전화번호 형식을 확인하세요"
//            phone.focus()
            return false;
        } else{
            MsgPhone.className='vaild'
            MsgPhone.innerHTML="ok"
	    return true;
        }   
}

/*function ck_email(){
        var email = document.getElementById("email")
        var MsgId = document.getElementById("MsgId")
        var isEmail = /([\w\-]+\@[\w\-]+\.[\w\-]+)/

        if(!isEmail.test(email.value)){
            MsgId.style.display="block";
            MsgId.className='error'
            MsgId.innerHTML="이메일 형식을 확인하세요"
//            email.focus()
            return false;
        } else{
            MsgId.className='vaild'
            MsgId.innerHTML="ok"
        }   
}*/

function ck_pwd(){
        var pwd = document.getElementById("pwd")
        var MsgPw = document.getElementById("MsgPw")
        var isPwd = /^(?=.*[a-zA-Z])((?=.*\d)|(?=.*\W)).{6,20}$/
        
        if(!isPwd.test(pwd.value)){
            MsgPw.style.display="block";
            MsgPw.className='error'
            MsgPw.innerHTML="숫자포함 최소 6자리 이상"
//            pwd.focus()
            return false;
        } else{
            MsgPw.className='vaild'
            MsgPw.innerHTML="ok"
	    return true;
        }   
}


function ck_pwd2(){
        var pwd_ck = document.getElementById("pwd_ck")
        var pwd = document.getElementById("pwd").value
        var MsgPwck = document.getElementById("MsgPwck")
        
        if(pwd_ck.value!=pwd || pwd_ck.value == ""){
            MsgPwck.style.display="block";
            MsgPwck.className='error'
            MsgPwck.innerHTML="비밀번호가 일치하지 않습니다."
//            pwd_ck.focus()
            return false;
        } else{
            MsgPwck.className='vaild'
            MsgPwck.innerHTML="ok"
	    return true;
        }   
}


function ck_name(){
        var name = document.getElementById("name")
        var MsgName = document.getElementById("MsgName")
        
        if(name.value==''){
            MsgName.style.display="block";
            MsgName.className='error'
            MsgName.innerHTML="2자 이상 입력하세요."
//            name.focus()
            return false;
        } else{
            MsgName.className='vaild'
            MsgName.innerHTML="ok"
	    return true;
        }   
}

function check_all () {
	if (!ck_id()) {
		$.MessageBox({
		    buttonDone      : "확인",
		    message         : "아이디를 확인해주세요."
		}).done(function(){
			var id = document.getElementById("id");
			id.focus();
		});
		return false;
	}
	if (!ck_phone()) {
		$.MessageBox({
		    buttonDone      : "확인",
		    message         : "전화번호를 확인해주세요."
		}).done(function(){
			var id = document.getElementById("phone");
			id.focus();
		});
		return false;
	}
	if (!ck_pwd()) {
		$.MessageBox({
		    buttonDone      : "확인",
		    message         : "비밀번호를 확인해주세요."
		}).done(function(){
			var id = document.getElementById("pwd");
			id.focus();
		});
		return false;
	}
	if (!ck_pwd2()) {
		$.MessageBox({
		    buttonDone      : "확인",
		    message         : "비밀번호 확인을 확인해주세요."
		}).done(function(){
			var id = document.getElementById("pwd_ck");
			id.focus();
		});
		return false;
	}
	if (!ck_name()) {
		$.MessageBox({
		    buttonDone      : "확인",
		    message         : "닉네임을 확인해주세요."
		}).done(function(){
			var id = document.getElementById("name");
			id.focus();
		});
		return false;
	}

	return true;
}

function ck_gender(){
    var wrap_gender = document.getElementById("wrap_gender")
    var man = document.getElementById("man")
    var woman = document.getElementById("woman")
    var MsgGender = document.getElementById("MsgGender")

    
    if(man.checked){
        document.getElementById("wrap_man").className='gender_act';
        document.getElementById("wrap_woman").className='gender';
    }
    
    if(woman.checked){
        document.getElementById("wrap_woman").className='gender_act';
        document.getElementById("wrap_man").className='gender';
    }
}




</script>

<?php include "../include/bottom.php"; ?>

