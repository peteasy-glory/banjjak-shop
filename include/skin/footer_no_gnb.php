


<script>
	
	$(document).ready(function(){
		$(".btn-page-prev").click(function(){
			history.back(); return false;
		})
		
		/*
		$("#container").append(`
			<div class="page-bottom">
				<div class="common-navigation">
					<a href="/" class="btn-navigation-item navigation-1 <?=$userBottomActived[0]?>"><div class="icon"></div><div class="txt">홈</div></a>
					<a href="/reserve_main" class="btn-navigation-item navigation-2 <?=$userBottomActived[1]?>"><div class="icon"></div><div class="txt">예약</div></a>
					<a href="#" class="btn-navigation-item navigation-4">
						<div class="ani-icon">
							<div class="ani-icon-off"></div>
							<div class="ani-icon-on"></div>
						</div>
						<div class="icon"></div>
						<div class="txt">산책</div>
					</a>
					<a href="/shop_main" class="btn-navigation-item navigation-3 <?=$userBottomActived[3]?>"><div class="icon"></div><div class="txt">쇼핑</div></a>
					<a href="/mypage_main" class="btn-navigation-item navigation-5 <?=$userBottomActived[4]?>"><div class="icon"></div><div class="txt">마이반짝</div></a>
				</div>
			</div>
		`);
		*/
		
		$(".navigation-4").click(function(){
			
			var usr_email	= '<?=$_SESSION['gobeauty_user_id']?>';
			SET_MoveMenu(2, usr_email);
			/*
			if(usr_email==''){
				popalert.open('firstRequestMsg1', '로그인 후 이용해주세요.');
				return false;	
			}
			*/
			
		});
		
		/*
		if(typeof menu_idx !== 'undefined'){
			SET_MoveMenu(menu_idx, "");
		}else{
			SET_MoveMenu("0", "");	
		}
		//SET_MoveMenu("1", "");
		*/
		
		$(".common-navigation a").click(function(){
			var idx	= $(".common-navigation a").index($(this))	;
			SET_MoveMenu(idx, '<?=$_SESSION['gobeauty_user_id']?>');
		})
	});
	
	
	
	
	// app에서 호출
	function SET_MoveMenu(idx, email){
		$(".common-navigation a").removeClass("actived");
		$(".common-navigation a:eq("+idx+")").addClass("actived");
		//alert(window.navigator.userAgent);
		
		var varUA = window.navigator.userAgent.toLowerCase(); //userAgent 값 얻기

		if(varUA.indexOf("android") >-1){
	        Banjjak_Android.SET_MoveMenu(idx, email);
	    }else if(varUA.indexOf("iphone") > -1 || varUA.indexOf("ipad") > -1){
	        webkit.messageHandlers.SET_MoveMenu.postMessage(idx, email);
	    }
	}
</script>

</body>
</html>