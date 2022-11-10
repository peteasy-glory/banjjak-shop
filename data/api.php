<?php
include($_SERVER['DOCUMENT_ROOT'] . "/include/global.php");
include($_SERVER['DOCUMENT_ROOT'] . "/common/TRestAPI.php");
include($_SERVER['DOCUMENT_ROOT']."/common/TEmoji.php");
$emoji = new TEmoji();

$user_id = (isset($_SESSION['gobeauty_user_id'])) ? $_SESSION['gobeauty_user_id'] : "";
$user_name = (isset($_SESSION['gobeauty_user_nickname'])) ? $_SESSION['gobeauty_user_nickname'] : "";





//$api = new TRestAPI("https://partnerapi.banjjakpet.com:8080","Token 2156d1824c98f27a1f163a102cf742002b15e624");
$api = new TRestAPI("http://stg-partnerapi.banjjakpet.com:8080","Token 3bc516d50d61f7c52f5da64b753a2488a02bd67c");
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
    }else if($r_mode === 'get_part_time'){

        $artist_id = $_POST['id'];
        $result = $api->get('/partner/setting/part-time/'.$artist_id);

        $return_data = array("code"=>"000000","data"=>$result);

    }else if($r_mode === 'get_allimi_history'){

        $artist_id = $_POST['artist_id'];
        $cellphone = $_POST['cellphone'];
        $pet_seq = $_POST['pet_seq'];

        $data = array('artist_id'=>$artist_id,'cellphone'=>$cellphone,'pet_seq'=>$pet_seq);

        $data_json = json_encode($data);

        $result = $api -> get('/partner/reserve/diary-history',$data_json);

        $return_data = array("code"=>"000000","data"=>$result);

    }else if($r_mode === "pet_info"){


        $pet_seq = $_POST['pet_seq'];

        $pet_info = $api->get('/partner/booking/pet/'.$pet_seq);

        $return_data = array("code"=>"000000","data"=>$pet_info);
    }else if($r_mode === 'post_allimi'){

        $payment_log_seq = $_POST['payment_log_seq'];
        $artist_id = $_POST['artist_id'];
        $pet_seq = $_POST['pet_seq'];
        $cellphone = $_POST['cellphone'];
        $etiquette_1 = $_POST['etiquette_1'];
        $etiquette_2 = $_POST['etiquette_2'];
        $etiquette_3 = $_POST['etiquette_3'];
        $etiquette_etc = $_POST['etiquette_etc'];
        $etiquette_etc_memo = $_POST['etiquette_etc_memo'];
        $condition_1 = $_POST['condition_1'];
        $condition_2 = $_POST['condition_2'];
        $condition_3 = $_POST['condition_3'];
        $condition_etc = $_POST['condition_etc'];
        $condition_etc_memo = $_POST['condition_etc_memo'];
        $tangles_1 = $_POST['tangles_1'];
        $tangles_2 = $_POST['tangles_2'];
        $tangles_3 = $_POST['tangles_3'];
        $tangles_4 = $_POST['tangles_4'];
        $tangles_5 = $_POST['tangles_5'];
        $tangles_6 = $_POST['tangles_6'];
        $tangles_7 = $_POST['tangles_7'];
        $tangles_etc = $_POST['tangles_etc'];
        $tangles_etc_memo = $_POST['tangles_etc_memo'];
        $part_1 = $_POST['part_1'];
        $part_2 = $_POST['part_2'];
        $part_3 = $_POST['part_3'];
        $part_4 = $_POST['part_4'];
        $part_5 = $_POST['part_5'];
        $part_6 = $_POST['part_6'];
        $part_etc = $_POST['part_etc'];
        $part_etc_memo = $_POST['part_etc_memo'];
        $skin_1 = $_POST['skin_1'];
        $skin_2 = $_POST['skin_2'];
        $skin_3 = $_POST['skin_3'];
        $skin_4 = $_POST['skin_4'];
        $skin_5 = $_POST['skin_5'];
        $skin_6 = $_POST['skin_6'];
        $skin_7 = $_POST['skin_7'];
        $skin_etc = $_POST['skin_etc'];
        $skin_etc_memo = $_POST['skin_etc_memo'];
        $bath_1 = $_POST['bath_1'];
        $bath_2 = $_POST['bath_2'];
        $bath_3 = $_POST['bath_3'];
        $bath_etc = $_POST['bath_etc'];
        $bath_etc_memo = $_POST['bath_etc_memo'];
        $notice_1 = $_POST['notice_1'];
        $notice_2 = $_POST['notice_2'];
        $notice_3 = $_POST['notice_3'];
        $notice_4 = $_POST['notice_4'];
        $notice_etc = $_POST['notice_etc'];
        $notice_etc_memo = $_POST['notice_etc_memo'];
        $file_path = $_POST['file_path'];


        $data = array(
            'payment_log_seq'=>intval($payment_log_seq),
            'artist_id'=>$artist_id,
            'pet_seq'=>intval($pet_seq),
            'cellphone'=>$cellphone,
            'etiquette_1'=>intval($etiquette_1),
            'etiquette_2'=>intval($etiquette_2),
            'etiquette_3'=>intval($etiquette_3),
            'etiquette_etc'=>intval($etiquette_etc),
            'etiquette_etc_memo'=>$etiquette_etc_memo,
            'condition_1'=>intval($condition_1),
            'condition_2'=>intval($condition_2),
            'condition_3'=>intval($condition_3),
            'condition_etc'=>intval($condition_etc),
            'condition_etc_memo'=>$condition_etc_memo,
            'tangles_1'=>intval($tangles_1),
            'tangles_2'=>intval($tangles_2),
            'tangles_3'=>intval($tangles_3),
            'tangles_4'=>intval($tangles_4),
            'tangles_5'=>intval($tangles_5),
            'tangles_6'=>intval($tangles_6),
            'tangles_7'=>intval($tangles_7),
            'tangles_etc'=>intval($tangles_etc),
            'tangles_etc_memo'=>$tangles_etc_memo,
            'part_1'=>intval($part_1),
            'part_2'=>intval($part_2),
            'part_3'=>intval($part_3),
            'part_4'=>intval($part_4),
            'part_5'=>intval($part_5),
            'part_6'=>intval($part_6),
            'part_etc'=>intval($part_etc),
            'part_etc_memo'=>$part_etc_memo,
            'skin_1'=>intval($skin_1),
            'skin_2'=>intval($skin_2),
            'skin_3'=>intval($skin_3),
            'skin_4'=>intval($skin_4),
            'skin_5'=>intval($skin_5),
            'skin_6'=>intval($skin_6),
            'skin_7'=>intval($skin_7),
            'skin_etc'=>intval($skin_etc),
            'skin_etc_memo'=>$skin_etc_memo,
            'bath_1'=>intval($bath_1),
            'bath_2'=>intval($bath_2),
            'bath_3'=>intval($bath_3),
            'bath_etc'=>intval($bath_etc),
            'bath_etc_memo'=>$bath_etc_memo,
            'notice_1'=>intval($notice_1),
            'notice_2'=>intval($notice_2),
            'notice_3'=>intval($notice_3),
            'notice_4'=>intval($notice_4),
            'notice_etc'=>intval($notice_etc),
            'notice_etc_memo'=>$notice_etc_memo,
            'file_path'=>$file_path,
        );

        $data_json = json_encode($data);

        $result = $api -> post('/partner/reserve/diary',$data_json);

        $return_data = array("code"=>"000000","data"=>$result);
    }else if($r_mode === 'allimi_talk'){


        $cellphone = $_POST['cellphone'];
        $message = $_POST['message'];
        $payment_log_seq = $_POST['payment_log_seq'];
        $tem_code = '1000004530_20018';
        $btn_link = "{\"button\":[{\"name\":\"알리미 보기\",\"type\":\"WL\",\"url_pc\":\"\",\"url_mobile\":\"https://customer.banjjakpet.com/allim/diary_info?payment_log_seq=".$payment_log_seq."\"}]}";

        $data = array(
            'cellphone'=>$cellphone,
            'message'=>$message,
            'tem_code'=>$tem_code,
            'btn_link'=>$btn_link,
        );

        $data_json = json_encode($data);

        $result = $api ->post('/partner/allim/send',$data_json);

        $return_data = array("code"=>"000000","data"=>$result);

    }else if($r_mode === 'get_shop_info_2'){

        $artist_id = $_POST['artist_id'];

        $result = $api -> get('/partner/shop/info/'.$artist_id);

        $return_data = array("code"=>"000000","data"=>$result);
    }else if($r_mode === 'allimi_recent'){

        $artist_id=$_POST['artist_id'];
        $cellphone = $_POST['cellphone'];

        $data = array(
            'artist_id'=>$artist_id,
            'cellphone'=>$cellphone
        );
        $data_json = json_encode($data);

        $result = $api -> get('/partner/reserve/diary-recent',$data_json);

        $return_data = array("code"=>"000000","data"=>$result);
    }else if($r_mode === 'get_diary_date'){

        $artist_id = $_POST['artist_id'];
        $cellphone = $_POST['cellphone'];

        $data = array(
            'artist_id'=>$artist_id,
            'cellphone'=>$cellphone,
        );
        $data_json = json_encode($data);

        $result = $api -> get ('/partner/reserve/diary-list',$data_json);

        $return_data = array("code"=>"000000","data"=>$result);
    }else if($r_mode === 'get_diary_date_pet') {
        $artist_id = $_POST['artist_id'];
        $cellphone = $_POST['cellphone'];

        $date = $_POST['date'];

        $data = array(
            'artist_id' => $artist_id,
            'cellphone' => $cellphone,
            'date' => $date
        );

        $data_json = json_encode($data);

        $result = $api->get('/partner/reserve/diary-select', $data_json);

        $return_data = array("code" => "000000", "data" => $result);
    }else if($r_mode === "get_allimi"){

        $payment_log_seq = $_POST['payment_log_seq'];

        $result = $api ->get('/partner/reserve/diary/'.$payment_log_seq);

        $return_data = array("code"=>"000000","data"=>$result);


    }else if($r_mode === "beauty_gal_get"){

        $pet_seq = $_POST['idx'];

        $artist_id = $_POST['artist_id'];

        $data = array('artist_id'=>$artist_id);
        $data_json = json_encode($data);

        $result = $api ->get('/partner/booking/beauty-gallery/'.$pet_seq,$data_json);

        $return_data = array("code"=>"000000","data"=>$result);


    }else if($r_mode === "beauty_gal_add"){

        $payment_log_seq = ($_POST['payment_log_seq'] != '')? $_POST['payment_log_seq'] : 0;
        $partner_id = $_POST['login_id'];
        $pet_seq = $_POST['pet_seq'];
        $prnt_title = $_POST['prnt_title'];
        $mime = $_POST['mime'];

        $image = $_FILES['image']['tmp_name'];
        $base_img = base64_encode(file_get_contents($image));

        $data = array(
            payment_log_seq=>intval($payment_log_seq),
            partner_id=>$partner_id,
            pet_seq=>intval($pet_seq),
            prnt_title=>$prnt_title,
            mime=>$mime,
            image=>$base_img);
        $data_json = json_encode($data);

        $result = $api -> post('/partner/booking/beauty-gallery' ,$data_json);

        $return_data = array("code"=>"000000","data"=>$result);
    }else if($r_mode === "db_to_str"){

        $str = $_POST['str'];

        $str = $emoji->emojiDBToStr($str);

        $return_data = array("code"=>"000000",'data'=>$str);

    }else if($r_mode === "str_to_db"){

        $str = $_POST['str'];

        $str = $emoji->emojiStrToDB($str);

        $return_data = array("code"=>"000000",'data'=>$str);
    }else if($r_mode ==='get_hotel_product'){


        $artist_id = $_POST['artist_id'];

        $result = $api ->get('/partner/setting/hotel-product/'.$artist_id);

        $return_data = array("code"=>"000000",'data'=>$result);
    }else if($r_mode ==='get_coupon'){

        $artist_id = $_POST['artist_id'];
        $service_type = $_POST['service_type'];

        $data = array('service_type'=>$service_type);

        $data_json = json_encode($data);

        $result = $api -> get('/partner/setting/coupon/'.$artist_id,$data_json);

        $return_data = array("code"=>"000000",'data'=>$result);


    }else if($r_mode ==='get_hotel_info'){

        $artist_id = $_POST['artist_id'];

        $result = $api ->get('/partner/setting/hotel/'.$artist_id);

        $return_data = array("code"=>"000000",'data'=>$result);
    }else if($r_mode === 'delete_all_hotel_product'){

        $artist_id = $_POST['artist_id'];
        $hp_seq = $_POST['hp_seq'];
        $del_msg = $artist_id.'|판매상품 관리에서 직접 삭제';

        $data = array(
            'hp_seq'=>intval($hp_seq),
            'del_msg'=>$del_msg
        );

        $data_json = json_encode($data);
        $result = $api -> delete('/partner/setting/hotel-product',$data_json);

        $return_data = array("code"=>"000000",'data'=>$result);

    }else if($r_mode ==='get_file_img'){

        $f_seq = $_POST['f_seq'];

        $sql ="

            SELECT file_path FROM tb_file WHERE f_seq =".$f_seq."
            ";

        $result = sql_query($sql);
        $row = sql_fetch($result);

        $return_data = array("code"=>"000000","data"=>$row);
    }else if($r_mode === 'post_set_hotel_product'){

        $h_seq = $_POST['h_seq'];
        $partner_id = $_POST['partner_id'];
        $room_pet_type = $_POST['room_pet_type'];
        $room_name = $_POST['room_name'];
        $room_cnt = $_POST['room_cnt'];
        $weight = $_POST['weight'];
        $normal_price = $_POST['normal_price'];
        $peak_price = $_POST['peak_price'];
        $is_neutral = $_POST['is_neutral'];
        $is_neutral_pay = $_POST['is_neutral_pay'];
        $neutral_price = $_POST['neutral_price'];
        $extra_price = $_POST['extra_price'];
        $is_peak = $_POST['is_peak'];
        $is_image = $_POST['is_image'];
        $comment = $_POST['comment'];
        $image = $_POST['image'];

        $data = array(
            "h_seq"=>intval($h_seq),
            "partner_id"=>$partner_id,
            "room_pet_type"=>$room_pet_type,
            "room_name"=>$room_name,
            "room_cnt"=>intval($room_cnt),
            "weight"=>$weight,
            "normal_price"=>$normal_price,
            "peak_price"=>$peak_price,
            "is_neutral"=>$is_neutral,
            "is_neutral_pay"=>$is_neutral_pay,
            "neutral_price"=>intval($neutral_price),
            "extra_price"=>intval($extra_price),
            "is_peak"=>$is_peak,
            "is_image"=>$is_image,
            "comment"=>$comment,
            "image"=>$image


        );

        $data_json = json_encode($data);

        $result = $api -> post('/partner/setting/hotel-product',$data_json);


        $return_data = array("code"=>"000000",'data'=>$result);

    }else if($r_mode === 'put_set_hotel_product'){


        $hp_seq = $_POST['hp_seq'];
        $room_name = $_POST['room_name'];
        $room_cnt = $_POST['room_cnt'];
        $weight = $_POST['weight'];
        $normal_price = $_POST['normal_price'];
        $sort = $_POST['sort'];
        $peak_price = $_POST['peak_price'];
        $is_neutral = $_POST['is_neutral'];
        $is_neutral_pay = $_POST['is_neutral_pay'];
        $neutral_price = $_POST['neutral_price'];
        $extra_price = $_POST['extra_price'];
        $is_peak = $_POST['is_peak'];
        $is_image = $_POST['is_image'];
        $comment = $_POST['comment'];
        $image = $_POST['image'];

        $data = array(
            "hp_seq"=>intval($hp_seq),
            "room_name"=>$room_name,
            "room_cnt"=>intval($room_cnt),
            "weight"=>$weight,
            "normal_price"=>$normal_price,
            "sort"=>intval($sort),
            "peak_price"=>$peak_price,
            "is_neutral"=>$is_neutral,
            "is_neutral_pay"=>$is_neutral_pay,
            "neutral_price"=>intval($neutral_price),
            "extra_price"=>intval($extra_price),
            "is_peak"=>$is_peak,
            "is_image"=>$is_image,
            "comment"=>$comment,
            "image"=>$image


        );

        $data_json = json_encode($data);

        $result = $api -> put('/partner/setting/hotel-product',$data_json);


        $return_data = array("code"=>"000000",'data'=>$result);


    }else if($r_mode ==='delete_set_hotel_product'){
        $hp_seq = $_POST['hp_seq'];
        $del_msg = '상품저장시 삭제..';

        $data = array("hp_seq"=>intval($hp_seq),"del_msg"=>$del_msg);

        $data_json = json_encode($data);

        $result = $api -> delete('/partner/setting/hotel-product',$data_json);

        $return_data = array("code"=>"000000","data"=>$result);

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
