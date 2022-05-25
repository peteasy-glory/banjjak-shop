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
            <!--<button type="button" onclick="location.href='reserve_time_sale_step1_3'" class="btn-float-menu">빈시간 판매하기</button>-->
        </div>
    </div>
</div>

<article id="approveOnly" class="layer-pop-wrap">
    <div class="layer-pop-parent">
        <div class="layer-pop-children">
            <div class="pop-data alert-pop-data">
                <div class="pop-body type-3">
                    <div class="msg-txt">승인대기중인 예약 <span id="a_cnt">0</span>건 </div>
                    <div class="msg-txt">대기리스트를 확인해주세요.</div>
                </div>
                <div class="pop-footer">
                    <button type="button" class="btn btn-confirm btn-reserv-block" onclick="popalert.close();">닫기</button>
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
                    <button type="button" class="btn btn-confirm btn-reserv-block" onclick="popalert.close();">닫기</button>
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

                <div class="pop-body type-3">
                    <div class="msg-txt">승인대기중인 예약 <span id="a1_cnt">0</span>건 </div>
                    <div class="msg-txt">상담대기중인 예약 <span id="c1_cnt">0</span>건 </div>
                    <div class="msg-txt">대기리스트를 확인해주세요.</div>
                </div>
                <div class="pop-footer">
                    <button type="button" class="btn btn-confirm btn-reserv-block" onclick="popalert.close();">닫기</button>
                    <button type="button" class="btn btn-confirm btn-reserv-send" onclick="location.href='reserve_advice_list_1';">상담신청 이동</button>
                    <button type="button" class="btn btn-confirm btn-reserv-send" onclick="location.href='reserve_waiting';">승인대기 이동</button>
                </div>
            </div>
        </div>
    </div>
</article>
