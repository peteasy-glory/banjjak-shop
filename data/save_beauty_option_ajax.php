<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$user_id = $_SESSION['gobeauty_user_id'];

//print_r($_POST);
$clear = array();
if(ctype_alpha($_POST['mode'])){
    $clear['mode'] = $_POST['mode'];
}

switch($clear['mode']){
    case 'save': // 안내사항 공통안내 저장
        $json['flag'] = true;
        $json['error'] = '';
        $que = "SELECT COUNT(*) AS cnt FROM tb_product_dog_static WHERE customer_id = '{$user_id}' AND first_type = '개' AND second_type = '기타공통'";
        $res = sql_query($que);
        $row = sql_fetch($res);
        if(!$row['cnt']){
            $sql  = "INSERT INTO tb_product_dog_static SET ";
            $sql .= "customer_id = '{$user_id}', ";
            $sql .= "first_type = '개', ";
            $sql .= "second_type = '기타공통', ";
            $sql .= "add_comment = '{$_POST['cmt']}', ";
            $sql .= "update_time = NOW() ";
            $res1 = sql_query($sql);
            if(!$res1){
                $json['flag'] = false;
                $json['error'] = '데이터 등록시 오류가 발생했습니다. 잠시 후 다시 시도해주세요.';
                echo json_encode($json);
                exit;
            }
        } else {
            $sql  = "UPDATE tb_product_dog_static SET ";
            $sql .= "add_comment = '{$_POST['cmt']}', ";
            $sql .= "update_time = NOW() ";
            $sql .= " WHERE customer_id = '{$user_id}' AND first_type = '개' AND second_type = '기타공통'";
            $res1 = sql_query($sql);
            if(!$res1){
                $json['flag'] = false;
                $json['error'] = '데이터 수정시 오류가 발생했습니다. 잠시 후 다시 시도해주세요.';
                echo json_encode($json);
                exit;
            }
        }
        echo json_encode($json);
    break;
}

?>
