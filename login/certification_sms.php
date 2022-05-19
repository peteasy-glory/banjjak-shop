<? 
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

function cetificationnumber6()
{
    srand((float) microtime() * 1000000);
    $rand_code = substr(rand(10000000, 99999999), 0, 6);
    return $rand_code;
}

$sendsms = false;
$userphone = (isset($_POST['userphone'])) ? str_replace("-", "", $_POST['userphone']) : "";
$dbcheck = (isset($_POST['dbcheck'])) ? $_POST['dbcheck'] : "";

$rand_code = cetificationnumber6();
$_SESSION["gobeauty_certification_number"] = $rand_code;

$crypto = new Crypto();
$ss_userphone = $crypto->encode(trim($userphone), $access_key, $secret_key);

$_SESSION["gobeauty_regist_cellphone"] = $ss_userphone;

/******************** 인증 & 발송 정보 ********************/
$sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
// $sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // HTTPS 전송요청 URL
$msg = "[반짝, 반려생활의 단짝] 인증번호는 " . $rand_code . " 입니다.정확히 입력해주세요";
$sms['user_id'] = base64_encode("pickmonhq"); //SMS 아이디.
$sms['secure'] = base64_encode("56a0b2d62a60b16b0e99304eb3ad642a"); //인증키
$sms['smsType'] = base64_encode("S"); // LMS일경우 L
$sms['msg'] = base64_encode(stripslashes($msg));
//  $sms['rphone'] = base64_encode($_POST['rphone']);
$sms['rphone'] = base64_encode($userphone);

$sms['sphone1'] = base64_encode("1661");
$sms['sphone2'] = base64_encode("9956");
$sms['sphone3'] = base64_encode("");

//    $sms['testflag'] = base64_encode("Y");
$sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.

//    $sms['rdate'] = base64_encode($_POST['rdate']);
//    $sms['rtime'] = base64_encode($_POST['rtime']);
//    $sms['returnurl'] = base64_encode($_POST['returnurl']);

//    $sms['destination'] = urlencode(base64_encode($_POST['destination']));
//    $returnurl = $_POST['returnurl'];
//    $sms['repeatFlag'] = base64_encode($_POST['repeatFlag']);
//    $sms['repeatNum'] = base64_encode($_POST['repeatNum']);
//    $sms['repeatTime'] = base64_encode($_POST['repeatTime']);

//    $nointeractive = $_POST['nointeractive']; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략

$host_info = explode("/", $sms_url);


$host = $host_info[2];
//$path = $host_info[3] . "/" . $host_info[4];
$path = $host_info[3];

srand((float) microtime() * 1000000);
$boundary = "---------------------" . substr(md5(rand(0, 32000)), 0, 10);
//print_r($sms);

// 헤더 생성
$header = "POST /" . $path . " HTTP/1.0\r\n";
$header .= "Host: " . $host . "\r\n";
$header .= "Content-type: multipart/form-data, boundary=" . $boundary . "\r\n";

$data	= "";
// 본문 생성
foreach ($sms as $index => $value) {
    $data .= "--$boundary\r\n";
    $data .= "Content-Disposition: form-data; name=\"" . $index . "\"\r\n";
    $data .= "\r\n" . $value . "\r\n";
    $data .= "--$boundary\r\n";
}
$header .= "Content-length: " . strlen($data) . "\r\n\r\n";

// 테스트를 위해서 막음
$fp = fsockopen($host, 80);

if ($fp) {
    fputs($fp, $header . $data);
    $rsp = '';
    while (!feof($fp)) {
        $rsp .= fgets($fp, 8192);
    }
    fclose($fp);
    $msg = explode("\r\n\r\n", trim($rsp));
    $rMsg = explode(",", $msg[1]);
    $Result = $rMsg[0]; //발송결과
    $Count = $rMsg[1]; //잔여건수

    //발송결과 알림
    if ($Result == "success") {
        $alert = "성공";
        $alert .= " 잔여건수는 " . $Count . "건 입니다.";
        $sendsms = true;
    } else if ($Result == "reserved") {
        $alert = "성공적으로 예약되었습니다.";
        $alert .= " 잔여건수는 " . $Count . "건 입니다.";
    } else if ($Result == "3205") {
        $alert = "잘못된 번호형식입니다.";
    } else if ($Result == "0044") {
        $alert = "스팸문자는발송되지 않습니다.";
    } else {
        $alert = "[Error]" . $Result;
    }
} else {
    $alert = "Connection Failed";
}


?>
{
"msg": "<?= $alert ?>",
"sendsms": "<?= $sendsms ?>",
"data": "<?= $crypto->encode($rand_code, $access_key, $secret_key); ?>"
}