


<script>
	
	$(document).ready(function(){
		$(".btn-page-prev").click(function(){
			location.href="home_main";
			return false;
		})
		
		<?if(!isset($footer_menu) || $footer_menu!==false){?>
		$("#container").append(`
			<div class="page-bottom">
				<div class="common-navigation">
					<a href="/home_main" class="btn-navigation-item navigation-1 <?=$bottomActived[0]?>"><div class="icon"></div><div class="txt">홈</div></a>
					<a href="<?=$reserve_main_page?>" class="btn-navigation-item navigation-6 <?=$bottomActived[1]?>" ><div class="icon"></div><div class="txt">예약관리</div></a>
					<a href="customer_inquiry" class="btn-navigation-item navigation-7 <?=$bottomActived[2]?>" ><div class="icon"></div><div class="txt">고객관리</div></a>
					<a href="shop_main" class="btn-navigation-item navigation-8 <?=$bottomActived[3]?>"><div class="icon"></div><div class="txt">샵관리</div></a>
					<a href="set_main" class="btn-navigation-item navigation-9 <?=$bottomActived[4]?>"><div class="icon"></div><div class="txt">설정</div></a>
				</div>
			</div>
		`);
		<?}?>

	})
</script>

<footer class="pc-footer">
	<div class="pc-footer-inner">
		<h2>(주)펫이지 사업자 정보</h2>
		<address class="address">
		대표: 신동찬<br>
		사업자등록번호: 157-86-01070 <a href="#" class="font-underline" target="_blank">사업자정보확인</a>&nbsp;&nbsp;&nbsp;&nbsp;통신판매업 제 2021-서울종로-0183호<br>
		서울시 종로구 종로6 5층 서울창조경제혁신센터&nbsp;&nbsp;&nbsp;&nbsp;개인정보 담당자: 이석범 <a href="mailTo:privacy@peteasy.kr">privacy@peteasy.kr</a>
		</address>
		<div class="pc-footer-bottom">
			<div class="footer-etc-menu">
				<ul>
					<li><a href="terms4">이용약관</a></li>
					<li><a href="terms3">개인정보처리방침</a></li>
				</ul>				
			</div>
			<div class="footer-copy">©PetEasy Co. Ltd. All rights reserved</div>
		</div>
	</div>
</footer>
</body>
</html>
