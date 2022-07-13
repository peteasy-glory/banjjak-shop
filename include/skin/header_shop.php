<?
//include "include/php_function.php";
function img_link_change_header($url){
	$url	= str_replace("/pet/upload", "/upload", $url);
	$url	= str_replace("/user/images", "/images", $url);
	return $url;
}

if($_SESSION['artist_flag'] === true){
    $footer_menu = false;
}

$artist_flag = (isset($_SESSION['artist_flag'])) ? $_SESSION['artist_flag'] : "";

if ($artist_flag === true) {
	$artist_id = (isset($_SESSION['shop_user_id'])) ? $_SESSION['shop_user_id'] : "";
	$user_id = (isset($_SESSION['shop_user_id'])) ? $_SESSION['shop_user_id'] : "";
	$user_name = (isset($_SESSION['shop_user_nickname'])) ? $_SESSION['shop_user_nickname'] : "";
} else {
	$artist_id = (isset($_SESSION['gobeauty_user_id'])) ? $_SESSION['gobeauty_user_id'] : "";
	$user_id = (isset($_SESSION['gobeauty_user_id'])) ? $_SESSION['gobeauty_user_id'] : "";
	$user_name = (isset($_SESSION['gobeauty_user_nickname'])) ? $_SESSION['gobeauty_user_nickname'] : "";
}

$shop_sql = "select * from tb_shop where customer_id = '" . $user_id . "'";
$shop_result = mysqli_query($connection, $shop_sql);
if ($shop_datas = mysqli_fetch_object($shop_result)) {
	
	//print_r($shop_datas);
	//exit;
	$front_image = $shop_datas->front_image;
    $name = $shop_datas->name;
	$working_years = $shop_datas->working_years;
	$self_introduction = $shop_datas->self_introduction;
	//$professional_field = $shop_datas->professional_field;
	$career = $shop_datas->career;
	//$license_indexs = $shop_datas->license_indexs;
	$region_and_cost = $shop_datas->region_and_cost;
	$enable_flag = $shop_datas->enable_flag;
	$update_time = $shop_datas->update_time;
	$is_finish_open_magic = $shop_datas->is_finish_open_magic;
	//$open_magin_step = $shop_datas->open_magin_step;
    $reserve_main = $shop_datas->reserve_main;

    // 예약관리 첫 페이지 구하기
    if($reserve_main == 0){
        $reserve_main_page = 'reserve_main_month';
    }else if($reserve_main == 1){
        $reserve_main_page = 'reserve_main_week';
    }else if($reserve_main == 2){
        $reserve_main_page = 'reserve_main_day';
    }

    // 페이지별 넣을 값 생성
    if(strpos($_SERVER['PHP_SELF'],'reserve_main_month') !== false){
        $change_reserve_main = 0;
    }
    if(strpos($_SERVER['PHP_SELF'],'reserve_main_week') !== false){
        $change_reserve_main = 1;
    }
    if(strpos($_SERVER['PHP_SELF'],'reserve_main_day') !== false){
        $change_reserve_main = 2;
    }


	//----- 상담 대기 건수 조회
	$pet_sql =
		"SELECT count(tpl.payment_log_seq) AS wait_count
			FROM tb_payment_log tpl, tb_mypet tm, tb_customer tc
			WHERE tpl.approval = 0
			AND tpl.pet_seq = tm.pet_seq 
			AND tpl.customer_id = tc.id
			AND tc.enable_flag = 1
			AND timestampdiff(minute, update_time, now()) < 720
			AND tpl.artist_id = '{$user_id}'";
	$pet_result = mysqli_query($connection, $pet_sql);
	// error_log('----- $pet_sql : ' . $pet_sql);
	if ($pet_result_rows = mysqli_fetch_object($pet_result)) {
		$wait_count = $pet_result_rows->wait_count;
	}

	//----- 댓글 안단 후기 댓글 수 조회
	$review_sql =
		"SELECT count(review_seq) AS review_count
			FROM tb_usage_reviews
			WHERE artist_reply is null
			AND is_delete = 0
			AND artist_id = '{$user_id}'";
	$review_result = mysqli_query($connection, $review_sql);
	// error_log('----- $review_sql : ' . $review_sql);
	if ($review_result_rows = mysqli_fetch_object($review_result)) {
		$review_count = $review_result_rows->review_count;
	}

    //----- 본인 정보 가져오기
    $customer_sql = "select * from tb_customer where id = '" . $_SESSION['gobeauty_user_id'] . "'";
    $customer_result = mysqli_query($connection, $customer_sql);
    $customer_datas = mysqli_fetch_object($customer_result);
    $nickname = $customer_datas->nickname;
}


//페이지 url 확인
$pars_url = $_SERVER['REQUEST_URI'];
$pos = strpos($pars_url, 'reserve_pay_management_1');
$pos1 = strpos($pars_url, 'reserve_waiting');
?>
<!DOCTYPE html>
<html lang="ko" class="">
<head>
	<meta charset="utf-8">
<!--	<meta name="viewport" content="width=device-width, initial-scale=1">-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0 , maximum-scale=1.0, user-scalable=no" />
    <link rel="shortcut icon" type="image/x-icon" href="https://www.gopet.kr/pet/ico/favicon.ico">
    <link rel="icon" type="image/x-icon" href="https://www.gopet.kr/pet/ico/favicon.png">
<!--    <meta name="viewport" content="user-scalable=no" />-->
	<title>반짝</title>
	<meta name="format-detection" content="telephone=no">
	<link href="/static/pub/css/reset.css?time=<?=$cssVersion?>" rel="stylesheet"/>
	<link href="/static/pub/css/swiper.min.css?time=<?=$cssVersion?>" rel="stylesheet"/>
	<link href="/static/pub/css/jquery-ui.css?time=<?=$cssVersion?>" rel="stylesheet"/>
	<link href="/static/pub/css/common.css?time=<?=$cssVersion?>" rel="stylesheet"/>
	<link href="/static/pub/css/form.css?time=<?=$cssVersion?>" rel="stylesheet"/>
	<script src="/static/pub/js/jquery-3.4.1.min.js"></script>    
	<script src="/static/pub/js/jquery-ui.min.js"></script>    
	<script src="/static/pub/js/swiper.min.js"></script>
	<script src="/static/pub/js/common.js"></script>
	<script src="/static/pub/js/dev_common.js"></script>
    <script type="text/javascript" src="https://ssl.daumcdn.net/dmaps/map_js_init/postcode.v2.js"></script>
    <script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
    <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=f2cf1f3b7e2ca88cb298196078ef550f&libraries=services"></script>
    <script src="https://kit.fontawesome.com/28b36311e4.js" crossorigin="anonymous"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-D73FL6EC4X"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-D73FL6EC4X');
    </script>

</head>
<body>        

<!-- 로딩화면 -->
<div id="loading" class="actived">
    <div class="loading-wrap">
        <div class="loading-bar">
            <div class="loading-obj">
                <svg xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50%" cy="50%" r="24"  class="background" stroke-linecap="butt"></circle>
                    <circle cx="50%" cy="50%" r="24"  class="yellow" stroke-linecap="butt" ></circle>
                </svg>
            </div>
        </div>
    </div>
</div>

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

<!--  기본 메세지 팝업(버튼2) -->
<article id="pop2" class="layer-pop-wrap">
	<div class="layer-pop-parent">
		<div class="layer-pop-children">

			<div class="pop-data alert-pop-data">
				<div class="pop-body">
					<div class="msg-title">확인</div>
					<div class="msg-txt">텍스트 입니다.</div>
				</div>
				<div class="pop-footer">
					<button type="button" class="btn btn-confirm btn-cc" onclick="popalert.close();">취소</button>
					<button type="button" class="btn btn-confirm btn-cf">확인</button>
				</div>
			</div>

		</div>
	</div>
</article>
<!-- //기본 메세지 팝업(버튼2) -->

<?if($lnb_show!==false){?>
	
<!-- 20220117 추가 -->
<header class="pc-header">
	<h1><a href="#">반짝 반려미용샵의 단짝</a></h1>
	<div class="pc-header-inner">
		<div class="pc-header-menu-wrap">
			<div class="pc-header-menu">
				<!-- pc-header-menu-cell 클래스에 actived 클랫 추가시 활성화 -->
				<div class="pc-header-menu-cell pc-header-menu-cell1"><a href="customer_pet_new">신규등록</a></div>
				<div class="pc-header-menu-cell pc-header-menu-cell2"><a href="customer_all_inquiry1">전체고객조회</a></div>
				<div class="pc-header-menu-cell pc-header-menu-cell3"><a href="reserve_main_month">예약 접수하기</a></div>
				<div class="pc-header-menu-cell pc-header-menu-cell4"><a href="reserve_advice_list_1">상담대기<!--<div class="label label-pink"></div>--></a></div>
			</div>
			<!-- 알람 있을때 btn-page-alarm 클래스에 actived클래스 추가시 활성화 -->
<!--			<a href="#" class="btn-page-ui btn-page-alarm actived"><div class="icon icon-size-24 icon-page-alarm">알람</div></a> -->
		</div>
	</div>
</header>
<!-- 20220117 추가 -->

<!-- 20220110 수정 -->
<nav id="gnb">
	<div class="gnb-header">
		<h2 class="page-title">전체메뉴</h2>
		<button type="button"class="btn-page-ui btn-page-close btn-page-menu-toggle"><span class="icon icon-size-24 icon-page-close">페이지 닫기</span></button>
	</div>
	<div class="gnb-wrap">
		<div class="gnb-inner">
			
			<div class="user-main-info">
				<div class="info-item-wrap">
					<div class="thumb-data">
						<div class="content-thumb small"><img src="https://image.banjjakpet.com<?= img_link_change_header($front_image) ?>" alt=""></div>
					</div>
					<div class="txt-data align-self-start">
						<div class="txt-data-inner">
							<div class="user-name"><?=$nickname?>님</div>
							<div class="user-cate"><a href="/shop_main"><?=$name?></a></div>
							<div class="user-btns"><a href="data/logout_process" class="btn-gnb-logout"><div class="icon icon-logout-gray"></div>로그아웃</a></div>
						</div>
					</div>
				</div>
			</div>
			<div class="gnb-menu-list">
				<!-- 20220117 수정 : 홈버튼 추가 -->
				<div class="basic-data-group" id="home_main" >
					<div class="con-title-group">
						<a href="home_main" class="btn-gnb-toggle-menu btn-gnb-toggle-menu-add"><span class="icons"><span class="icon icon-size-24 icon-gnb-menu-home-black off"></span><span class="icon icon-size-24 icon-gnb-menu-home-black-fill on"></span></span>홈</a>
					</div>
				</div>
				<!-- //20220117 수정 -->
				<div class="basic-data-group">
					<div class="con-title-group">
						<!-- 20220117 수정 : button 태그로 변경 및 컨텐츠 수정 -->
						<button type="button" class="btn-gnb-toggle-menu btn-gnb-toggle-menu-add" onClick="location.href='reserve_main_month';"><span class="icons"><span class="icon icon-size-24 icon-calendar-black off"></span><span class="icon icon-size-24 icon-calendar-black-fill on"></span></span>예약 관리</button>
						<!-- //20220117 수정 -->
					</div>
					<div class="single-btns-list">
						<!-- list-cell 클래스에 actived클래스 추가시 활성화 -->
						<div id="hiddenReserve" style="display:none;"></div>
						<div class="list-cell" id="reserve_advice_list_1"  ><a href="reserve_advice_list_1" class="btn-single-item arrow"><div class="txt">상담 대기</div></a></div>
						<div class="list-cell" id="reserve_accept"><a href="reserve_main_week" class="btn-single-item arrow"><div class="txt">예약 접수하기<div class="tag-item"></div></div></a></div>
					</div>
				</div>
				<div class="basic-data-group">
					<div class="con-title-group">
						<!-- 20220117 수정 : button 태그로 변경 및 컨텐츠 수정 -->
						<button type="button" class="btn-gnb-toggle-menu btn-gnb-toggle-menu-add" onClick="location.href='customer_inquiry';"><span class="icons"><span class="icon icon-size-24 icon-dubble-user-black off"></span><span class="icon icon-size-24 icon-dubble-user-black-fill on"></span></span>고객 관리</button>
						<!-- //20220117 수정 -->
					</div>
					<div class="single-btns-list">
						<div id="hiddenCustomer" style="display:none;"></div>
						<div class="list-cell" id="customer_all_inquiry1"><a href="customer_all_inquiry1" class="btn-single-item arrow"><div class="txt">전체 고객 조회</div></a></div>
						<div class="list-cell" id="customer_pet_new" ><a href="customer_pet_new" class="btn-single-item arrow"><div class="txt">신규 등록</div></a></div>
						<div class="list-cell" id="customer_grade"><a href="customer_grade" class="btn-single-item arrow"><div class="txt">회원 등급 설정</div></a></div>
					</div>
				</div>
                <?php if($_SESSION['artist_flag'] !== true){ ?>
				<div class="basic-data-group">
					<div class="con-title-group">
						<button type="button" class="btn-gnb-toggle-menu btn-gnb-toggle-menu-add" onClick="location.href='shop_main';"><span class="icons"><span class="icon icon-size-24 icon-shop-black off"></span><span class="icon icon-size-24 icon-shop-black-fill on"></span></span>샵 관리</button>
					</div>
					<div class="single-btns-list">
						<div id="hiddenShop" style="display:none;"></div>
						<div class="list-cell" id="shop_gate_picture_list" ><a href="shop_gate_picture_list" class="btn-single-item arrow"><div class="txt">샵 대문 관리</div></a></div>
						<div class="list-cell" id="shop_management" ><a href="shop_management" class="btn-single-item arrow"><div class="txt">샵 정보 관리</div></a></div>
						<div class="list-cell" id="shop_portfolio_list" ><a href="shop_portfolio_list" class="btn-single-item arrow"><div class="txt">포트폴리오 관리</div></a></div>
						<div class="list-cell" id="shop_review_list"><a href="shop_review_list" class="btn-single-item arrow"><div class="txt">샵 후기 관리</div></a></div>
						<div class="list-cell" id="shop_blog_list" ><a href="shop_blog_list" class="btn-single-item arrow"><div class="txt">샵 연동 블로그 관리</div></a></div>
					</div>
				</div>
				<div class="basic-data-group">
					<div class="con-title-group">
						<button type="button" class="btn-gnb-toggle-menu btn-gnb-toggle-menu-add" onClick="location.href='set_main';"><span class="icons"><span class="icon icon-size-24 icon-set-black off"></span><span class="icon icon-size-24 icon-set-black-fill on"></span></span>상세 설정</button>
					</div>
					<div class="single-btns-list">
						<div id="hiddenSet" style="display:none;"></div>
						<div class="list-cell" id="set_schedule_list"><a href="set_schedule_list" class="btn-single-item arrow"><div class="txt">업무/휴무 일정 관리</div></a></div>
						<div class="list-cell" id="set_right"><a href="set_right" class="btn-single-item arrow"><div class="txt">권한 설정<div class="tag-item">접수 권한</div></div></a></div>
						<div class="list-cell" id="set_teacher"><a href="set_teacher" class="btn-single-item arrow"><div class="txt">미용사 관리</div></a></div>
						<div class="list-cell" id="set_beauty_management"><a href="set_beauty_management" class="btn-single-item arrow"><div class="txt">판매 상품 관리</div></a></div>
						<div class="list-cell" id="set_save_money"><a href="set_save_money" class="btn-single-item arrow"><div class="txt">적립금 설정</div></a></div>
						<div class="list-cell" id="set_pay_type"><a href="set_pay_type" class="btn-single-item arrow"><div class="txt">결제 방식</div></a></div>
					</div>
				</div>
				<div class="basic-data-group">
					<div class="con-title-group">
						<button type="button" class="btn-gnb-toggle-menu btn-gnb-toggle-menu-add"><span class="icons"><span class="icon icon-size-24 icon-money-black off"></span><span class="icon icon-size-24 icon-money-black-fill on"></span></span>판매 실적</button>
					</div>
					<div class="single-btns-list">
						<div class="list-cell" id="stats_sale" ><a href="stats_sale" class="btn-single-item arrow"><div class="txt">판매실적 조회</div></a></div>
                        <!--<div class="list-cell" id="stats_sale" ><a href="javascript:popalert.confirm('firstRequestMsg1','준비중입니다.','home_main');" class="btn-single-item arrow"><div class="txt">판매실적 조회</div></a></div>-->
					</div>
				</div>
                <?php } ?>
                <div class="basic-data-group">
                    <div class="con-title-group">
                        <button type="button" class="btn-gnb-toggle-menu btn-gnb-toggle-menu-add"><span class="icons"><span class="icon icon-size-24 icon-other off"></span><span class="icon icon-size-24 icon-other-fill on"></span></span>기타</button>
<!--                        <div class="con-title"><div class="icon icon-size-24 icon-set-black"></div>기타</div>-->
                    </div>
                    <div class="single-btns-list">
                        <div id="hiddenMyMenu" style="display:none;"></div>
                        <div class="list-cell"><a href="mypage_inquiry_list" class="btn-single-item arrow"><div class="txt">1:1 문의하기</div></a></div>
                        <?php
                        /*if($user_id == 'itseokbeom@gmail.com' || $user_id == "jack@peteasy.kr" || $user_id == "eaden@peteasy.kr" || $user_id == "luna@peteasy.kr" || $user_id == "lisa@peteasy.kr"
                            || $user_id == "pickmon@pickmon.com" || $user_id == "sally@peteasy.kr" || $user_id == "dew@peteasy.kr" || $user_id == "joseph@peteasy.kr" || $user_id == "noah@peteasy.kr" || $user_id == "pettester@peteasy.kr"){*/
                            ?>
                            <div class="list-cell"><a href="shop_pay_list" class="btn-single-item arrow"><div class="txt">상품결제내역</div></a></div>
                            <?php
                        /*}*/
                        ?>
                        <div class="list-cell"><a href="mypage_member" class="btn-single-item arrow"><div class="txt">회원정보 관리</div></a></div>
                        <div class="list-cell"><a href="mypage_notice_list" class="btn-single-item arrow"><div class="txt">공지사항</div></a></div>
                    </div>
                </div>
			</div>			
		</div>
	</div>
</nav>
<!-- //20220110 수정 -->
<script>
	//메뉴 설정
	try {
		var menuId = "<?=$menuId?>";

		$("#"+menuId).addClass("actived");

		if (menuId != "home_main") {
			var preantId = $("#"+menuId).data()
			$("#"+menuId).parents('.basic-data-group').addClass("actived current");
		}

	}catch(e){
	}

</script>

<header id="header">	
	<?php if($header_menu===true){?>
	<!-- 20220110 수정 -->
	<div class="header-left">
		<button type="button" class="btn-page-ui btn-page-menu btn-page-menu-toggle"><span class="icon icon-size-36 icon-all-menu">메뉴</span></button>
	</div>
	<?php } else { ?>
	<div class="header-left">
		<a href="#" class="btn-page-ui btn-page-prev" id="btn_page_prev"><div class="icon icon-size-24 icon-page-prev">페이지 뒤로가기</div></a>
	</div>
	<?php  } ?>
	<div class="page-title"><?=$shop_title?></div>
	<!-- //20220110 수정 -->
    <div class="reserve-state-bar" id="empty_time_bar" style="display:none;">
        <div class="bar" style="width:33.33%"></div>
    </div>
	<?php
    if(isset($header_right)) {

        echo $header_right;
        if ($pos !== false) {
            echo '<div class="header-right">
                    <div class="label-group">
                        <div class="label-add-purple"><em>승인대기</em></div>
                    </div>
                </div>';
        }
    }else if(isset($reserve_waiting)){
        if ($pos1 !== false) {
            echo '<div class="header-right">
                    <div class="label-group">
                        <a href="https://partner.banjjakpet.com/mypage_notice_view?notice_seq=20"><div class="label-add-pink"><em>회원등급제란?</em></div></a>
                    </div>                                        
                </div>';
        }
    } else {
			
		?>
<!--		<div class="header-right" id="header_bell">
			<a href="#" class="btn-page-ui btn-page-alarm"><div class="icon icon-size-24 icon-page-alarm">알람</div></a>
		</div> -->
<?php }	?>
</header>
<?php } ?>
<script>
    window.onload = function(){
        $('#loading').removeClass("actived");
    }
</script>
