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
            <button type="button" class="btn-float-menu new-item" onclick="popalert.open('setting_main')">예약관리 화면설정</button>
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
<script>

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
</script>