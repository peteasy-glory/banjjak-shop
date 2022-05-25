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