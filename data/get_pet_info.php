<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

$clear = array();
if(ctype_alnum($_POST['mode'])){
    $clear['mode'] = $_POST['mode'];
}
$shop_id = $_SESSION['gobeauty_user_id'];
switch($clear['mode']){
    case 'petInfo'://펫 정보 불러오기

        if(ctype_digit($_POST['no'])){
            $clear['no'] = $_POST['no'];
        }
        $json['flag'] = true;
        $que = "SELECT * FROM tb_mypet WHERE pet_seq = {$clear['no']}";
//        echo $que;
        $res = sql_query($que);
        $row = sql_fetch($res);


        $cm = '';
        $sql = "
                                            SELECT *
                                            FROM tb_payment_log 
                                            WHERE artist_id = '" . $shop_id . "' 
                                                AND is_no_show = 0 
                                                AND is_cancel = 0 
                                                AND is_cancel = 0
                                                AND approval = 1
                                                AND etc_memo = ''
                                                AND pet_seq = '{$clear['no']}'
                                                
                                        ";
        //echo $sql;
        $arr = sql_fetch_array($sql);
        $total_cnt = count($arr);
        if(count($arr)>0) {
            foreach ($arr as $rs) {
                $date = $rs['year'].'-'.$rs['month'].'-'.$rs['day'];
                $prev_beauty = explode("|",$rs['product']);
                $kg = explode(":",$prev_beauty[5]);
                $cm .='<div class="con-title-group">';
                $cm .='    <h4 class="con-title">이전 특이사항</h4>';
                $cm .='</div>';
                $cm .='<div class="special-note">';
                $cm .='    <div class="note-desc"><em>'.date('Y.m.d',strtotime($date)).'</em><div class="txt">'.$rs['etc_memo'].'</div></div>';
                $cm .='</div>';
                $cm .='<div class="basic-data-group vmiddle">';
                if($total_cnt>5) {
                    $cm .= '    <button type="button" class="btn btn-middle-size btn-round btn-outline-purple">더보기</button>';
                }
                $cm .='</div>';
            }
        }

        $bt = '';

        $sql = "
                                            SELECT *
                                            FROM tb_payment_log 
                                            WHERE artist_id = '" . $shop_id . "' 
                                                AND is_no_show = 0 
                                                AND is_cancel = 0 
                                                AND is_cancel = 0
                                                AND approval = 1
                                                AND pet_seq = '{$clear['no']}'
                                                AND CONCAT(year,'-',month,'-',day) < DATE_FORMAT(NOW(),'%Y-%c-%e')
                                                                                            
                                        ";
        //echo $sql;
        $arr = sql_fetch_array($sql);
        $total_cnt1 = count($arr);
        if($total_cnt1>0) {
            $bt .='<div class="con-title-group">';
            $bt .='    <h4 class="con-title">이전 미용</h4>';
            $bt .='</div>';
            $bt .='<div class="memo-item-list">';
            foreach ($arr as $rs) {
                $date = $rs['year'].'-'.$rs['month'].'-'.$rs['day'];
                $prev_beauty = explode("|",$rs['product']);
                $kg = explode(":",$prev_beauty[5]);

                $bt .='<div class="memo-item">'.date('Y.m.d',strtotime($date)).' / '.$row["name"].' / '.$prev_beauty[4].' / ~'.$kg[0].'kg / '.number_format($kg[1]).'원<div class="memo-link"><a href="#" class="btn-memo-link">상세보기<div class="icon icon-arrow-right-small"></div></a></div></div>';

            }
            $bt .='<div class="basic-data-group vmiddle">';
            if($total_cnt1>5) {
                $bt .= '    <button type="button" class="btn btn-middle-size btn-round btn-outline-purple">더보기</button>';
            }
            $bt .='</div>';
        }
        if(!$row){
            echo json_encode(array('result'=>false,'data'=>''), JSON_UNESCAPED_UNICODE);
        }
        echo json_encode(array('result'=>$json,'data'=>$row,'memo'=>$data,'beauty'=>$bt), JSON_UNESCAPED_UNICODE);
        break;
}

?>
