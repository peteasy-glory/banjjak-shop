var include = {

	init : function(){
		this.header();
		this.sitemap();
		this.footer();
	},
	header : function(){
		var _html = '';
		_html+= '<header class="pc-header">';
		_html+= '	<h1><a href="#">반짝 반려미용샵의 단짝</a></h1>';
		_html+= '	<div class="pc-header-inner">';
		_html+= '		<div class="pc-header-menu-wrap">';
		_html+= '			<div class="pc-header-menu">';
		_html+= '				<div class="pc-header-menu-cell actived"><a href="#">신규등록</a></div>';
		_html+= '				<div class="pc-header-menu-cell"><a href="#">전체고객조회</a></div>';
		_html+= '				<div class="pc-header-menu-cell"><a href="#">예약 접수하기</a></div>';
		_html+= '				<div class="pc-header-menu-cell"><a href="#">상담대기<div class="label label-pink">12</div></a></div>';
		_html+= '			</div>';
		_html+= '			<a href="#" class="btn-page-ui btn-page-alarm actived"><div class="icon icon-size-24 icon-page-alarm">알람</div></a>';
		_html+= '		</div>';
		_html+= '	</div>';
		_html+= '</header>';

		$('body').prepend(_html);
	},

	sitemap : function(){
		var _html = '';
		_html+= '<nav id=gnb><div class=gnb-header><h2 class=page-title>전체메뉴</h2><button class="btn-page-close btn-page-menu-toggle btn-page-ui"type=button><span class="icon icon-size-24 icon-page-close">페이지 닫기</span></button></div><div class=gnb-wrap><div class=gnb-inner><div class=user-main-info><div class=info-item-wrap><div class=thumb-data><div class="content-thumb small"><img alt=""src=/static/pub/images/user_thumb.png></div></div><div class="align-self-start txt-data"><div class=txt-data-inner><div class=user-name>pettes_7832님</div><div class=user-cate>소망애견 미용실</div><div class=user-btns><a class=btn-gnb-logout href=#><div class="icon icon-logout-gray"></div>로그아웃</a></div></div></div></div></div><div class=gnb-menu-list><div class=basic-data-group><div class=con-title-group><a class=btn-gnb-toggle-menu href=#><span class=icons><span class="icon icon-size-24 off icon-gnb-menu-home-black"></span><span class="icon icon-size-24 on icon-gnb-menu-home-black-fill"></span></span>홈</a></div></div><div class="basic-data-group actived current"><div class=con-title-group><button class=btn-gnb-toggle-menu type=button><span class=icons><span class="icon icon-size-24 off icon-calendar-black"></span><span class="icon icon-size-24 on icon-calendar-black-fill"></span></span>예약 관리</button></div><div class=single-btns-list><div class="list-cell actived"><a class="arrow btn-single-item"href=#><div class=txt>상담 대기</div></a></div><div class=list-cell><a class="arrow btn-single-item"href=#><div class=txt>예약 접수하기<div class=tag-item>점주 예약</div></div></a></div></div></div><div class=basic-data-group><div class=con-title-group><button class=btn-gnb-toggle-menu type=button><span class=icons><span class="icon icon-size-24 off icon-dubble-user-black"></span><span class="icon icon-size-24 on icon-dubble-user-black-fill"></span></span>고객 관리</button></div><div class=single-btns-list><div class=list-cell><a class="arrow btn-single-item"href=#><div class=txt>전체 고객 조회</div></a></div><div class=list-cell><a class="arrow btn-single-item"href=#><div class=txt>신규 등록</div></a></div><div class=list-cell><a class="arrow btn-single-item"href=#><div class=txt>회원 등급 설정</div></a></div></div></div><div class=basic-data-group><div class=con-title-group><button class=btn-gnb-toggle-menu type=button><span class=icons><span class="icon icon-size-24 off icon-shop-black"></span><span class="icon icon-size-24 on icon-shop-black-fill"></span></span>샵 관리</button></div><div class=single-btns-list><div class=list-cell><a class="arrow btn-single-item"href=#><div class=txt>샵 대문 관리</div></a></div><div class=list-cell><a class="arrow btn-single-item"href=#><div class=txt>샵 정보 관리</div></a></div><div class=list-cell><a class="arrow btn-single-item"href=#><div class=txt>포트폴리오 관리</div></a></div><div class=list-cell><a class="arrow btn-single-item"href=#><div class=txt>샵 후기 관리</div></a></div><div class=list-cell><a class="arrow btn-single-item"href=#><div class=txt>샵 연동 블로그 관리</div></a></div></div></div><div class=basic-data-group><div class=con-title-group><button class=btn-gnb-toggle-menu type=button><span class=icons><span class="icon icon-size-24 off icon-set-black"></span><span class="icon icon-size-24 on icon-set-black-fill"></span></span>상세 설정</button></div><div class=single-btns-list><div class=list-cell><a class="arrow btn-single-item"href=#><div class=txt>업무/휴무 일정 관리</div></a></div><div class=list-cell><a class="arrow btn-single-item"href=#><div class=txt>권한 설정<div class=tag-item>접수 권한</div></div></a></div><div class=list-cell><a class="arrow btn-single-item"href=#><div class=txt>미용사 관리</div></a></div><div class=list-cell><a class="arrow btn-single-item"href=#><div class=txt>판매 상품 관리</div></a></div><div class=list-cell><a class="arrow btn-single-item"href=#><div class=txt>적립금 설정</div></a></div><div class=list-cell><a class="arrow btn-single-item"href=#><div class=txt>결제 방식</div></a></div></div></div><div class=basic-data-group><div class=con-title-group><button class=btn-gnb-toggle-menu type=button><span class=icons><span class="icon icon-size-24 off icon-money-black"></span><span class="icon icon-size-24 on icon-money-black-fill"></span></span>판매 실적</button></div><div class=single-btns-list><div class=list-cell><a class="arrow btn-single-item"href=#><div class=txt>판매실적 조회</div></a></div></div></div></div></div></div></nav>';
		
		$('body').prepend(_html);
	},

	footer : function(){
		var _html = '';
		_html+= '<footer class=pc-footer><div class=pc-footer-inner><h2>(주)펫이지 사업자 정보</h2><address class=address>대표: 신동찬<br>사업자등록번호: 157-86-01070 <a href=# class=font-underline target=_blank>사업자정보확인</a>    통신판매업 제 2021-서울종로-0183호<br>서울시 종로구 종로6 5층 서울창조경제혁신센터    개인정보 담당자: 이석범 <a href=mailTo:privacy@peteasy.kr>privacy@peteasy.kr</a></address><div class=pc-footer-bottom><div class=footer-etc-menu><ul><li><a href=#>이용약관</a><li><a href=#>개인정보처리방침</a></ul></div><div class=footer-copy>©PetEasy Co. Ltd. All rights reserved</div></div></div></footer>';
		
		$('body').append(_html);
	}
};

/**********************************
@ common
**********************************/
var common = {

	stage : {width : 0, height : 0, top : 0},
	toastPopElement : null,
	toastPopActive : false,
	toastPopTimer : null,
	pageBannerElement : null,
	pageBannerSlider : null,
	pageBannerSliderCur : 0,
	pageBannerSliderLen : -1,
	categorySwiperElement : null,
	categorySwiperSlider : null,
	categorySwiperSliderCur : 0,
	categorySwiperSliderLen : -1,
	pageTopFlag : false,
	
    init: function () {		

		//include.init();

		var _this = this;	
		
		//아코디언 메뉴 클릭 이벤트
		$(document).on('click' , '.btn-accordion-menu' , function(){
			common.accordionToggle($(this));
		});

		//전체 메뉴 클릭 이벤트
		$(document).on('click' , '.btn-page-menu-toggle' , function(){
			common.gnbToggle();
			return false;
		});

		//pc gnb 메뉴 클릭 이벤트
		$(document).on('click' , '.btn-gnb-toggle-menu' , function(){
			var $element = $(this);
			var $parents = $(this).parents('.basic-data-group');
			if(!$element.attr('href')){
				if($parents.hasClass('current')){
					$parents.removeClass('current');
				}else{
					$parents.addClass('current');
					//$parents.addClass('current').siblings().removeClass('current');
				}
			}
		});
		
		//메인 공통 배너
		if($('.page-banner-slider').length > 0 && $('.page-banner-wrap').hasClass('actived') == true){
			common.pageBannerElement = $('.page-banner-slider');
			common.pageBannerSliderLen = common.pageBannerElement.find('.swiper-slide').length;
			common.pageBannerSlider = new Swiper( common.pageBannerElement.find('.swiper-container')[0] , {
				loop : false,
				slidesPerView : 1 ,
				spaceBetween : 0,
				simulateTouch : true,
				speed : 450,
				navigation: {
				  nextEl: common.pageBannerElement.find('.btn-swiper-slider-next')[0],
				  prevEl: common.pageBannerElement.find('.btn-swiper-slider-prev')[0]
				}
			});
			common.pageBannerSlider.on('slideChange' , function(){
				common.pageBannerSliderCur = this.realIndex;
				common.pageBannerSwiperSort();
			});
			common.pageBannerSwiperSort();
		};

		if($('.import-request-slider').length > 0 ){
			importSlider = new Swiper($('.import-request-slider').find('.swiper-container')[0] , {
				loop : true,
				slidesPerView : 1 ,
				spaceBetween : 0,
				simulateTouch : true,
				speed : 450,
				autoplay : {
					delay : 3000,		
					disableOnInteraction:false
				},
				pagination: {
					el:$('.import-request-slider').find('.swiper-pagination')[0],
					clickable:true
				}
			});	
		};

		$(document).on('click' , '.btn-picture-ui' , function(){
			var $parents = $(this).parents('.picture-thumb-view');
			if($parents.hasClass('actived')){
				$parents.removeClass('actived')
			}else{
				$parents.addClass('actived')
			}
		});

		$(document).on('click' , '.btn-basic-item-ui-nav' , function(){
			var $parents = $(this).parents('.basic-item');
			if($parents.hasClass('actived')){
				$parents.removeClass('actived')
			}else{
				$parents.addClass('actived')
			}
		});
		
		// 맵 컨트롤러 핸들 클릭 이벤트
		$(document).on('click' , '#btnMapController' , function(){
			if($('#mapController').hasClass('actived')){
				$('#mapController').removeClass('actived');
				$('#mapDisplay').addClass('actived');
			}else{
				$('#mapController').addClass('actived');
				$('#mapDisplay').removeClass('actived');
			}
		});

		//카드 더보기 버튼 클릭
		$(document).on('click' , '.btn-pay-card-toggle' , function(){
			var $parents = $(this).parents('.pay-card-rule-wrap');
			if($parents.hasClass('actived')){
				$parents.removeClass('actived')
			}else{
				$parents.addClass('actived')
			}

		});

		//푸터 더보기 버튼 클릭
		$(document).on('click' , '.btn-footer-business' , function(){
			var $parents = $(this).parents('.footer-business');
			if($parents.hasClass('actived')){
				$parents.removeClass('actived')
			}else{
				$parents.addClass('actived')
			}
		});

		//카테고리 2뎁스 메뉴 
		//메인 공통 배너
		if($('.shop-category-menu').length > 0){
			common.categorySwiperElement = $('.shop-category-menu');
			common.categorySwiperSliderLen = common.categorySwiperElement.find('.swiper-slide').length;
			common.categorySwiperSlider = new Swiper( common.categorySwiperElement.find('.swiper-container')[0] , {
				loop : false,
				slidesPerView : 1 ,
				spaceBetween : 0,
				simulateTouch : true,
				speed : 450
			});
			common.categorySwiperSlider.on('slideChange' , function(){
				common.categorySwiperSliderCur = this.realIndex;
				common.categorySwiperSwiperSort();
			});
			common.categorySwiperSwiperSort();
		};

		//주문하기 상품 더보기 버튼
		$(document).on('click' , '.btn-order-product-toggle' , function(){
			if($(this).hasClass('actived')){
				$(this).removeClass('actived').html('주문 상품 전체 보기');
				$('.order-product-wrap').removeClass('actived');
			}else{
				$(this).addClass('actived').html('주문 상품 접기');
				$('.order-product-wrap').addClass('actived');
			}
		});

		//샵 상세 갤러리
		if($('.shop-view-gallery').length > 0 ){
			var shopViewSwiper = new Swiper( $('.shop-view-gallery').find('.swiper-container')[0] , {
				loop : false,
				slidesPerView : 1 ,
				spaceBetween : 0,
				simulateTouch : true,
				pagination: {
					el: $('.shop-view-gallery').find('.swiper-pagination')[0],
					clickable:true,
					bulletElement : 'button'
				}
			});	
		}

		//예약하기 캘린더 플로팅 버튼 토글 이벤트
		$(document).on('click' , '.btn-reserve-calendar-toggle' , function(){
			if($(this).next().hasClass('actived')){
				$(this).next().removeClass('actived');
				$('.page-cover').removeClass('actived');
			}else{
				$(this).next().addClass('actived');
				$('.page-cover').addClass('actived');
			}
		});

		

		this.scroll();
		this.resize();
	},
	categorySwiperSwiperSort : function(){		
		var _value = String((common.categorySwiperSliderCur + 1) + ' / ' + common.categorySwiperSliderLen);
		common.categorySwiperElement.find('.swiper-page').html(_value);
	},
	pageBannerSwiperSort : function(){		
		var _value = '<em>' + String((common.pageBannerSliderCur + 1) + '</em> / ' + common.pageBannerSliderLen);
		common.pageBannerElement.find('.swiper-page').html(_value);
	},
	accordionToggle:function(_obj){
		var parentsElement = _obj.parents('.accordion-list');
		parentsElement.find('.accordion-cell').each(function(){
			if($(this).index() == _obj.parents('.accordion-cell').index()){
				if($(this).hasClass('actived')){
					$(this).removeClass('actived');
				}else{
					$(this).addClass('actived');
				}
			}else{
				//$(this).removeClass('actived');
			}
		});
	},

	gnbToggle : function(){
		if($('#gnb').hasClass('actived')){
			$('#gnb').removeClass('actived');
		}else{
			$('#gnb').addClass('actived');
		}
	},

	containerScroll : function(){
		if(common.stage.width <= 1024){
			if($('.page-body').length > 0){
				var elementTop = $('.page-body').scrollTop();		
				if (elementTop >= 100) {
					if (!common.pageTopFlag) {
						$('#btnPageTop').stop(true).fadeIn(300);
					}
					common.pageTopFlag = true;

				} else {
					if (common.pageTopFlag) {
						$('#btnPageTop').stop(true).fadeOut(300);
					}
					common.pageTopFlag = false;
				}
			}
		}
	},

	scroll : function(){
		common.stage.top = $(window).scrollTop();	
		if(common.stage.width > 1024){
			if($('#btnPageTop').length > 0 ){
				if (common.stage.top >= 100) {
					if (!common.pageTopFlag) {
						$('#btnPageTop').stop(true).fadeIn(300);
					}
					common.pageTopFlag = true;

				} else {
					if (common.pageTopFlag) {
						$('#btnPageTop').stop(true).fadeOut(300);
					}
					common.pageTopFlag = false;
				}
			}
		}
	},	
	
	resize : function(){
		common.stage.width = $(window).width();
		common.stage.height = $(window).height();
		common.stage.top = $(window).scrollTop();	
	},

	mobileMenuCheck : function(){
		if($('#gnb').hasClass('open')){
			//전체 메뉴 닫기
			$('html').removeClass('fix');
			$('#header').removeClass('open');
			$('#gnb').removeClass('open');
		}else{
			//전체 메뉴 열기
			$('html').addClass('fix');
			$('#header').addClass('open');
			$('#gnb').addClass('open');
		}
			
	},

	toastPopOpen : function( id ){
		
		if(!common.toastPopActive){
			if(common.toastPopTimer) clearTimeout(common.toastPopTimer);
			common.toastPopActive = true;
			common.toastPopElement = $('#' + id);
			common.toastPopElement.addClass('actived');
			common.toastPopTimer = setTimeout(function(){
				common.toastPopElement.removeClass('actived');
				common.toastPopActive = false;
			} , 1500);
		}
	},

	pageMove : function( _y ){
		$('.page-body').stop().animate({'scrollTop':_y}, {queue:false, duration:350});
	}
};


var pop = {
		
	element : null,
	elementArr : [],
	isActive : false,
	zIndex : 99999,

	init : function(){
		//팝업 컨텐츠 외 영역 클릭시 닫기 이벤트
		$(document).on('click' , '.layer-pop-wrap' , function(e){
			if(pop.isActive){
				if($(e.target).parents('.pop-data').length == 0){					
					pop.close();
				}
			}
		});
	},
	
	// 팝업 열기
	open : function( _id , _item ){
		pop.isActive = true;
		$('html').addClass('fix');
		this.element = $('#' + _id);
		this.element.addClass('actived').css({ 'z-index' : pop.zIndex });
		this.elementArr.push(this.element);
		pop.zIndex += 1;
	},
	
	//팝업 닫기
	close : function( _item ){
		var $element;
		pop.isActive = false;
		$('html').removeClass('fix');
		/*
		if(_item){
			$(_item).parents('.layer-pop-wrap').removeClass('actived');
		}else{
			this.element.removeClass('actived');
		}
		*/
		if(_item){
			$element= $(_item);
			$element.removeClass('actived');
		}else{
			$element =pop.elementArr[pop.elementArr.length - 1];
			$element.removeClass('actived');
		}
		pop.elementArr.pop();


	},

	change : function(){

	}
};

var main = {
	mainBigBannerElement : null,
	mainBigBannerSwiper : null,
	mainBigBannerSwiperCur : 0,
	mainBigBannerSwiperLen : -1,

	init : function(){
		main.mainBigBannerElement = $('.main-big-banner');
		main.mainBigBannerSwiperLen = main.mainBigBannerElement.find('.swiper-slide').length;
		main.mainBigBannerSwiper = new Swiper( main.mainBigBannerElement.find('.swiper-container')[0] , {
			loop : false,
			slidesPerView : 1 ,
			spaceBetween : 10,
			simulateTouch : true,
			/*
			speed : 900,
			autoplay : {
				delay : 3000,		
				disableOnInteraction:false
			},			
			pagination: {
				el: main.mainBigBannerElement.find('.swiper-pagination')[0],
				clickable:true,
				bulletElement : 'button'
			}
			*/
		});					

		main.mainBigBannerSwiper.on('slideChange' , function(){
			main.mainBigBannerSwiperCur = this.realIndex;
			main.mainBigBannerSwiperSort();
		});
		main.mainBigBannerSwiperSort();

	},
	mainBigBannerSwiperSort : function(){		
		var _value = '<em>' + String((main.mainBigBannerSwiperCur + 1) + '</em> / ' + main.mainBigBannerSwiperLen);
		main.mainBigBannerElement.find('.swiper-page').html(_value);
	},
};

var productGallery = {
	productGalleryElement : null,
	productGallerySwiper : null,
	productGallerySwiperCur : 0,
	productGallerySwiperLen : -1,

	init : function(){
		productGallery.productGalleryElement = $('.product-view-gallery');
		productGallery.productGallerySwiperLen = productGallery.productGalleryElement.find('.swiper-slide').length;
		productGallery.productGallerySwiper = new Swiper( productGallery.productGalleryElement.find('.swiper-container')[0] , {
			loop : false,
			slidesPerView : 1 ,
			spaceBetween : 0,
			simulateTouch : true,
			/*
			speed : 900,
			autoplay : {
				delay : 3000,		
				disableOnInteraction:false
			},			
			pagination: {
				el: productGallery.productGalleryElement.find('.swiper-pagination')[0],
				clickable:true,
				bulletElement : 'button'
			}
			*/
		});					

		productGallery.productGallerySwiper.on('slideChange' , function(){
			productGallery.productGallerySwiperCur = this.realIndex;
			productGallery.productGallerySwiperSort();
		});
		productGallery.productGallerySwiperSort();

	},
	productGallerySwiperSort : function(){		
		var _value = '<em>' + String((productGallery.productGallerySwiperCur + 1) + '</em> / ' + productGallery.productGallerySwiperLen);
		productGallery.productGalleryElement.find('.swiper-page').html(_value);
	},
};

$(function () {
	common.init();
	pop.init();

	if($('.main-big-banner').length > 0 ){
		main.init();
	};

	if($('.product-view-gallery').length > 0 ){
		productGallery.init();
	};

	/******************************************************
	@ Container Scroll
	******************************************************/
	if($('.page-body').length > 0 ){
		$('.page-body').on("scroll",function () {
			common.containerScroll();		
		});
	}


	/******************************************************
	@ Window Scroll
	******************************************************/
	$(window).on("scroll",function () {
		common.scroll();		
	});

	/******************************************************
	@ Window Resize
	******************************************************/
	$(window).on("resize",function () {
		common.resize();
		common.scroll();
	});

});

function iosSample(){
	if($('html').hasClass('ios')){
		$('html').removeClass('ios')
	}else{
		$('html').addClass('ios')
	}
}