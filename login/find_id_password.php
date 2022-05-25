<?php include "../include/top.php"; ?>

<style>
a{text-decoration:none; }
a:link {color:black;}
a:visited {color:black;}
a:hover {color:black;}
a:active {color:black;}
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
        height:36px;
        line-height:36px;
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
}.change_reservation:active {
        position:relative;
        top:1px;
}
.cell_confirm:active {
	position:relative;
	top:1px;
}
*/

.header { position: relative; width: 100%; height: 50px; line-height: 50px; border-bottom: 1px solid #ccc; }
.header .header-back-btn { position: absolute; left: 10px; top: 12px; }
.header .header-back-btn img { width: 26px; }
.header .header-title { width:100%;height:50px;line-height:50px;text-align:center;font-size:18px;font-weight:bold; }
.header .header-title p { padding: 0px; margin: 0px; }
.header .header-home-btn { position: absolute; right: 10px; top: 12px; }
.header .header-home-btn img { width: 50px; }
</style>
<?php
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

	<div style="height:20px;"></div>

	<table width="100%">
	 <tr>
<?php
	if ($sequence == 1) {
?>
	  <td width="50%">
		<a href="?sequence=1">
		<div width="100%" style="vertical-align:middle;height:40px;text-align:center;background-color:#f5bf2e;color:#fff;font-weight:bold;">
			<table width="100%" height="40px"><tr><td>아이디찾기</td></tr></table>
		</div>
		</a>
	  </td>
	  <td width="50%">
		<a href="?sequence=2">
		<div width="100%" style="vertical-align:middle;height:40px;text-align:center;background-color:#cecaca;color:#fff;font-weight:bold;">
			<table width="100%" height="40px"><tr><td>비밀번호찾기</td></tr></table>
		</div>
		</a>
	  </td>
<?php
	} else {
?>
          <td width="50%">
		<a href="?sequence=1">
                <div width="100%" style="vertical-align:middle;height:40px;text-align:center;background-color:#cecaca;color:#fff;font-weight:bold;">
                        <table width="100%" height="40px"><tr><td>아이디찾기</td></tr></table>
                </div>
		</a>
          </td>
          <td width="50%">
		<a href="?sequence=2">
                <div width="100%" style="vertical-align:middle;height:40px;text-align:center;background-color:#f5bf2e;color:#fff;font-weight:bold;">
                        <table width="100%" height="40px"><tr><td>비밀번호찾기</td></tr></table>
                </div>
		</a>
          </td>
<?php
	}
	$okurlgo = "https://gopet.kr/pet/login/find_id_password_process.php?sequence=".$sequence;
?>
	 </tr>
	 <tr><td height="20px"></td></tr>
	 <tr>
	  <td colspan="2" align="center">
		<a href="auto_cellphone_confirm.php?okurl=<?=urlencode($okurlgo)?>" class="cell_confirm"><font style="color:#fff;">전화번호인증</font></a>
	  </td>
	 </tr>
	</table>

<?php include "../include/bottom.php"; ?>
