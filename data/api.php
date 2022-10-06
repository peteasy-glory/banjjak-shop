<?php
include($_SERVER['DOCUMENT_ROOT'] . "/include/global.php");
include($_SERVER['DOCUMENT_ROOT'] . "/common/TRestAPI.php");
include($_SERVER['DOCUMENT_ROOT']."/common/TEmoji.php");
$emoji = new TEmoji();

$user_id = (isset($_SESSION['gobeauty_user_id'])) ? $_SESSION['gobeauty_user_id'] : "";
$user_name = (isset($_SESSION['gobeauty_user_nickname'])) ? $_SESSION['gobeauty_user_nickname'] : "";





//$api = new TRestAPI("https://partnerapi.banjjakpet.com:8080","Token 2156d1824c98f27a1f163a102cf742002b15e624");
$api = new TRestAPI("http://stg-partnerapi.banjjakpet.com:8080","Token 55dda3818c897ef163b09a13d37199a7d211b6d2");
//$api2 = new TRestAPI("http://192.168.20.216:8080","Token 2156d1824c98f27a1f163a102cf742002b15e624");


$data = array();



$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");

$r_mode = ($_POST["mode"] && $_POST["mode"] != "") ? $_POST["mode"] : "";





if($r_mode) {

    if($r_mode === "shop_reserve_get"){

        $login_id = $_POST['login_id'];

        $data = $api->get('/partner/reserve/shop-reserve/'.$login_id);

        $return_data = array("code" => "000000", "data"=>$data);

    }else if($r_mode === 'deposit_save'){

        $artist_id = $_POST['artist_id'];
        $reserve_price = $_POST['reserve_price'];
        $deadline = $_POST['deadline'];
        $bank_name = $_POST['bank_name'];
        $account_num = $_POST['account_num'];

        $data = array(
            'artist_id'=>$artist_id,
            'reserve_price'=>intval($reserve_price),
            'deadline'=>intval($deadline),
            'bank_name'=>$bank_name,
            'account_num'=>$account_num
        );

        $data_json = json_encode($data);

        $result = $api -> put('/partner/reserve/shop-reserve',$data_json);

        $return_data = array("code"=>"000000","data"=>$result);

    }else if($r_mode === 'get_deposit'){

        $artist_id = $_POST['artist_id'];

        $result = $api -> get('/partner/reserve/shop-reserve/'.$artist_id);

        $return_data = array("code"=>"000000","data"=>$result);
    }else if($r_mode === 'deposit_finish') {


        $payment_log_seq = $_POST['payment_log_seq'];
        $reserve_pay_yn = $_POST['reserve_pay_yn'];

        $data = array('payment_log_seq' => $payment_log_seq,'reserve_pay_yn'=>intval($reserve_pay_yn));

        $data_json = json_encode($data);

        $result = $api->put('/partner/reserve/payment-reserve', $data_json);

        $return_data = array("code" => "000000", "data" => $result);
    }else if($r_mode === 'reserve_regist_allim'){


        $cellphone = $_POST['cellphone'];
        $message = $_POST['message'];
        $tem_code = "1000004530_20001";
        $payment_idx = $_POST['payment_idx'];

        $btn_link = "{\"button\":[{\"name\":\"예약정보\",\"type\":\"WL\",\"url_pc\":\"\",\"url_mobile\":\"https://customer.banjjakpet.com/allim/reserve_info?payment_log_seq=".$payment_idx."\"}]}";

        $data = array('cellphone'=>$cellphone,'message'=>$message,'tem_code'=>$tem_code,'btn_link'=>$btn_link);

        $data_json = json_encode($data);

        $result = $api -> post('/partner/allim/send',$data_json);

        $return_data = array("code"=>"000000","data"=>$data);


    }else if($r_mode === 'deposit_allim'){

        $cellphone = $_POST['cellphone'];
        $message = $_POST['message'];
        $tem_code = "1000004530_20016_2";
        $btn_link = '';

        $data = array(

            'cellphone'=>$cellphone,
            'message'=>$message,
            'tem_code'=>$tem_code,
            'btn_link'=>$btn_link
        );

        $data_json = json_encode($data);
        $result = $api ->post('/partner/allim/send',$data_json);

        $return_date = array("code"=>"000000","data"=>$result);
    }
}

function resizeImage($file, $newfile) {
    $w = 0;
    $h = 0;
    list($width, $height) = getimagesize($file); // 업로드 파일의 가로세로 구하기
    if($width > 1080){ // 가로가 1280보다 크면
        $w = 1080;
        $h = 1080*($height/$width); // 가로 기준으로 세로 비율 구하기
    }else if($height > 1920){ // 세로가 1920보다 크면
        $h = 1920;
        $w = 1920*($width/$height); // 세로 기준으로 가로 비율 구하기
    }
    if(strpos(strtolower($file), ".jpg"))
        $src = imagecreatefromjpeg($file);
    else if(strpos(strtolower($file), ".png"))
        $src = imagecreatefrompng($file);
    else if(strpos(strtolower($file), ".gif"))
        $src = imagecreatefromgif($file);
    $dst = imagecreatetruecolor($w, $h);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
    // 이미지 회전
    if (function_exists('exif_read_data')) {
        $exif = exif_read_data($newfile);
        if ($exif && isset($exif['Orientation'])) {
            $orientation = $exif['Orientation'];
            if ($orientation != 1) {
                $deg = 0;
                switch ($orientation) {
                    case 3:
                        $deg = 180;
                        break;
                    case 6:
                        $deg = 270;
                        break;
                    case 8:
                        $deg = 90;
                        break;
                }
                if ($deg) {
                    $dst = imagerotate($dst, $deg, 0);
                }
            } // if there is some rotation necessary
        } // if have the exif orientation info
    } // if function exists
    if(strpos(strtolower($newfile), ".jpg"))
        imagejpeg($dst, $newfile);
    else if(strpos(strtolower($newfile), ".png"))
        imagepng($dst, $newfile);
    else if(strpos(strtolower($newfile), ".gif"))
        imagegif($dst, $newfile);
}




echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
?>
