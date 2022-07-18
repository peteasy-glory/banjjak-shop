<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$user_id = $_SESSION['gobeauty_user_id'];

$clear = array();
if(ctype_alnum($_POST['mode'])){
    $clear['mode'] = $_POST['mode'];
}


switch($clear['mode']){
    case 'dogDelOne'://강아지 미용 삭제
        $json['flag'] = true;
        $data = explode('|',$_POST['type']);
        $que = "DELETE FROM tb_product_dog_static WHERE customer_id = '{$user_id}' AND first_type = '{$data[0]}' AND second_type = '{$data[1]}' AND direct_title = '{$data[2]}'";
        //echo $que."<p>";
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
            echo json_encode($json);
            exit;
        }
        echo json_encode($json);
        break;
    case 'catDel'://고양이 미용 삭제
        $json['flag'] = true;
        $data = explode('|',$_POST['type']);
        $que = "DELETE FROM tb_product_cat WHERE customer_id = '{$user_id}' AND first_type = '{$data[0]}' AND second_type = '{$data[1]}'";
        //echo $que."<p>";
        $res = sql_query($que);
        if(!$res){
            $json['flag'] = false;
            echo json_encode($json);
            exit;
        }
        echo json_encode($json);
        break;
}



?>
