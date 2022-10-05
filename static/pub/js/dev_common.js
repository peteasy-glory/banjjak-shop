function img_link_change(img){
	var img 	= img.replace("/pet/images", "/images");
	var img 	= img.replace("/pet/upload", "/upload");
	var img 	= img.replace("/pet/mainpage", "");
	
	return img;
}


var popalert = {		
	element : null,
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
		$('#' + _id+" .msg-txt").text(_item);
		this.element.addClass('actived').css({ 'z-index' : pop.zIndex });
		pop.zIndex += 1;
	},
	
	// 팝업 열기
	confirm : function( _id , _item, _url ){
		pop.isActive = true;
		$('html').addClass('fix');
		this.element = $('#' + _id);
		$('#' + _id+" .msg-txt").text(_item);
		$('#' + _id+" .btn-cf").click(function(){
			location.href=_url;
		})
		
		this.element.addClass('actived').css({ 'z-index' : pop.zIndex });
		pop.zIndex += 1;
	},
	
	
	back : function( _id , _item ){
		pop.isActive = true;
		$('html').addClass('fix');
		this.element = $('#' + _id);
		$('#' + _id+" .msg-txt").text(_item);
		$('#' + _id+" .btn-confirm").click(function(){
			history.back();
		});
		this.element.addClass('actived').css({ 'z-index' : pop.zIndex });
		pop.zIndex += 1;
	},
	
	//팝업 닫기
	close : function( _item ){
		pop.isActive = false;
		$('html').removeClass('fix');

		if(_item){
			$(_item).parents('.layer-pop-wrap').removeClass('actived');
		}else{
			this.element.removeClass('actived');
		}

	},

	change : function(){

	}
};

var layerPop = {
	element : null,
	isActive : false,
	zIndex : 99999,

	init : function(){			
		//팝업 컨텐츠 외 영역 클릭시 닫기 이벤트
		$('body').append('<article id="commonLayerPopup" class="layer-pop-wrap"></article>');

		$('#commonLayerPopup').on('click', function(e){
			if(layerPop.isActive){
				if($(e.target).parents('.pop-data').length == 0){					
					layerPop.close();
				}
			}
		});
	},
	
	// 팝업 열기
	alert : function( message ){
		layerPop.isActive = true;
		$('html').addClass('fix');
		
		$('#commonLayerPopup').html(layerPop.alertLayer(message));
		this.element = $('#commonLayerPopup');
		this.element.addClass('actived').css({ 'z-index' : layerPop.zIndex });	
	},

	confirm : function( title,message ){
		layerPop.isActive = true;
		$('html').addClass('fix');
		
		$('#commonLayerPopup').html(layerPop.confirmLayer(title,message));
		this.element = $('#commonLayerPopup');
		this.element.addClass('actived').css({ 'z-index' : layerPop.zIndex });	
	},

	selectDistance : function (distance) {
		var layerTxt = `
			<div class="layer-pop-parent">
				<div class="layer-pop-children">
	
					<div class="pop-data alert-pop-data">
						<div class="pop-body">
							<div class="distance-set-range">
								<div class="info-txt">고객님의 위치를 기준으로 ‘거리순’을 설정하세요.<br>최대 30Km까지 확인하실 수 있습니다.</div>
								<div class="range-wrap">
									<div class="range-bar">
										<div id="slider-range" class="bar"></div>
									</div>
									<div class="range-value"><strong><em id="slider-value">`+distance+`</em>Km</strong> 이내</div>
								</div>
							</div>
						</div>
						<div class="pop-footer">
							<button type="button" class="btn btn-confirm" onclick="layerPop.selectDistanceAction();">확인</button>
						</div>
					</div>
	
				</div>
			</div>`;
		layerPop.isActive = true;
		$('html').addClass('fix');
		
		$('#commonLayerPopup').html(layerTxt);
		this.element = $('#commonLayerPopup');
		this.element.addClass('actived').css({ 'z-index' : layerPop.zIndex });	
	},

	confirmAction : function () {
		layerPop.close();
		window.dispatchEvent(new Event(confirmAction()));
	},

	selectDistanceAction : function () {
		layerPop.close();
		window.dispatchEvent(new Event(selectDistanceAction()));
	},
	
	//팝업 닫기
	close : function(){
		var $element;
		layerPop.isActive = false;
		$('html').removeClass('fix');

		$element= $('#commonLayerPopup');
		$element.removeClass('actived');
	},

	alertLayer : function (message) {
		var alertLayerTag = `
			<div class="layer-pop-parent">
				<div class="layer-pop-children">

					<div class="pop-data alert-pop-data">
						<div class="pop-body">
							<div class="msg-txt">`+message+`</div>
						</div>
						<div class="pop-footer">
							<button type="button" class="btn btn-confirm btn-cf" onclick='layerPop.close();' >확인</button>
						</div>
					</div>

				</div>
			</div>`;

		return alertLayerTag;
	},

	confirmLayer : function (title, message) {
		var alertLayerTag = `
			<div class="layer-pop-parent">
				<div class="layer-pop-children">

					<div class="pop-data alert-pop-data">
						<div class="pop-body">
							<div class="msg-title">`+title+`</div>
							<div class="msg-txt">`+message+`</div>
						</div>
						<div class="pop-footer">
							<button type="button" class="btn btn-confirm btn-cc" onclick="layerPop.close();">취소</button>
							<button type="button" class="btn btn-confirm btn-cf" onclick='layerPop.confirmAction();' >확인</button>
						</div>
					</div>

				</div>
			</div>`;

		return alertLayerTag;
	},

};

$( document ).ready(function() {
	layerPop.init();
});

function ZeroFill (str, cnt) {
	str = '0000000000'+str; 
	return str.substr(str.length-cnt, cnt); 
}
function GetDate () {
	var d = new Date();
	return ''+d.getFullYear()+ZeroFill((d.getMonth()+1),2)+ZeroFill(d.getDate(),2)+ZeroFill(d.getHours(),2)+ZeroFill(d.getMinutes(),2)+ZeroFill(d.getSeconds(),2);
}
function GetPhotoFilename(service_name,user_id,filename) {
	return user_id.trim()+'/'+service_name.trim()+'_'+GetDate()+'.'+filename.split('.').pop();
}
function GetThisYear () {
	var d = new Date();
	return d.getFullYear();
}
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function insertParam(key, value) {
	key = encodeURIComponent(key); value = encodeURIComponent(value);

	var domain = String(window.location.href).split("?");

	var s = document.location.search;
	var kvp = key+"="+value;

	var r = new RegExp("(&|\\?)"+key+"=[^\&]*");

	s = s.replace(r,"$1"+kvp);

	if(!RegExp.$1) {s += (s.length>0 ? '&' : '?') + kvp;};

	history.pushState(null, null, domain[0]+s);
}

function toBase62(n) {
	if (n === 0) {
		return '0';
	}
	var digits = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	var result = '';
	while (n > 0) {
		result = digits[n % digits.length] + result;
		n = parseInt(n / digits.length, 10);
	}

	return result;
}

function fromBase62(s) {
	var digits = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	var result = 0;
	for (var i=0 ; i<s.length ; i++) {
		var p = digits.indexOf(s[i]);
		if (p < 0) {
			return NaN;
		}
		result += p * Math.pow(digits.length, s.length - i - 1);
	}
	return result;
}

Date.prototype.format = function(f) {
    if (!this.valueOf()) return " ";
 
    var weekName = ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"];
    var d = this;
     
    return f.replace(/(yyyy|yy|MM|dd|E|hh|mm|ss|a\/p)/gi, function($1) {
        switch ($1) {
            case "yyyy": return d.getFullYear();
            case "yy": return (d.getFullYear() % 1000).zf(2);
            case "MM": return (d.getMonth() + 1).zf(2);
            case "dd": return d.getDate().zf(2);
            case "E": return weekName[d.getDay()];
            case "HH": return d.getHours().zf(2);
            case "hh": return ((h = d.getHours() % 12) ? h : 12).zf(2);
            case "mm": return d.getMinutes().zf(2);
            case "ss": return d.getSeconds().zf(2);
            case "a/p": return d.getHours() < 12 ? "오전" : "오후";
            default: return $1;
        }
    });
};

String.prototype.string = function(len){var s = '', i = 0; while (i++ < len) { s += this; } return s;};
String.prototype.zf = function(len){return "0".string(len - this.length) + this;};
Number.prototype.zf = function(len){return this.toString().zf(len);};


function deposit_toggle(id){




	$.ajax({

		url:'/data/api.php',
		type:'post',
		data:{

			mode:'get_deposit',
			artist_id:id
		},success:function(res) {
			let response = JSON.parse(res);
			let head = response.data.head;
			let body = response.data.body;
			if (head.code === 401) {
				pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
			} else if (head.code === 200) {
				console.log(body)

				if(body.length >0){
					if(document.getElementById('deposit_form_1').style.display === 'none'){

						document.getElementById('deposit_form_1').style.display = 'block'
					}else{

						document.getElementById('deposit_form_1').style.display = 'none';
					}


					document.getElementById('reserve_deposit_input').value = body[0].reserve_price

					for(let i=0; i<document.getElementById('reserve_deposit_time').options.length; i++){

						if(body[0].deadline == document.getElementById('reserve_deposit_time').options[i].value){

							document.getElementById('reserve_deposit_time').options[i].selected = true;
							document.getElementById('reserve_deposit_time').dispatchEvent(new Event('change'))
						}

					}

					if(document.getElementById('deposit_btn').checked === true){
						document.getElementById('deposit_notice').style.display = 'flex';
					}else{
						document.getElementById('deposit_notice').style.display = 'none';
					}


					document.getElementById('reserve_deposit_input').setAttribute('data-bank',`${body[0].bank_name}`)
					document.getElementById('reserve_deposit_input').setAttribute('data-account',`${body[0].account_num}`)
				}else{

					pop.open('deposit_confirm')

				}



			}
		}

	})



}

function minutes_to_hour(minutes){

	let hours = Math.floor(minutes/60);
	let min = minutes%60;


	return `${hours !== 0 ? hours : ''}${hours !== 0 ? '시간 ':''}${min !== 0 ? min : ''}${min !== 0 ? '분' :''}`

}


var bank_names = [
	{
		code : '003',
		name : '기업은행'
	},{
		code : '004',
		name : '국민은행'
	},{
		code : '011',
		name : '농협중앙회회'
	},{
		code : '012',
		name : '단위농협'
	},{
		code : '020',
		name : '우리은행'
	},{
		code : '031',
		name : '대구은행'
	},{
		code : '005',
		name : '외환은행'
	},{
		code : '023',
		name : 'SC제일은행'
	},{
		code : '032',
		name : '부산은행'
	},{
		code : '045',
		name : '새마을금고'
	},{
		code : '027',
		name : '한국씨티은행'
	},{
		code : '034',
		name : '광주은행'
	},{
		code : '039',
		name : '경남은행'
	},{
		code : '007',
		name : '수협'
	},{
		code : '048',
		name : '신협'
	},{
		code : '037',
		name : '전북은행'
	},{
		code : '035',
		name : '제주은행'
	},{
		code : '064',
		name : '산림조합'
	},{
		code : '071',
		name : '우체국'
	},{
		code : '081',
		name : '하나은행'
	},{
		code : '088',
		name : '신한은행'
	},{
		code : '090',
		name : '카카오뱅크'
	},{
		code : '209',
		name : '동양종금증권'
	},{
		code : '243',
		name : '한국투자증권'
	},{
		code : '240',
		name : '삼성증권'
	},{
		code : '230',
		name : '미래에셋'
	},{
		code : '247',
		name : '우리투자증권'
	},{
		code : '218',
		name : '현대증권'
	},{
		code : '266',
		name : 'SK증권'
	},{
		code : '278',
		name : '신한금융투자'
	},{
		code : '262',
		name : '하이증권'
	},{
		code : '263',
		name : 'HMC증권'
	},{
		code : '267',
		name : '대신증권'
	},{
		code : '270',
		name : '하나대투증권'
	},{
		code : '279',
		name : '동부증권'
	},{
		code : '280',
		name : '유진증권'
	},{
		code : '287',
		name : '메리츠증권'
	},{
		code : '291',
		name : '신영증권'
	},{
		code : '238',
		name : '대우증권'
	}

]


function deposit_save(id){


	if(parseInt(document.getElementById('deposit_input').value) < 1000){

		document.getElementById('msg1_txt').innerText = '최소 예약금은 1,000원 입니다.';
		pop.open('reserveAcceptMsg1');
		return;
	}

	$.ajax({

		url:'/data/api.php',
		type:'post',
		data:{

			mode:'deposit_save',
			artist_id:id,
			reserve_price:document.getElementById('deposit_input').value,
			deadline:document.getElementById('deposit_time').value,
			bank_name:document.getElementById('deposit_bank').value,
			account_num:document.getElementById('deposit_bank_account').value
		},success:function(res) {
			console.log(res)
			let response = JSON.parse(res);
			let head = response.data.head;
			let body = response.data.body;
			if (head.code === 401) {
				pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
			} else if (head.code === 200) {


				pop.close('deposit_set')
			}


		}

	})


}

function get_deposit(id){


	$.ajax({

		url:'/data/api.php',
		type:'post',
		data:{

			mode:'shop_reserve_get',
			login_id:id,
		},success:function(res) {
			console.log(res)
			let response = JSON.parse(res);
			let head = response.data.head;
			let body = response.data.body;
			if (head.code === 401) {
				pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
			} else if (head.code === 200) {
				console.log(body)


				document.getElementById('deposit_input').value = body[0].reserve_price;


				for(let i=0; i<document.getElementById('deposit_time').options.length; i++){

					if(body[0].deadline == document.getElementById('deposit_time').options[i].value){

						document.getElementById('deposit_time').options[i].selected = true;

					}
				}

				for(let i=0; i<document.getElementById('deposit_bank').options.length; i++){

					if(body[0].bank_name == document.getElementById('deposit_bank').options[i].value){

						document.getElementById('deposit_bank').options[i].selected = true;

					}
				}

				document.getElementById('deposit_bank_account').value = body[0].account_num;
			}


		}

	})

}


function fill_zero(time){

	if(time.toString().length < 2){

		time = `0${time}`
	}else{
		time = time;
	}

	return time;
}

function deposit_finish(target){

	let payment_idx = target.getAttribute('data-payment_idx');

	$.ajax({

		url:'/data/api.php',
		type:'post',
		data:{
			mode:'deposit_finish',
			payment_log_seq:payment_idx,
		},
		success:function(res) {
			let response = JSON.parse(res);
			let head = response.data.head;
			let body = response.data.body;
			if (head.code === 401) {
				pop.open('firstRequestMsg1', '잠시 후 다시 시도 해주세요.');
			} else if (head.code === 200) {
				console.log(body)

				target.checked = true;
				let date_ = new Date();
				document.getElementById('pay_deposit_title').innerText = '예약금 입금완료';
				document.getElementById('pay_deposit_title').classList.add('actived');
				document.getElementById('pay_deposit_date').innerText = `(입금처리 : ${date_.getFullYear()}. ${fill_zero(date_.getMonth()+1)}. ${fill_zero(date_.getDate())}. ${am_pm_check(date_.getHours())}시 ${fill_zero(date_.getMinutes())}분)`

			}
		}
	})
}
function am_pm_check(hours){

	if(hours > 12){
		hours = `오후 ${(hours-12).toString().length <2 ? '0' : ''}${hours-12}`
	}else if(hours === 12){
		hours = `오후 ${hours}`
	}else{
		hours = `오전 ${hours}`
	}

	return hours;
}