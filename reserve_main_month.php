<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

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

$yy = (isset($_GET['yy']) && $_GET['yy']) ? $_GET['yy'] : date('Y');
$mm = (isset($_GET['mm']) && $_GET['mm']) ? $_GET['mm'] : date('m');
$dd = (isset($_GET['dd']) && $_GET['dd']) ? $_GET['dd'] : date('d');



$shop_title	= "예약관리";
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header_shop.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

?>

<?

$from = $_GET['from'];

$sql = array();
$data = array();
$wait_count = 0;

$ch = (isset($_GET['ch']) && $_GET['ch']) ? $_GET['ch'] : 'month';
$yy = (isset($_GET['yy']) && $_GET['yy']) ? $_GET['yy'] : date('Y');
$mm = (isset($_GET['mm']) && $_GET['mm']) ? $_GET['mm'] : date('m');
$dd = (isset($_GET['dd']) && $_GET['dd']) ? $_GET['dd'] : date('d');
$week = $_GET['week'];

$backurl = $_GET['backurl'];

$daily = array('일', '월', '화', '수', '목', '금', '토');
$selected_date = $yy . "-" . $mm . "-" . $dd;

$y_yy = date("Y", strtotime($selected_date . "-1day"));
$y_mm = date("m", strtotime($selected_date . "-1day"));
$y_dd = date("d", strtotime($selected_date . "-1day"));
$t_yy = date("Y", strtotime($selected_date . "+1day"));
$t_mm = date("m", strtotime($selected_date . "+1day"));
$t_dd = date("d", strtotime($selected_date . "+1day"));

// 오늘 날짜
$today_year = date("Y");
$today_month = date("m");
$today_day = date("d");

// var_dump($_SESSION);
$artist_flag = $_SESSION['artist_flag'];

if ($artist_flag === true) {
    $artist_id = $_SESSION['shop_user_id'];
    $user_id = $_SESSION['shop_user_id'];
    $user_name = $_SESSION['shop_user_nickname'];
} else {
    $artist_id = $_SESSION['artist_id'];
    $user_id = $_SESSION['gobeauty_user_id'];
    $user_name = $_SESSION['gobeauty_user_nickname'];
}

$shop_sql = "select * from tb_shop where customer_id = '" . $user_id . "'";
$shop_result = mysqli_query($connection, $shop_sql);
if ($shop_datas = mysqli_fetch_object($shop_result)) {
    $front_image = $shop_datas->front_image;
    $name = $shop_datas->name;
    $working_years = $shop_datas->working_years;
    $self_introduction = $shop_datas->self_introduction;
    $professional_field = $shop_datas->professional_field;
    $career = $shop_datas->career;
    $license_indexs = $shop_datas->license_indexs;
    $region_and_cost = $shop_datas->region_and_cost;
    $enable_flag = $shop_datas->enable_flag;
    $update_time = $shop_datas->update_time;

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

    //크기 데이터,  상품 데이터
    $dog_static_query = "SELECT *, second_type AS product_type FROM tb_product_dog_static WHERE customer_id = '{$user_id}'";
    $dog_static_result = mysqli_query($connection, $dog_static_query);
    $dog_static_list = array();
	$dog_static_list2 = array(); // worktime
    $dog_service_list = array();
    while ($dog_static_data = mysqli_fetch_object($dog_static_result)) {
        $product_type = $dog_static_data->product_type;
        $dog_static_list[] = $product_type;

        $sanitation = $dog_static_data->sanitation_price;
        $bath = $dog_static_data->bath_price;
        $part = $dog_static_data->part_price;
        $bath_part = $dog_static_data->bath_part_price;
        $all = $dog_static_data->all_price;
        $spoting = $dog_static_data->spoting_price;
        $summercut = $dog_static_data->summercut_price;
        $scissors = $dog_static_data->scissors_price;

        if ($bath != null && $bath != "") {
            $dog_service_list[$product_type]["목욕"] = "목욕";
			$dog_static_list2[1] = "목욕";
        }

        if ($part != null && $part != "") {
            $dog_service_list[$product_type]["부분미용"] = "부분미용";
			$dog_static_list2[2] = "부분미용";
        }

        if ($bath_part != null && $bath_part != "") {
            $dog_service_list[$product_type]["부분+목욕"] = "부분+목욕";
			$dog_static_list2[3] = "부분+목욕";
        }

        if ($all != null && $all != "") {
            $dog_service_list[$product_type]["전체미용"] = "전체미용";
			$dog_static_list2[4] = "전체미용";
        }

        if ($spoting != null && $spoting != "") {
            $dog_service_list[$product_type]["스포팅"] = "스포팅";
			$dog_static_list2[5] = "스포팅";
        }

        if ($scissors != null && $scissors != "") {
            $dog_service_list[$product_type]["가위컷"] = "가위컷";
			$dog_static_list2[6] = "가위컷";
        }

		if ($sanitation != null && $sanitation != "") {
            $dog_service_list[$product_type]["위생"] = "위생";
			$dog_static_list2[7] = "위생";
        }

        if ($summercut != null && $summercut != "") {
            $dog_service_list[$product_type]["썸머컷"] = "썸머컷";
			$dog_static_list2[8] = "썸머컷";
        }

        // if (($sanitation != null && $sanitation != "") && ($summercut != null && $summercut != "")) {
        //     $dog_service_list[$product_type] = ["위생" => "위생", "목욕" => "목욕", "부분미용" => "부분미용", "부분+목욕" => "부분+목욕", "전체미용" => "전체미용", "스포팅" => "스포팅", "썸머컷" => "썸머컷", "가위컷" => "가위컷"];
        // } else if (($sanitation != null && $sanitation != "") && ($summercut == null || $summercut == "")) {
        //     $dog_service_list[$product_type] = ["위생" => "위생", "목욕" => "목욕", "부분미용" => "부분미용", "부분+목욕" => "부분+목욕", "전체미용" => "전체미용", "스포팅" => "스포팅", "가위컷" => "가위컷"];
        // } else if (($sanitation == null || $sanitation == "") && ($summercut != null && $summercut != "")) {
        //     $dog_service_list[$product_type] = ["목욕" => "목욕", "부분미용" => "부분미용", "부분+목욕" => "부분+목욕", "전체미용" => "전체미용", "스포팅" => "스포팅", "썸머컷" => "썸머컷", "가위컷" => "가위컷"];
        // } else {
        //     $dog_service_list[$product_type] = ["목욕" => "목욕", "부분미용" => "부분미용", "부분+목욕" => "부분+목욕", "전체미용" => "전체미용", "스포팅" => "스포팅", "가위컷" => "가위컷"];
        // }
    }

	$dog_worktime_query = "SELECT * FROM tb_product_dog_worktime WHERE artist_id = '".$user_id."' AND is_delete = '2' ";
	$dog_worktime_result = mysqli_query($connection, $dog_worktime_query);
	$dog_worktime_cnt = mysqli_num_rows($dog_worktime_result);
	$dog_worktime_list = array(); 
	$dog_worktime_txt = array("dog","목욕","부분미용","부분+목욕","전체미용","스포팅","가위컷","위생","썸머컷");
	// 순서대로 목욕,부분미용,부분+목욕,전체미용,스포팅,가위컷,위생,썸머컷
	if($dog_worktime_cnt > 0){
		while($dog_worktime_data = mysqli_fetch_assoc($dog_worktime_result)){
			for($_i = 1; $_i <= 8; $_i++){
				foreach($dog_static_list2 AS $key => $value){
					if($dog_worktime_txt[$_i] == $value){
						$dog_worktime_list[$value] = $dog_worktime_data["worktime".$_i];
					}
				}
			}
		}
	}else{
		$dog_worktime_list = array(
			"dog" => "dog",
			"목욕" => "120",
			"부분미용" => "120",
			"부분+목욕" => "120",
			"전체미용" => "120",
			"스포팅" => "120",
			"가위컷" => "120",
			"위생" => "120",
			"썸머컷" => "120"
		);
	}
	//print_r($dog_worktime_list);
    

    $cat_static_query = "SELECT * FROM tb_product_cat WHERE customer_id = '{$user_id}'";
    $cat_static_result = mysqli_query($connection, $cat_static_query);
    $cat_static_list = array();
    while ($cat_static_data = mysqli_fetch_object($cat_static_result)) {
        $cat_static_list[] = $cat_static_data->second_type;
        $cat_product = $cat_static_data;
    }

    //기타 상품 데이터
    $dog_etc_list = array();
    $dog_etc_query = "SELECT * FROM tb_product_dog_etc WHERE customer_id = '{$user_id}'";
    $dog_etc_result = mysqli_query($connection, $dog_etc_query);
    while ($dog_etc = mysqli_fetch_object($dog_etc_result)) {
        $dog_etc_list[] = $dog_etc;
    }

    $cat_etc_list = array();
    $cat_etc_query = "SELECT * FROM tb_product_cat_etc WHERE customer_id = '{$user_id}'";
    $cat_etc_result = mysqli_query($connection, $cat_etc_query);
    while ($cat_etc = mysqli_fetch_object($cat_etc_result)) {
        $cat_etc_list[] = $cat_etc;
    }

    //쿠폰 상품 데이터
    $coupon_list = array();
    $coupon_query = "SELECT * FROM tb_coupon WHERE customer_id = '{$user_id}' AND del_yn = 'N' ORDER BY reg_date";
    $coupon_result = mysqli_query($connection, $coupon_query);
    while ($coupon_data = mysqli_fetch_object($coupon_result)) {
        $coupon_list[] = $coupon_data;
    }
}






















$_SESSION['bjj_stop_direct_flag'] = "1";
$go_year = $yy;
$go_month = $mm;
if ($go_month == 1) {
    $go_year = $go_year - 1;
    $go_month = 12;
} else {
    $go_month = $go_month - 1;
}
$go2_year = $yy;
$go2_month = $mm;
if ($go2_month == 12) {
    $go2_year = $go2_year + 1;
    $go2_month = 1;
} else {
    $go2_month = $go2_month + 1;
}

function sel_yy($yy, $func)
{
    if ($yy == '') $yy = date('Y');
    if ($func == '') {
        $str = "<select name='yy'>\n<option value=''></option>\n";
    } else {
        $str = "<select name='yy' onChange='$func'>\n<option value=''></option>\n";
    }
    $gijun = date('Y');
    for ($i = $gijun; $i < $gijun + 4; $i++) {
        if ($yy == $i) $str .= "<option value='$i' selected>$i</option>";
        else $str .= "<option value='$i'>$i</option>";
    }
    $str .= "</select>";

    return $str;
}

function sel_mm($mm, $func)
{
    if ($func == '') {
        $str = "<select name='mm'>\n";
    } else {
        $str = "<select name='mm' onChange='$func'>\n";
    }

    for ($i = 1; $i < 13; $i++) {
        if ($mm == $i) $str .= "<option value='$i' selected>{$i}월</option>";
        else $str .= "<option value='$i'>{$i}월</option>";
    }

    $str .= "</select>";

    return $str;
}

function get_schedule($yy, $mm, $dd)
{
    global $connection;
    $mm = str_pad($mm, 2, "0", STR_PAD_LEFT);
    $dd = str_pad($dd, 2, "0", STR_PAD_LEFT);
    $dtstr = $yy . "-" . $mm . "-" . $dd;
    $sql = "SELECT * FROM schedule WHERE frdt <= '$dtstr' AND todt >= '$dtstr' ORDER BY frdt, todt";

    $ret = mysqli_query($connection, $sql);
    while ($row = mysqli_fetch_assoc($ret)) {
        print_r($ret);
        $str .= "<font style='font-size:8pt;'>- $row[name]</font><br>";
    }
    return $str;
}

// 1. 총일수 구하기
$last_day = date("t", strtotime($yy . "-" . $mm . "-01"));

// 2. 시작요일 구하기
$start_week = date("w", strtotime($yy . "-" . $mm . "-01"));

// 3. 총 몇 주인지 구하기
$total_week = ceil(($last_day + $start_week) / 7);

// 4. 마지막 요일 구하기
$last_week = date('w', strtotime($yy . "-" . $mm . "-" . $last_day));

// 법정 공휴일 쉬는 여부 1이 쉰다.
$is_rest_public_holiday = 1;
$working_sql = "select * from tb_working_schedule where customer_id = '" . $user_id . "';";
$working_result = mysqli_query($connection, $working_sql);
if ($working_datas = mysqli_fetch_object($working_result)) {
    $is_rest_public_holiday = $worki추ng_datas->rest_public_holiday;
}

// 개인 정기 휴일 설정 1이 쉰다.
$is_monday = 0;
$is_tuesday = 0;
$is_wednesday = 0;
$is_thursday = 0;
$is_friday = 0;
$is_saturday = 0;
$is_sunday = 0;
$get_regular_sql = "select * from tb_regular_holiday where customer_id = '" . $user_id . "';";
$get_regular_result = mysqli_query($connection, $get_regular_sql);
if ($get_regular_datas = mysqli_fetch_object($get_regular_result)) {
    $is_monday = $get_regular_datas->is_monday;
    $is_tuesday = $get_regular_datas->is_tuesday;
    $is_wednesday = $get_regular_datas->is_wednesday;
    $is_thursday = $get_regular_datas->is_thursday;
    $is_friday = $get_regular_datas->is_friday;
    $is_saturday = $get_regular_datas->is_saturday;
    $is_sunday = $get_regular_datas->is_sunday;
}

// 2019.01.07 developlay - 요일별 휴일여부 추출 [START]
$holiweek_arr = array();
$sql_regular = "select * from tb_regular_holiday where customer_id = '" . $user_id . "';";
$sql_regular_result = mysqli_query($connection, $sql_regular);
if ($sql_regular_row = mysqli_fetch_object($sql_regular_result)) {
    $holiweek_arr[0] = $sql_regular_row->is_sunday;
    $holiweek_arr[1] = $sql_regular_row->is_monday;
    $holiweek_arr[2] = $sql_regular_row->is_tuesday;
    $holiweek_arr[3] = $sql_regular_row->is_wednesday;
    $holiweek_arr[4] = $sql_regular_row->is_thursday;
    $holiweek_arr[5] = $sql_regular_row->is_friday;
    $holiweek_arr[6] = $sql_regular_row->is_saturday;
}
// 2019.01.07 developlay - 요일별 휴일여부 추출 [END]

// 2019.01.07 developlay - 휴일일 추출 [START]
$holiday_arr = array();
$sql_public_holiday = "select * from tb_public_holiday where year = " . $yy . " and month = " . $mm . ";";
$sql_public_holiday_result = mysqli_query($connection, $sql_public_holiday);
while ($sql_public_holiday_row = mysqli_fetch_object($sql_public_holiday_result)) {
    array_push($holiday_arr, $sql_public_holiday_row->day);
}
// 2019.01.07 developlay - 휴일일 추출 [END]

$holiday_array = array(0);
$select_public_holiday = "select * from tb_public_holiday where year = " . $yy . " and month = " . $mm . ";";
$select_result = mysqli_query($connection, $select_public_holiday);
while ($select_datas = mysqli_fetch_object($select_result)) {
    $a_day = $select_datas->day;
    array_push($holiday_array, $a_day);
}

$artist_query = "SELECT GROUP_CONCAT(DISTINCT name ORDER BY name ASC) as worker FROM tb_artist_list WHERE artist_id = '{$artist_id}' AND is_view = '2' GROUP BY artist_id";
$artist_result = mysqli_query($connection, $artist_query);
$artist_data = mysqli_fetch_object($artist_result);

$private_all_holiday_array = array();
$private_notall_holiday_array = array();
$ph_sql = "SELECT *, GROUP_CONCAT(A.worker ORDER BY A.worker ASC) AS worker 
		FROM(
			SELECT 
				*,
				DATE_FORMAT(CONCAT(start_year,'-',start_month,'-',start_day,' ',start_hour,':',IFNULL('00',start_minute),':00'),'%Y-%m-%d %H:%i:%s') as start_date, 
        		DATE_FORMAT(CONCAT(end_year,'-',end_month,'-',end_day,' ',IF(end_hour = 24 AND (end_minute IS NULL OR end_minute = 0), '23', end_hour),':',IF(end_hour = 24 AND end_minute IS NULL, '59', IFNULL('00',end_minute)),':00'),'%Y-%m-%d %H:%i:%s') as end_date
			FROM tb_private_holiday 
			WHERE customer_id = '{$artist_id}' AND ('{$yy}' BETWEEN start_year AND end_year) AND ('{$mm}' BETWEEN start_month AND end_month)
		)A
		GROUP BY start_date, end_date, customer_id";
$ph_result = mysqli_query($connection, $ph_sql);
while ($ph_datas = mysqli_fetch_object($ph_result)) {
    $type = $ph_datas->type;
    $sy = $ph_datas->start_year;
    $sm = $ph_datas->start_month;
    $sd = $ph_datas->start_day;
    $sh = $ph_datas->start_hour;
    $ey = $ph_datas->end_year;
    $em = $ph_datas->end_month;
    $ed = $ph_datas->end_day;
    $eh = $ph_datas->end_hour;
    if ($type == "all" && $artist_data->worker == $ph_datas->worker) {
        if ($sm < $mm && $em > $mm) {
            $sd = 1;
            $ed = (int) date('t', strtotime("$yy-$mm-01"));
            for ($index = $sd; $index <= $ed; $index++) {
                array_push($private_all_holiday_array, $index);
            }
        } else if ($sm == $mm && $em != $mm) {
            $ed = (int) date('t', strtotime("$yy-$mm-01"));
            for ($index = $sd; $index <= $ed; $index++) {
                array_push($private_all_holiday_array, $index);
            }
        } else if ($em == $mm && $sm != $mm) {
            $sd = 1;
            for ($index = $sd; $index <= $ed; $index++) {
                array_push($private_all_holiday_array, $index);
            }
        } else {
            for ($index = $sd; $index <= $ed; $index++) {
                array_push($private_all_holiday_array, $index);
            }
        }
    } else if ($type == "notall" || $type == "not") {
        array_push($private_notall_holiday_array, $sd);
    }
}

// $payment_log = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
// 날짜별 예약 건수 호출
$o_sql = "
	SELECT pay.* 
	FROM `tb_payment_log` AS pay
	WHERE pay.artist_id = '" . $user_id . "' 
		AND pay.year = '" . $yy . "' 
		AND pay.month = '" . $mm . "' 
		AND pay.is_cancel = 0
		AND pay.data_delete = '0'
		AND pay.is_no_show = 0
";
$o_result = mysqli_query($connection, $o_sql);
$payment_data   = array();
while ($o_rows = mysqli_fetch_assoc($o_result)) {
    $payment_log[$o_rows['day']] += 1;
    $payment_data[$o_rows['day']][]    = $o_rows;


}

//print_r($payment_data);
?>


<!-- container -->
<section id="container">
    <!-- page-body -->
    <div class="page-body">
        <div class="reserve-calendar-wrap">
            <button type="button" onclick="location.href='reserve_time_sale_step1_3'" class="btn btn-outline-purple btn-middle-size btn-round">빈시간 판매하기</button>
            <!-- 캘린더 정렬 -->
            <div class="reserve-calendar-sort">
                <div class="sort-left">
                    <!-- actived클래스 추가시 활성화 -->
                    <button type="button" onclick="location.href='reserve_main_month?ch=month&yy=<?=$yy?>&mm=<?=$mm?>&dd=<?=$dd?>';"
                        class="btn-reserve-calendar-sort actived">월</button>
                    <button type="button" onclick="location.href='reserve_main_week?ch=week&yy=<?=$yy?>&mm=<?=$mm?>&dd=<?=$dd?>';"
                        class="btn-reserve-calendar-sort">주</button>
                    <button type="button" onclick="location.href='reserve_main_day?ch=day&yy=<?=$yy?>&mm=<?=$mm?>&dd=<?=$dd?>';"
                        class="btn-reserve-calendar-sort">일</button>
                    <button type="button" onclick="location.href='reserve_main_list?ch=list&yy=<?=$yy?>&mm=<?=$mm?>&dd=<?=$dd?>';"
                        class="btn-reserve-calendar-sort"><span class="icon icon-type-list-gray off"></span><span
                            class="icon icon-type-list-white on"></span></button>
                </div>
                <div class="sort-right">
                    <select>
                        <option value="beauty" selected>미용</option>
                        <option value="hotel">호텔</option>
                        <option value="playroom">유치원</option>
                    </select>
                </div>
            </div>
            <!-- //캘린더 정렬 -->
            <!-- 캘린더 상단 -->
            <div class="reserve-calendar-top">
                <button type="button" class="btn-reserve-calendar-ui btn-month-prev"><span
                        class="icon icon-calendar-prev-small"></span></button>
                <div class="reserve-calendar-title">
                    <div class="txt"></div>
                    <!--
					//	select태그는 투명을로 위로 띄웟고 , change 시 txt클래스내 문구 변경으로 디자인 처리
					-->
                    <select>
                        <?for($i=-6;$i<0;$i++){?>
                        <option value="<?=date("Y.m", mktime(0, 0, 0, $mm-$i, $dd,   $yy))?>"><?=date("Y.m", mktime(0, 0, 0, $mm-$i, $dd,   $yy))?></option>
                        <?}?>
                        <?for($i=0;$i<6;$i++){?>
                        <option <?if($i==0) echo "selected";?> value="<?=date("Y.m", mktime(0, 0, 0, $mm-$i, $dd,   $yy))?>"><?=date("Y.m", mktime(0, 0, 0, $mm-$i, $dd,   $yy))?></option>
                        <?}?>
                    </select>
                </div>
                <button type="button" class="btn-reserve-calendar-ui btn-month-next"><span
                        class="icon icon-calendar-next-small"></span></button>
            </div>
            <!-- //캘린더 상단 -->
            <!-- 캘린더 라벨 -->
            <div class="reserve-calendar-label">
                <div class="grid-layout reserve-calendar-label-group">
                    <div class="grid-layout-inner">
                        <div class="grid-layout-cell">
                            <div class="reserve-calendar-label-items">
                                <div class="reserve-calendar-label-state yellow"></div>앱-선결제
                            </div>
                        </div>
                        <div class="grid-layout-cell">
                            <div class="reserve-calendar-label-items">
                                <div class="reserve-calendar-label-state purple"></div>앱-매장결제
                            </div>
                        </div>
                        <div class="grid-layout-cell">
                            <div class="reserve-calendar-label-items">
                                <div class="reserve-calendar-label-state green"></div>매장예약
                            </div>
                        </div>
                        <div class="grid-layout-cell">
                            <div class="reserve-calendar-label-items">
                                <div class="reserve-calendar-label-state red"></div>NoShow
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- //캘린더 라벨 -->
            <!-- 캘린더 상세 -->
            <div>
                <div class="reserve-calendar-data">
                    <div class="reserve-calendar-inner">
                        <!--
						// calendar-month-header-col 클래스 정의
						//	sunday : 일요일
						//	saturday : 토요일

						// calendar-month-body-col 클래스 정의
						// before : 이전월
						// after : 다음월
						//	sunday : 일요일
						//	saturday : 토요일
						//	break :휴무
						//	holiday :공휴일
						// today : 오늘
						// calendar-drag-item-group : 드래그 가능한 영역
						// calendar-drag-item : 드래그 아이템
						-->
                        <!--
						// calendar-week-time-item 상황별 클래스값
						// yellow : 앱-선결제
						// purple : 앱-매장결제
						// green : 매장예약
						// red : NoShow
						// gray : 승인대기
						-->
                        <div class="calendar-month-wrap">
                            <div class="calendar-month-header">
                                <div class="calendar-month-header-row">
                                    <div class="calendar-month-header-col sunday">일</div>
                                    <div class="calendar-month-header-col">월</div>
                                    <div class="calendar-month-header-col">화</div>
                                    <div class="calendar-month-header-col">수</div>
                                    <div class="calendar-month-header-col">목</div>
                                    <div class="calendar-month-header-col">금</div>
                                    <div class="calendar-month-header-col saturday">토</div>
                                </div>
                            </div>
                            <div class="calendar-month-body">
                                
                                <?

                                    $today_yy = date('Y');
                                    $today_mm = date('m');

                                    // 5. 화면에 표시할 화면의 초기값을 1로 설정
                                    $day = 0;

                                    // 6. 총 주 수에 맞춰서 세로줄 만들기
                                    for ($i = 1; $i <= $total_week; $i++) {
                                        
                                        
                                        
                                        
                                        ?>
                                        <div class="calendar-month-body-row">
                                            <?
                                            // 7. 총 가로칸 만들기
                                            for ($j = 0; $j < 7; $j++) {
                                                $rest_word = "";

                                                $current_time = date("" . $yy . "-" . $mm . "-" . $day . " 00:00:00");
                                                $next_3month_time = date("Y-m-d 00:00:00", strtotime("+3 months"));
                                                $next_3month_int = strtotime($next_3month_time);

                                                $cal_time = date('Y-m-d 00:00:00');
                                                $current_int = strtotime($current_time);
                                                $cal_int = strtotime($cal_time);
                                                

                                                //if ($current_int < $cal_int || $current_int > $next_3month_int) { echo " bgcolor='#bbbbbb' "; $rest_word = ""; $is_rest = true; }
                                                if (($j == 0 && $is_sunday) || ($j == 0 && $is_rest_public_holiday) || ($is_rest_public_holiday && in_array($day, $holiday_array))) {
                                                    $rest_class =   "break";
                                                    $rest_word = "정휴1";
                                                    //$is_rest = true;
                                                } else if ($j == 1 && $is_monday) {
                                                    $rest_class =   "break";
                                                    //$rest_word = "정휴2";
                                                    //$is_rest = true;
                                                } else if ($j == 2 && $is_tuesday) {
                                                    $rest_class =   "break";
                                                    $rest_word = "정휴";
                                                    //$is_rest = true;
                                                } else if ($j == 3 && $is_wednesday) {
                                                    $rest_class =   "break";
                                                    $rest_word = "정휴";
                                                // $is_rest = true;
                                                } else if ($j == 4 && $is_thursday) {
                                                    $rest_class =   "break";
                                                    $rest_word = "정휴";
                                                    //$is_rest = true;
                                                } else if ($j == 5 && $is_friday) {
                                                    $rest_class =   "break";
                                                    $rest_word = "정휴";
                                                    //$is_rest = true;
                                                } else if ($j == 6 && $is_saturday) {
                                                    $rest_class =   "break";
                                                    $rest_word = "정휴";
                                                    //$is_rest = true;
                                                } else if ($i == $total_week && $j > $last_week) {
                                                    //echo " bgcolor='#ffffff' ";
                                                } else if (in_array($day, $private_all_holiday_array)) {
                                                    $rest_class =   "break";
                                                    $rest_word = "임휴";
                                                    //$is_rest = true;
                                                } else if (in_array($day, $private_notall_holiday_array)) {
                                                    //echo " bgcolor='#eceaea' ";
                                                    $rest_word = "일정";
                                                } else {
                                                    $rest_class  = "";
                                                    $rest_word  = "";
                                                    //echo " bgcolor='#ffffff' ";
                                                }
                                                
                                                if (!(($i == 1 && $j < $start_week) || ($i == $total_week && $j > $last_week))) {

                                                    // 14. 날자 증가
                                                    $day++;
                                                    $day_print  = true;

                                                    // in_array($day, $holiday_arr) || 
                                                    $holiweek_idx = (int) date('w', strtotime("{$yy}-{$mm}-{$day}"));
                                                    $day_link   = "?ch=day&yy={$yy}&mm={$mm}&dd={$day}&backurl=" . urlencode($_SERVER['PHP_SELF']);
                                                    
                                                    if ($j == 0 || in_array($day, $holiday_arr)) {
                                                        $day_class  = "sunday";
                                                    } else if ($j == 6) {
                                                        // 10. $j가 0이면 토요일이므로 파란색
                                                        $day_class  = "saturday";
                                                    } else {
                                                        // 11. 그외는 평일이므로 검정색
                                                        $day_class  = "";
                                                    }
                        
                                                    // 12. 오늘 날자면 굵은 글씨가 아니라 언더라인이겠지
                                                    if ($today_yy == $yy && $today_mm == $mm && $day == date("j")) {
                                                        $today_class    = "today";
                                                    }else{
                                                        $today_class    = "";
                                                    }
                        
                                                    
                                                
                        
                        
                        
                        
                                                    //스케줄 출력
                                                    $schstr = get_schedule($yy,$mm,$day);
                                                    
                        
                                                    
                        
                                                    
                                                }else{
                                                    $rest_class =   "before";
                                                    $rest_word = "";
                                                    $day_print  = false;
                                                    
                                                }


                                                if ($css_pay_type != "noshow") {


                                                    if ($pay_type == 'card' || $pay_type == 'bank') { 
                                                        $css_pay_type   = "yellow";
                                                    }else if($pay_type == 'offline-card' || $pay_type == 'offline-cash' || $pay_type == 'offline') { 
                                                        $css_pay_type   = "purple";
                                                    }else if($pay_type == 'pos-card' || $pay_type == 'pos-cash') { 
                                                        $css_pay_type   = "green";
                                                    }

                                                 }else{
                                                    $css_pay_type   = "red";
                                                 }
                                            ?>
                                                

                                                


                                                <div class="calendar-month-body-col <?=$rest_class?> <?=$day_class?> <?=$today_class?>" <?if($rest_class==""){?> onclick="location.href='reserve_main_day<?=$day_link?>'" style="cursor:pointer"<?}?>>
                                                    <div class="calendar-col-inner">
                                                        <div class="calendar-day-value">
                                                            <div class="number"><?if($day_print===true) echo $day;?></div>
                                                            <div class="state"><?=$rest_word?></div>
                                                        </div>
                                                        
                                                        <div class="calendar-drag-item-group">
                                                         <?if ($payment_log[$day] > 0) {?>
                                                            <?foreach($payment_data[$day] as $key=> $val){ 
                                                                //print_r($val);
                                                                if($val['is_no_show']){
                                                                    $div_class  = "red";
                                                                }else{
                                                                    if($val['pay_type'] == "card" || $val['pay_type'] == "bank"){
                                                                        $div_class  = "yellow";
                                                                    }else if($val['pay_type'] == "offline-card" || $val['pay_type'] == "offline-cash" || $val['pay_type'] == "offline"){
                                                                        $div_class  = "purple";
                                                                    }else if($val['pay_type'] == "pos-card" || $val['pay_type'] == "pos-cash"){
                                                                        $div_class  = "green";
                                                                    }
                                                                }
                                                                
                                                                $log_product    = explode("|",$val['product']);
                                                                ?>
                                                            <div class="calendar-drag-item">
                                                                <div class="calendar-month-day-item <?=$div_class?>"><?=$log_product['0']?></div>
                                                            </div>
                                                            <?}?>

                                                        <?}?>
                                                        </div>
                                                        <div class="calendar-total-value"><?if ($payment_log[$day] > 0) {?>총 <?=$payment_log[$day]?>건<?}?></div>
                                                        
                                                    </div>
                                                </div>



                                            <? 
                                            
                                            
                                        } ?>

                                        </div>

                                    <? 
                                    
                                } ?>
                                    
                                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- //캘린더 상세 -->
        </div>
        <div class="reserve-calendar-float">
            <div class="reserve-calendar-float-cell">
                <button onclick="location.href='reserve_main_day';" type="button" class="btn-reserve-calendar-today"><span
                        class="icon icon-circle-float-today"></span></button>
            </div>
            <div class="reserve-calendar-float-cell">
                <button type="button" class="btn-reserve-calendar-menu btn-reserve-calendar-toggle"><span
                        class="icon icon-circle-float-menu"></span><em><?= $wait_count ?></em></button>
                <div class="reserve-calendar-float-menu">
                    <button type="button" class="btn-float-menu" onclick="location.href='customer_inquiry';">고객조회</button>
                    <button type="button" class="btn-float-menu" onclick="location.href='customer_pet_new';">신규등록</button>
                    <button type="button" class="btn-float-menu" onclick="location.href='reserve_advice_list_1';">상담 승인 대기 : <?= $wait_count ?></button>
                    <button type="button" class="btn-float-menu">예약 승인 대기 : </button>
                    <button type="button" onclick="location.href='reserve_time_sale_step1_3'" class="btn-float-menu">빈시간 판매하기</button>
                </div>
            </div>
        </div>
    </div>
    <!-- //page-body -->
</section>
<!-- //container -->
<script src="/static/pub/js/Sortable.min.js"></script>
<script>
$(function() {
    
    /*
	$( "#sortable" ).sortable({
		placeholder: "ui-state-highlight",
		cancel:''
    });
	$( "#sortable" ).disableSelection();
	*/

    //https://github.com/SortableJS/Sortable

    $('.calendar-month-body-col').each(function() {
        if (!$(this).hasClass('break')) {
            //휴무가 아닐 경우 드래그앤 드롭 가능 처리
            var sortable = Sortable.create($(this).find('.calendar-drag-item-group')[0], {
                group: 'shared',
                delay: 500,
                delayOnTouchOnly: true,
                ghostClass: 'guide',
                draggable: '.calendar-drag-item',
                onStart: function(evt) {
                    //드래그 시작 
                    console.log('drag start');
                },
                onEnd: function(evt) {
                    //드래그 끝
                    console.log('drag end');
                    //evt.to;    // 현재 아이템
                    //evt.from;  // 이전 아이템
                    //evt.oldIndex;  // 이전 인덱스값
                    //evt.newIndex;  // 새로운 인덱스값
                },
                onUpdate: function(evt) {
                    console.log('update');
                },
                onUpdate: function(evt) {
                    console.log('onChange');
                },
                onRemove: function( /**Event*/ evt) {
                    console.log('remove');
                }

            });
        }
    });



    //$(".reserve-calendar-top select").val('<?=$yy?>.<?=sprintf('%02d',$mm)?>');
    $(".reserve-calendar-top .txt").text($(".reserve-calendar-top select").val());
    var idx = $(".reserve-calendar-top select option").index( $(".reserve-calendar-top select option:selected") );
    if(idx==0){
        $(".btn-month-next").hide();
    }

    if(idx==11){
        $(".btn-month-prev").hide();
    }
    

    $(".reserve-calendar-top select").change(function () {
        var yymm    = $(this).val().split(".");
        if(yymm[1]>=10){
            location.href="reserve_main_month?ch=month&yy="+yymm[0]+"&mm="+yymm[1];
        }else{
            location.href="reserve_main_month?ch=month&yy="+yymm[0]+"&mm="+yymm[1].replace("0","");
        }
        
    });

    $(".btn-month-prev").on('click',function(e){
        var idx = $(".reserve-calendar-top select option").index( $(".reserve-calendar-top select option:selected") );
        /* $('.reserve-calendar-top select option:eq(' + (idx+1) + ')').attr('selected', 'selected').change();
        e.preventDefault(); */

    });
    /* $(".btn-month-prev").click(function (e) {
        var idx = $(".reserve-calendar-top select option").index( $(".reserve-calendar-top select option:selected") );
        $('.reserve-calendar-top select option:eq(' + (idx+1) + ')').attr('selected', 'selected').change();
        e.preventDefault();
        
    }); */

    $(".btn-month-next").click(function (e) { 
        var idx = $(".reserve-calendar-top select option").index( $(".reserve-calendar-top select option:selected") );
        $('.reserve-calendar-top select option:eq(' + (idx-1) + ')').attr('selected', 'selected').change();
        e.preventDefault();
        
    });

                
});
</script>

<?
include($_SERVER['DOCUMENT_ROOT']."/include/skin/footer_shop.php");
?>