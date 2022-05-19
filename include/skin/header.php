<!DOCTYPE html>
<html lang="ko" class="">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<title>반짝</title>
	<meta name="format-detection" content="telephone=no">
	<link href="/static/pub/css/reset.css?v=<?=$cssVersion?>" rel="stylesheet"/>
	<link href="/static/pub/css/swiper.min.css?v=<?=$cssVersion?>" rel="stylesheet"/>
	<link href="/static/pub/css/jquery-ui.css?v=<?=$cssVersion?>" rel="stylesheet"/>
	<link href="/static/pub/css/common.css?v=<?=$cssVersion?>" rel="stylesheet"/>
	<link href="/static/pub/css/form.css?v=<?=$cssVersion?>" rel="stylesheet"/>
	<script src="/static/pub/js/jquery-3.4.1.min.js"></script>    
	<script src="/static/pub/js/jquery-ui.min.js"></script>    
	<script src="/static/pub/js/swiper.min.js"></script>
	<script src="/static/pub/js/common.js?v=<?=$jsVersion?>"></script>
	<script src="/static/pub/js/dev_common.js?v=<?=$jsVersion?>"></script>
	<script src="/static/pub/js/jquery.fileupload.js"></script>    
	<script src="/static/pub/js/jquery.ui.widget.js"></script>   
</head>
<body>        
<!-- [필수사항]을(를) 입력해주세요.  -->
<article id="firstRequestMsg1" class="layer-pop-wrap">
	<div class="layer-pop-parent">
		<div class="layer-pop-children">

			<div class="pop-data alert-pop-data">
				<div class="pop-body">
					<div class="msg-txt"></div>
				</div>
				<div class="pop-footer">
					<button type="button" class="btn btn-confirm" onclick="popalert.close();">확인</button>
				</div>
			</div>

		</div>
	</div>
</article>