<?php
function alert($str){
	echo '<script>alert("'.$str.'");</script>';
}
function history_back($idx=0){
	echo '<script>history.back('.$idx.');</script>';
}
function date2timestamp($Ymd){
	//mktime(int hour, int minute, int second, int month, int day, int year );
	return mktime(date('H',$Ymd),date('i',$Ymd),date('s',$Ymd),date('m',$Ymd),date('d', $Ymd),date('Y',$Ymd));
}
function make_password_hash($pass){
	$password=base64_encode(hash_hmac('sha256', $pass, 'toron_pass_word_hash', TRUE)); 
	return $password;  
}
function make_user_directory($static_directory,$user_id){
	$mkdir = $static_directory."/".$user_id."/";
	if (!is_dir($mkdir)) {
        $old = umask(0);
		mkdir($mkdir);
		chmod($mkdir,0777);
		umask($old);
	}
	//echo $mkdir;
	return 1;
}
function str2hex($string){
    $hex = '';
    for ($i=0; $i<strlen($string); $i++){
        $ord = ord($string[$i]);
        $hexCode = dechex($ord);
        $hex .= substr('0'.$hexCode, -2);
    }
    return strToUpper($hex);
}
function rand_id () {
	$result = "";
	for ($i = 0; $i < 5; $i++) {
		$result = $result.strval(mt_rand(0, 99));
	}
	return $result."_".date('YmdHis');
}
$bad_word = "개새끼/씨발/씨팔/조까/졸라/자지/보지/씹/좃/좆/좇/병신/젖까/지랄/좆까/좃까/좇까/씹/씹할/앂/씨.발/개.새.끼/";
function check_bad_word($word) {
    if (strpos($word, $bad_word) !== false) {
		return 1; // 포함
	} else {
		return 0; // 미포함
	}
}
function app_push($arr_userapikey,$title,$memo,$path,$image, $is_partner){
		$result =[];
 		global $connection;
        //$API_ACCESS_KEY= 'AAAAKR8K-yk:APA91bFGTYpY4e0uOZw1IfOmyMc9dQQlDfsXCWKUAkoJBMPudzEdXYuXJVHgkZrmXp8ikj0qKrtb8rV63-jcgCMsEiZaCdwc1bCUyiSrCsayIdcEkFhS29Ok5zK559Bh8c9rYrA-T5cY'; // 기존 키값

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if(strpos($user_agent, "app_gopet_partner_and_one_store") > 0){ // 발신견주 앱
            $API_ACCESS_KEY= 'AAAAk71B8O8:APA91bG73bS1yk41VOeiOMmPdRCnPXA6Ar3dcfSYm0dDpbq6XB6TiTgAbht9dne6WtLzCBwZifKwsJ4JCMbs5XkShuyuEKGoGY_MqfW4OEXznrRbHElyzt59e74lwjQQqhpDVwxqMoqK';
        }else if($is_partner == "partner"){ // 파트너앱
			$API_ACCESS_KEY= 'AAAAexOuErg:APA91bGIHbSkZlt46HZaPelJtBMNPskBVkJ0w9z944k-UkppzuasuiWhpeexSkgnsM3TC7XVExCmkKgbQSk_48-CX54rZmSgtzeLWOjgPbVSdTFJ13No_Hm2kQnH7LxW37fLiS6-_VUE';
        }else{ // 견주앱
			$API_ACCESS_KEY= 'AAAAKR8K-yk:APA91bFGTYpY4e0uOZw1IfOmyMc9dQQlDfsXCWKUAkoJBMPudzEdXYuXJVHgkZrmXp8ikj0qKrtb8rV63-jcgCMsEiZaCdwc1bCUyiSrCsayIdcEkFhS29Ok5zK559Bh8c9rYrA-T5cY';
		}

		
 		$path = str_replace("http://" , "https://" , $path);

 		// https://firebase.google.com/docs/cloud-messaging/http-server-ref		
          $postJSONData = array(
 //                'registration_ids' => $arr_userapikey,
                  'content_available' => true,
                 'priority' =>'high',
                 'notification' => array(
                         'title' => $title,
                         'body' =>$memo,
                         'count' =>'0',
                         'sound' =>'default',
                         'image' => $image,
                         'path' => $path
                 ),
                  'data'  => array(
                         'title' =>$title,
                          'msg' =>$memo,
                          'count' =>'0',
                          'path' =>$path,
                         'image' => $image
                  )
          );

 		$_lst_to_user_token = "";	// 수신자 구분 저장용
         if ($arr_userapikey != null && $arr_userapikey != "") {
                 if(is_array($arr_userapikey)) {
                         $postJSONData['registration_ids'] = $arr_userapikey;	//  멀티캐스트 메시지(둘 이상의 등록 토큰으로 전송된 메시지)의 수신자를 지정
 						$_lst_to_user_token = json_encode( $arr_userapikey );
                 } else {
                         $postJSONData['to'] = $arr_userapikey;	// 수신자 지정
 						$_lst_to_user_token = $arr_userapikey;
                 }
         }

          $headers = array
          (
                 'Authorization: key=' .$API_ACCESS_KEY,
                 'Content-Type: application/json'
          );

         $request_msg =  json_encode( $postJSONData );
         //echo $request_msg."<br>";
          $ch = curl_init();
          curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
          curl_setopt( $ch,CURLOPT_POST, true );
          curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
          curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
          curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
          curl_setopt( $ch,CURLOPT_POSTFIELDS, $request_msg );
          $result = curl_exec($ch );
          curl_close( $ch );

 		 mysqli_query($connection, "INSERT INTO tb_cron_log (`data`, `result`, `reg_dt`, `lst_to_user_token`) VALUES ('".addslashes($request_msg)."', '".addslashes($result)."', NOW(), '". $_lst_to_user_token ."')");

         return $result;
}

function app_push_iOS($receiver, $title, $memo, $path)
{	
	$PUSH_IOS_REAL = 1;
		
	$body['aps'] = array(
		'alert' => array("title" => $title, "body" => $memo),
		'badge' => 0,
		'sound' => 'default'
	);
	
	$body['extra_info'] = array('name' => 'url', 'url' => $path);
	
	$payload = json_encode($body);
	
	$token = "";	
	$token = chr(0).pack('n', 32).pack('H*', $receiver).pack('n', strlen($payload)).$payload;
		
	if ($PUSH_IOS_REAL == 1)
	{
		$cert = "/opt/apache/htdocs/pem/apns.pem";
		$url = "ssl://gateway.push.apple.com:2195";
	}
	else
	{
		$cert = "/opt/apache/htdocs/pem/apns.pem";
		$url = "ssl://gateway.sandbox.push.apple.com:2195";
	}
	
	$ctx = stream_context_create();
	stream_context_set_option($ctx, 'ssl', 'local_cert', $cert);
    
	$fp = stream_socket_client($url, $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
				
	if (!$fp)
	{
		exit("Failed to connect: $err $errstr" . PHP_EOL);
		return false;
	}
	    
	$result = fwrite($fp, $token, strlen($token));
	fclose($fp);

	if (!$result)
		return false;
	else
		return true;

}

function a_push ($customer_id, $title, $memo, $path, $image, $is_partner)
{
	global $connection;
	
	$sql = "select token, partner_token from tb_customer where id = '".$customer_id."';";
    $result = mysqli_query($connection, $sql);
    if($rs = mysqli_fetch_object($result)) 
    {
//        if ($rs->token != null && $rs->token != "")
//        {
			if($is_partner == 'partner'){
				return app_push($rs->partner_token, $title, $memo, $path, $image, $is_partner);
			}else{
				return app_push($rs->token, $title, $memo, $path, $image, $is_partner);
			}

//        }
    }
    
    return null;
}

function all_push($title,$memo,$path,$image){
	global $connection;
	
	$arr_userapikey=array();
	$sql = "select DISTINCT token from tb_customer;";
	$result = mysqli_query($connection, $sql);
        while($rs = mysqli_fetch_object($result)) {
		if ($rs->token != null && $rs->token != "") {
	                array_push($arr_userapikey,$rs->token);
		}
        }
	return app_push($arr_userapikey, $title, $memo, $path, $image);
}

function add_vat ($value, $is_vat) {
	if (intval($is_vat)) {
		return intval($value) + (intval($value) * 0.1);
	} else {
		return $value;
	}
}
function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}
function getBankDDay ($now_hour) {
	$bank_d_day = 0;
	if ($now_hour >= 18) {
		$next_d_hour = (10 + (24 - $now_hour));
		$bank_d_day = strtotime ("+".strval($next_d_hour)." hours");
	} else if ($now_hour <= 8) {
		$next_d_hour = (10 - $now_hour);
		$bank_d_day = strtotime ("+".strval($next_d_hour)." hours");
	} else {
		$bank_d_day = strtotime ("+2 hours");
	}
	return $bank_d_day;
}
function strcut_utf8($str, $len){
    preg_match_all('/[\xE0-\xFF][\x80-\xFF]{2}|./', $str, $match);
    $m = $match[0];
    $slen = strlen($str); // length of source string
    $tail = '...';
    $tlen = $tail; // length of tail string
    if ($slen <= $len) return $str;
    $ret = array();
    $count = 0;
    for ($i=0; $i < $len; $i++){
        $count += (strlen($m[$i]) > 1)?2:1;
 
        if ($count + $tlen > $len) break;
        $ret[] = $m[$i];
    }
    return join('', $ret).$tail;
}

function add_hyphen($tel)
{
    $tel = preg_replace("/[^0-9]/", "", $tel);    // 숫자 이외 제거
    if (substr($tel,0,2)=='02')
        return preg_replace("/([0-9]{2})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $tel);
    else if (strlen($tel)=='8' && (substr($tel,0,2)=='15' || substr($tel,0,2)=='16' || substr($tel,0,2)=='18'))
        // 지능망 번호이면
        return preg_replace("/([0-9]{4})([0-9]{4})$/", "\\1-\\2", $tel);
    else
        return preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})$/", "\\1-\\2-\\3", $tel);
}


/*function makeSelectBoxForProduct ($name, $start, $increase, $end, $selected) {
	$result = "<select name='".$name."' id='".$name."'>";
	for ($i = $start ; $i <= $end ; $i = $i + $increase) {
		if ($i == $selected) {
			$result = $result."<option value='".$i."' selected>".number_format($i)."</option>";
		} else {
			$result = $result."<option value='".$i."'>".number_format($i)."</option>";
		}
	}
	$result = $result."</select>";
	return $result;
}*/

//2019-06-21 hue(로그인 상태 유지 7일로 맞춤)
function cookie_save($uid,$master_key_name){
	$hash = hash('sha256', $master_key_name.$uid);
	//setcookie("auto_login_uid",$uid,time()+(86400*7),"/",".".$_SERVER['HTTP_HOST']."; samesite=strict");
	//setcookie("user_hash",$hash,time()+(86400*7),"/",".".$_SERVER['HTTP_HOST']."; samesite=strict");
    setcookie('auto_login_uid', $uid, ['expires'=>time()+(86400*7), 'path'=>'/', 'domain'=>'.'.$_SERVER['HTTP_HOST'], 'samesite' => 'strict']);
    setcookie('user_hash', $hash, ['expires'=>time()+(86400*7), 'path'=>'/', 'domain'=>'.'.$_SERVER['HTTP_HOST'], 'samesite' => 'strict']);
}

function img_link_change($url){
	$url	= str_replace("/pet/upload", "/upload", $url);
	$url	= str_replace("/user/images", "/images", $url);
	return $url;
}

function cant_reservation_week($conn, $artist_id, $worker, $date)
{

	//예약금지 설정된 일자에 해당 데이터가 있는지 검사한다.
	$que = "
				SELECT 
				     ph_seq
				     ,CONCAT(start_year,'-',IF(length(start_month)=1,LPAD(start_month,'2','0'),start_month),'-',IF(length(start_day)=1,LPAD(start_day,'2','0'),start_day),' ',LPAD(start_hour,'2','0'),':',LPAD(start_minute,'2','0')) as start_datetime
					 ,CONCAT(end_year,'-',IF(length(end_month)=1,LPAD(end_month,'2','0'),end_month),'-',IF(length(end_day)=1,LPAD(end_day,'2','0'),end_day),' ',LPAD(end_hour,'2','0'),':',LPAD(end_minute,'2','0')) as end_datetime
				FROM 
				     tb_private_holiday 
				WHERE 
				      customer_id = '{$artist_id}' 
				  AND worker = '{$worker}' 
				  AND CONCAT(start_year,'-',LPAD(start_month,'2','0'),'-',LPAD(start_day,'2','0')) = '{$date}'
				  ";
	//echo $que."<br>";
	$res = mysqli_query($conn, $que);
	$row = mysqli_fetch_assoc($res);
	if ($row['ph_seq']) {
		return $row;
	}
}


function cant_reservation_day($conn, $artist_id, $worker, $date)
{

	//예약금지 설정된 일자에 해당 데이터가 있는지 검사한다.
	$que = "
				SELECT 
				     ph_seq
				     ,CONCAT(start_year,'-',IF(length(start_month)=1,LPAD(start_month,'2','0'),start_month),'-',IF(length(start_day)=1,LPAD(start_day,'2','0'),start_day),' ',LPAD(start_hour,'2','0'),':',LPAD(start_minute,'2','0')) as start_datetime
					 ,CONCAT(end_year,'-',IF(length(end_month)=1,LPAD(end_month,'2','0'),end_month),'-',IF(length(end_day)=1,LPAD(end_day,'2','0'),end_day),' ',LPAD(end_hour,'2','0'),':',LPAD(end_minute,'2','0')) as end_datetime
				FROM 
				     tb_private_holiday 
				WHERE 
				      customer_id = '{$artist_id}' 
				  AND worker = '{$worker}'
				  AND CONCAT(start_year,'-',LPAD(start_month,'2','0'),'-',LPAD(start_day,'2','0')) = '{$date}'
				  ";
	//echo $que."<br>";
	$res = mysqli_query($conn, $que);
	$row = mysqli_fetch_assoc($res);
	if ($row['ph_seq']) {
		return $row;
	}
}


function cant_reservation_week_nor($conn, $artist_id)
{
	$regular_break_day = array();
	//정기휴일을 확인한다.
	$que = "
				SELECT 
				     *
				FROM 
				     tb_regular_holiday 
				WHERE 
				      customer_id = '{$artist_id}'
				  ";
//	echo $que."<br>";
	$res = mysqli_query($conn, $que);
	$row = mysqli_fetch_assoc($res);

	if($row['is_sunday']==1){
		$regular_break_day[] = 0;
	}
	if($row['is_monday']==1) {
		$regular_break_day[] = 1;
	}
	if($row['is_tuesday']==1) {
		$regular_break_day[] = 2;
	}
	if($row['is_wednesday']==1) {
		$regular_break_day[] = 3;
	}
	if($row['is_thursday']==1) {
		$regular_break_day[] = 4;
	}
	if($row['is_friday']==1) {
		$regular_break_day[] = 5;
	}
	if($row['is_saturday']==1) {
		$regular_break_day[] = 6;
	}

	return $regular_break_day;
}

//미용 추가옵션에서 옵션목록 저장된 컬럼을 분리한다.
function get_explode_data($data){
	$dt = explode(",",$data);
	return $dt;
}

//컬럼 형싱 : 으로 분리하기
function split_text($data){
    if($data == "") //eaden
		return null;
    $data_tmp = explode(",",$data);
	for($i=0;$i<count($data_tmp);$i++) {
		$data_new = explode(":", $data_tmp[$i]);
		$res['name'][] = $data_new[0];
		$res['price'][] = $data_new[1];
	}

	return $res;
}


//sql_query
function sql_query($que){
	global $connection;
	$res = mysqli_query($connection, $que);
	return $res;
}

//msyqli_fetch
function sql_fetch($result){
	global $connection;
	if( ! $result) return array();
	if(function_exists('mysqli_fetch_assoc') && G5_MYSQLI_USE)
		$row = @mysqli_fetch_assoc($result);
	else
		$row = @mysql_fetch_assoc($result);

	return $row;
}
function sql_fetch_array($sql){
	global $connection;
	$res = mysqli_query($connection, $sql);
	while($rows = mysqli_fetch_array($res)){
		$data[] = $rows;
	}
	return $data;

}

//빈시간 판매를 위한 예약정보 구하기
function get_reservation_empty_chk($user_id, $worker, $date, $time){
	global $connection;
	// 날짜별 예약 건수 호출

	$t = explode(":",$time);
	$t1 = intval($t[0]);
	$t2 = intval($t[1]);
	$time = $t1.':'.$t2;
	$o_sql = "
	SELECT pay.* , 
	       (SELECT name FROM tb_mypet WHERE pet_seq = pay.pet_seq) AS pet_name,
	       (SELECT pet_type FROM tb_mypet WHERE pet_seq = pay.pet_seq) AS pet_type
	FROM `tb_payment_log` AS pay
	WHERE pay.artist_id = '" . $user_id . "' 
		AND CONCAT(pay.year,'-',pay.month,'-',pay.day,' ',pay.hour,':',pay.minute) = '{$date} {$time}'		
		AND pay.is_cancel = 0
		AND pay.data_delete = '0'		
		
";
//	echo $o_sql;
	$o_result = mysqli_query($connection, $o_sql);
	$payment_data   = array();
	while($row = mysqli_fetch_array($o_result)){
		$data[] = $row;
	}

	return $data;
}


function get_reservation_chk($user_id, $worker, $date, $time){
	global $connection;
	// 날짜별 예약 건수 호출

	$t = explode(":",$time);
	$t1 = intval($t[0]);
	$t2 = intval($t[1]);
	$time = $t1.':'.$t2;
	$o_sql = "
	SELECT pay.* , 
	       (SELECT name FROM tb_mypet WHERE pet_seq = pay.pet_seq) AS pet_name,
	       (SELECT pet_type FROM tb_mypet WHERE pet_seq = pay.pet_seq) AS pet_type,
	       (SELECT is_approve FROM tb_grade_reserve_approval_mgr WHERE payment_log_seq = pay.payment_log_seq) AS is_await,
	       (SELECT idx FROM tb_grade_reserve_approval_mgr WHERE payment_log_seq = pay.payment_log_seq) AS approve_seq
	FROM `tb_payment_log` AS pay
	WHERE pay.artist_id = '" . $user_id . "' 
		AND CONCAT(pay.year,'-',pay.month,'-',pay.day,' ',pay.hour,':',pay.minute) = '{$date} {$time}'		
		AND pay.is_cancel = 0
		AND pay.data_delete = '0'
		AND worker = '{$worker}'
		
";
	//echo $o_sql;
	$o_result = mysqli_query($connection, $o_sql);
	while($row = mysqli_fetch_array($o_result)){
		$data[] = $row;
	}

	return $data;
}


function get_reservation_await_chk($user_id, $worker, $date, $time){
	global $connection;
	// 날짜별 예약 건수 호출

	$t = explode(":",$time);
	$t1 = intval($t[0]);
	$t2 = intval($t[1]);
	$time = $t1.':'.$t2;
	$o_sql = "
	SELECT pay.* , 
	       (SELECT name FROM tb_mypet WHERE pet_seq = pay.pet_seq) AS pet_name,
	       (SELECT pet_type FROM tb_mypet WHERE pet_seq = pay.pet_seq) AS pet_type
	FROM `tb_payment_log` AS pay
	WHERE pay.artist_id = '" . $user_id . "' 
		AND CONCAT(pay.year,'-',pay.month,'-',pay.day,' ',pay.hour,':',pay.minute) = '{$date} {$time}'		
		AND pay.is_cancel = 0
		AND pay.data_delete = '0'
		AND worker = '{$worker}'
		
";
	//echo $o_sql;
	$o_result = mysqli_query($connection, $o_sql);
	while($row = mysqli_fetch_array($o_result)){
		$data[] = $row;
	}

	return $data;
}


//예약 진행중인 건수
function get_reservation_ing_chk($artist_id, $worker, $date, $time){
	global $connection;
	// 날짜별 예약 건수 호출

	$t = explode(":",$time);
	$t1 = intval($t[0]);
	$t2 = intval($t[1]);
	$time = $t1.':'.$t2;
	$o_sql = "
	SELECT COUNT(*) AS cnt, rowspan, update_time
	FROM `tb_reservation` AS pay
	WHERE pay.artist_id = '" . $artist_id . "'
		AND worker = '{$worker}' 
		AND update_time >= DATE_ADD(NOW(),INTERVAL -10 MINUTE)		
";
	//echo $o_sql;
	$o_result = mysqli_query($connection, $o_sql);
	while($row = mysqli_fetch_array($o_result)){
		$data[] = $row;
	}

	return $data;
}
//예약 블럭 개산하기
function make_rev_calc_height($start, $end){
	$block_height = 100; // 블럭 높이
	$tm = ($end - $start) ? ($end - $start) : (60 * 30);
	$time_block_cnt = $tm / (60 * 30); // 블럭갯수
	$style_height = $block_height * (int) $time_block_cnt;
	return $style_height;
}

//지난 일자 계산
function calc_passed_year($firstDate,$secondDate){
	$dateDifference = abs(strtotime($secondDate) - strtotime($firstDate));
	$years  = floor($dateDifference / (365 * 60 * 60 * 24));
	$months = floor(($dateDifference - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
	$days   = floor(($dateDifference - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 *24) / (60 * 60 * 24));

	echo $years.'년'.$months.'개월';
}

//보유 펫 정보 구하기
function get_mypet_info($crypto, $user_id, $phone,$access_key,$secret_key){
	global $connection;
	$cellphone_encode = $crypto->encode($phone, $access_key, $secret_key);
	$data = array();
	$cus = '';
	$sql = "
                                            SELECT *
                                            FROM tb_customer
                                            WHERE cellphone = '" . $cellphone_encode . "' 
                                                #and id = '{$user_id}'
                                                and nickname not like 'cellp_%'
                                        ";	// 20210705 by migo - cellp 제외 조건
	//echo $sql."<p>";

	$result = mysqli_query($connection, $sql);
	$member_cnt = mysqli_num_rows($result);
	if ($member_cnt == 0) { // (가회원)
		$sql = "
                                                SELECT *
                                                FROM tb_tmp_user
                                                WHERE cellphone = '" . $phone . "'
                                            ";
		//echo $sql;
		$result = mysqli_query($connection, $sql);
		$data["customer"] = mysqli_fetch_assoc($result);
		$data["customer"]["tmp_yn"] = "Y";
		$where_qy  = " AND mp.tmp_yn = '" . $data["customer"]["tmp_yn"] . "' ";
		$where_qy .= " AND mp.tmp_seq = '" . $data["customer"]["tmp_seq"] . "' ";
		$cus = $data["customer"]["tmp_seq"];
	} else {
		$data["customer"] = mysqli_fetch_assoc($result);
		$data["customer"]["tmp_yn"] = "N";
		$where_qy  = " AND mp.tmp_yn = '" . $data["customer"]["tmp_yn"] . "' ";
		$where_qy .= " AND mp.customer_id = '" . $data["customer"]["id"] . "' ";
		$data["customer"]["cellphone"] = $phone;
		$cus = $data["customer"]["id"];
	}

	// 펫 리스트 호출
	$sql = "
		SELECT * FROM (
			SELECT 
				if(sum(pl.data_delete) > 0, NULL, mp.pet_seq) AS pet_seq,
				if(sum(pl.data_delete) > 0, NULL, mp.name) AS name, 
				if(sum(pl.data_delete) > 0, NULL, mp.type) AS type,
				if(sum(pl.data_delete) > 0, NULL, mp.pet_type) AS pet_type 
			FROM tb_mypet AS mp
				LEFT OUTER JOIN (
					SELECT * 
					FROM tb_payment_log
					WHERE artist_id = '" . $user_id . "'
				) AS pl ON mp.pet_seq = pl.pet_seq
				
			WHERE 1=1 
			AND mp.data_delete = '0'
			" . $where_qy . "
			GROUP BY mp.pet_seq
		) AS past
		WHERE past.pet_seq IS NOT NULL
	";
	//echo $sql."<p><p>";
	$result = mysqli_query($connection, $sql);
	while ($row = mysqli_fetch_assoc($result)) {
		if ($row["pet_name"] != "") {
			$row["name"] = $row["pet_name"];
		}
		$row['cus'] = $cus;
		$data["pet_list"][] = $row;
	}

	return $data["pet_list"];
}
?>
