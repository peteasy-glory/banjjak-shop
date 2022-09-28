<style>
    .ui-dialog { padding: 0px;  z-index: 5000 !important;}
    .ui-dialog .ui-dialog-content { padding: 0px;  }
    .ui-dialog-titlebar { display: none; }
    .ui-widget-overlay { background-color: rgba(0, 0, 0, 0.8); opacity: 1; }
    .ui-dialog-buttonset { width: 100%; }
    .ui-dialog .ui-dialog-buttonpane { padding: 0px !important; margin-top: 0px; }
    .ui-widget.ui-widget-content { border: 0px; }
    .ui-dialog .ui-dialog-buttonpane button { display: inline-block; width: 50%; height: 50px; padding: 0px; margin: 0px; border: 0px; background-color: #222; padding: 0px !important; margin: 0px !important; border-radius: 0px; border: 0px; font-size: 14px; color:white}

    #popup_wrap { display: none; }
    #popup_wrap .custom-modal-content { margin: 0px auto; width: 100%; }
    #popup_wrap .swiper-container_front { margin: 0px auto; overflow: hidden; position: relative; z-index: 1; }
    #popup_wrap .swiper-container_front .next { position: absolute; left: 10px; top: 50%; color: #000; font-size: 24px; z-index: 1; outline: none; display: block !important; }
    #popup_wrap .swiper-container_front .next.swiper-button-disabled { color: rgba(0,0,0,0); }
    #popup_wrap .swiper-container_front .prev { position: absolute; right: 10px; top: 50%; color: #000; font-size: 24px; z-index: 1; outline: none; display: block !important }
    #popup_wrap .swiper-container_front .prev.swiper-button-disabled { color: rgba(0,0,0,0); }
    #popup_wrap .swiper-wrapper { height: auto; }
    #popup_wrap .swiper-slide {  }
    #popup_wrap .swiper-slide img { width: 100%; height: auto; vertical-align: top; }
    #popup_wrap .swiper-pagination_front { position: absolute; left: 0px; bottom: 0px; width: 100%; text-align: center; margin-bottom: 20px; z-index: 1; }
    #popup_wrap .swiper-pagination-bullet { margin: 0px 5px; }
    #popup_wrap .swiper-pagination-bullet-active { background-color: #999; }
</style>




<div class="reserve-calendar-float">
    <div class="reserve-calendar-float-cell">
        <button onclick="location.href='reserve_main_day';" type="button" class="btn-reserve-calendar-today"><span
                    class="icon icon-circle-float-today"></span></button>
    </div>
    <div class="reserve-calendar-float-cell">
        <button type="button" class="btn-reserve-calendar-menu btn-reserve-calendar-toggle"><span
                    class="icon icon-circle-float-menu"></span><em><?= $wait_count+$await_cnt ?></em></button>
        <div class="reserve-calendar-float-menu">
            <button type="button" class="btn-float-menu" onclick="location.href='customer_inquiry';">고객조회</button>
            <button type="button" class="btn-float-menu" onclick="location.href='customer_pet_new';">신규등록</button>
            <button type="button" class="btn-float-menu" onclick="location.href='reserve_advice_list_1';">상담 승인 대기 : <?= $wait_count ?></button>
            <button type="button" class="btn-float-menu" onclick="location.href='reserve_waiting';">예약 승인 대기 : <?php echo $await_cnt; ?></button>
            <button type="button" class="btn-float-menu new-item" onclick="popalert.open('setting_main')">예약관리 화면설정<img src="images/new_item2.png" alt="" style="margin-bottom:13px; margin-left:3px;max-width:5px !important;"></button>

        </div>
    </div>
</div>


<article id="setting_main" class="layer-pop-wrap" style="z-index: 100002;">
    <div class="layer-pop-parent">
        <div class="layer-pop-children">

            <div class="pop-data alert-pop-data">
                <div class="pop-body">
                    <div class="msg-txt">예약관리 첫 화면 설정</div>
                    <div class="radio-btn-wrap">
                        <br>
                        <div><input type="radio" class="radio-btn-vertical" name="reserve_main" value="0" <?php echo ($reserve_main == 0)? "checked":"" ?>><label for="reserve_main" class="radio-btn-label" >월</label></div>
                        <div><input type="radio" class="radio-btn-vertical" name="reserve_main" value="1" <?php echo ($reserve_main == 1)? "checked":"" ?>><label for="reserve_main" class="radio-btn-label" >주</label></div>
                        <div><input type="radio" class="radio-btn-vertical" name="reserve_main" value="2" <?php echo ($reserve_main == 2)? "checked":"" ?>><label for="reserve_main" class="radio-btn-label">일</label></div>
                    </div>
                </div>
                <div class="pop-footer">
                    <button type="button" class="btn btn-confirm" onclick="popalert.close();">취소</button>
                    <button type="button" class="btn btn-confirm" onclick=" set_reserve_main();">변경</button>
                </div>
            </div>

        </div>
    </div>
</article>

<!-- 설정 완료 -->
<article id="setting_main_ok" class="layer-pop-wrap">
    <div class="layer-pop-parent">
        <div class="layer-pop-children">
            <div class="pop-data alert-pop-data">
                <div class="pop-body">
                    <div class="msg-txt">변경되었습니다.</div>
                </div>
                <div class="pop-footer">
                    <button type="button" class="btn btn-confirm" onclick="popalert.close();">확인</button>
                </div>
            </div>
        </div>
    </div>
</article>

<article id="approveOnly" class="layer-pop-wrap">
    <div class="layer-pop-parent">
        <div class="layer-pop-children">
            <div class="pop-data alert-pop-data">
                <div class="pop-body">
                    <div class="msg-txt">승인대기중인 예약 <span id="a_cnt">0</span>건 </div>
                    <div class="msg-txt">대기리스트를 확인해주세요.</div>
                </div>
                <div class="pop-footer">
                    <button type="button" class="btn btn-confirm btn-reserv-block" id="close-approve-only" onclick="popalert.close();">닫기</button>
                    <button type="button" class="btn btn-confirm btn-reserv-send" onclick="location.href='reserve_waiting';">지금확인</button> 
                </div>
            </div>
        </div>
    </div>
</article>

<article id="counselorOnly" class="layer-pop-wrap">
    <div class="layer-pop-parent">
        <div class="layer-pop-children">
            <div class="pop-data alert-pop-data">
                <div class="msg-txt">상담대기중인 예약 <span id="c_cnt">0</span>건 </div>
                <div class="msg-txt">대기리스트를 확인해주세요.</div>
                <div class="pop-footer">
                    <button type="button" class="btn btn-confirm btn-reserv-block"  id="close-counselor-only" onclick="popalert.close();">닫기</button>
                    <button type="button" class="btn btn-confirm btn-reserv-send" onclick="location.href='reserve_advice_list_1';">지금확인</button>
                </div>
            </div>
        </div>
    </div>
</article>


<article id="bothMsg" class="layer-pop-wrap">
    <div class="layer-pop-parent">
        <div class="layer-pop-children">
            <div class="pop-data alert-pop-data">

                <div class="pop-body">
                    <div class="msg-txt">승인대기중인 예약 <span id="a1_cnt">0</span>건 </div>
                    <div class="msg-txt">상담대기중인 예약 <span id="c1_cnt">0</span>건 </div>
                    <div class="msg-txt">대기리스트를 확인해주세요.</div>
                </div>
                <div class="pop-footer">
                    <button type="button" class="btn btn-confirm btn-reserv-block" id="close-btn-msg" onclick="popalert.close();">닫기</button>
                    <button type="button" class="btn btn-confirm btn-reserv-send" onclick="location.href='reserve_advice_list_1';">상담신청 이동</button>
                    <button type="button" class="btn btn-confirm btn-reserv-send" onclick="location.href='reserve_waiting';">승인대기 이동</button>
                </div>
            </div>
        </div>
    </div>
</article>

<div id="popup_wrap">
    <div class="custom-modal-content">
        <div class="popup_img">
            <div class="swiper-container_front swiper-container">
                <div class="swiper-wrapper" id="popup-wraper">
                    <div class="swiper-slide">
                        <a href="javascript:location.href='mypage_notice_view?notice_seq=24';">
                            <img src="/images/banner/reserve_main.jpg" />
                        </a>
                    </div>
                    <div class="swiper-slide">
                        <a href="#" onclick="event.preventDefault();" style="cursor:default">
                            <img src="https://image.banjjakpet.com/images/reservation_drag.jpg" alt="">
                        </a>

                    </div>
                </div>
                <!-- Add Arrows -->
                <div class="next"><i class="fa-solid fa-chevron-left"></i></div>
                <div class="prev"><i class="fa-solid fa-chevron-right"></i></div>
                <div class="swiper-pagination_front"></div>
            </div>
        </div>
    </div>
</div>

<div id="popup_wrap2">
    <div class="custom-modal-content">
        <div class="popup_img">
            <div class="swiper-container_front swiper-container">
                <div class="swiper-wrapper" id="popup-wraper2">
                    <div class="swiper-slide">
                        <a href="https://partner-pc.banjjakpet.com/?partner_pc=<?=$artist_id?>" target="_blank">
                            <img src="https://image.banjjakpet.com/images/partnerpc_pop.jpg" alt="" />
                        </a>
                    </div>
                    <!--                            <div class="swiper-slide">-->
                    <!--                                <a href="javascript:location.href='mypage_notice_view?notice_seq=';">-->
                    <!--                                    <img src="/images/banner/pop_recommendation_event.jpg" alt="" />-->
                    <!--                                </a>-->
                    <!--                            </div>-->

                </div>
                <!-- Add Arrows -->
                <div class="next"><i class="fa-solid fa-chevron-left"></i></div>
                <div class="prev"><i class="fa-solid fa-chevron-right"></i></div>
                <div class="swiper-pagination_front"></div>
            </div>
        </div>
    </div>
</div>

<script>
    let mql = window.matchMedia("screen and (min-width: 1025px)");

    function set_reserve_main(){
        var change_reserve_main = $('input[name=reserve_main]:checked').val();
        console.log(change_reserve_main);

        $.ajax({
            type: 'post',
            url: '/data/data_set_reserve_main.php',
            data: {
                customer_id : "<?=$artist_id?>",
                reserve_main : change_reserve_main
            },
            dataType: 'json',
            error: function(xhr,error) {
                console.log(xhr+ '' +error)
            },
            success: function(json) {
                console.log(json);
            }
        });
        popalert.close();
        popalert.open('setting_main_ok');
        $('.reserve-calendar-float-menu, .page-cover').removeClass('actived')
    }


    function getCookie_popup(name) {
        var obj = name + "=";
        var x = 0;
        while (x <= document.cookie.length) {
            var y = (x + obj.length);
            if (document.cookie.substring(x, y) == obj) {
                if ((endOfCookie = document.cookie.indexOf(";", y)) == -1)
                    endOfCookie = document.cookie.length;
                return unescape(document.cookie.substring(y, endOfCookie));
            }
            x = document.cookie.indexOf(" ", x) + 1;
            if (x == 0)
                break;
        }
        return "";
    }
    function setCookie_popup(name, value, expiredays) {
        var todayDate = new Date();
        todayDate.setDate(todayDate.getDate() + expiredays);
        document.cookie = name + '=' + escape(value) + '; path=/; expires=' + todayDate.toGMTString() + '; SameSite=None; Secure';
    }

    $(document).ready(function(){
        if(!mql.matches){
            if (getCookie_popup('guide_beauty_shop_reserve') != 'Y') {
                $("#popup_wrap").dialog({
                    modal: true,
                    title: "",
                    autoOpen: true,
                    //maxWidth: "96%",
                    //minHeight: Number($(window).height()) - 40,
                    //width: 'auto',
                    //height: 'auto',
                    autoSize: false,
                    resize: 'auto',
                    resizable: false,
                    draggable: false,
                    buttons: {
                        '닫기': function() {
                            // setCookie_popup('guide_beauty_shop ', 'Y', 1);
                            $(this).dialog("close");
                        },
                        "다시 보지 않기": function() {
                            // location.href = "mypage_notice_view?notice_seq=19";
                            setCookie_popup('guide_beauty_shop_reserve', 'Y', 100);
                            $(this).dialog("close");
                        }
                    },
                    open: function(event, ui) {
                        // swiper2.update();
                        $(event.target).parent().css('position', 'fixed'); // dialog fixed
                        $(event.target).parent().css('top', '50%'); // dialog fixed
                        $(event.target).parent().css('left', '50%'); // dialog fixed
                        $(event.target).parent().css('transform', 'translate(-50%, -50%)'); // dialog fixed
                        // $('.ui-dialog').position({ my: "center", at: "center", of: window });
                    },
                    close: function() {
                    }
                });
            }
        }else if(mql.matches){
            if (getCookie_popup('guide_beauty_shop_pc') != 'Y') {
                $("#popup_wrap2").dialog({
                    modal: true,
                    title: "",
                    autoOpen: true,
                    //maxWidth: "96%",
                    //minHeight: Number($(window).height()) - 40,
                    //width: 'auto',
                    //height: 'auto',
                    autoSize: false,
                    resize: 'auto',
                    resizable: false,
                    draggable: false,
                    buttons: {
                        '닫기': function() {
                            // setCookie_popup('guide_beauty_shop ', 'Y', 1);
                            $(this).dialog("close");
                        },
                        "오늘 그만보기": function() {
                            // location.href = "mypage_notice_view?notice_seq=19";
                            setCookie_popup('guide_beauty_shop_pc', 'Y', 1);
                            $(this).dialog("close");
                        }
                    },
                    open: function(event, ui) {
                        // swiper2.update();
                        $(event.target).parent().css('position', 'fixed'); // dialog fixed
                        $(event.target).parent().css('top', '50%'); // dialog fixed
                        $(event.target).parent().css('left', '50%'); // dialog fixed
                        $(event.target).parent().css('transform', 'translate(-50%, -50%)'); // dialog fixed
                        // $('.ui-dialog').position({ my: "center", at: "center", of: window });
                    },
                    close: function() {
                    }
                });
            }

        }

    });

    let slide_length = document.querySelector("#popup-wraper").children.length-1;

    let random = Math.floor(Math.random()*10);

    while(random > slide_length){

        random = Math.floor(Math.random()*10);

    }


    let popup_swiper_reserve = new Swiper('.swiper-container_front', {

        direction:'horizontal',
        slidesPerView:1,

        observer:true,
        observeParents:true,

        navigation:{
            nextEl:".prev",
            prevEl:".next",
        },
        watchOverflow:true,
        initialSlide:random


    });

</script>