<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$user_id = $_SESSION['gobeauty_user_id'];

$clear = array();
if(ctype_alpha($_POST['mode'])){
    $clear['mode'] = $_POST['mode'];
}

switch($clear['mode']){
    case 'beautyStoreAdd'://상품 등록시
        $product_type = $_POST['product_type'];

//        print_r($_POST);

        $good1_cnt = count($_POST['good1_name']);
        if($good1_cnt>0){
            $sql = "DELETE FROM tb_product_dog_etc WHERE customer_id = '{$user_id}' AND product_kind = 1";
            sql_query($sql);
        }
        for($i=0;$i<$good1_cnt;$i++){
            if(!empty($_POST['good1_name'][$i])) {
                $sql = "INSERT INTO tb_product_dog_etc SET ";
                $sql .= "customer_id    = '{$user_id}', ";
                $sql .= "product_kind   = '1', ";
                $sql .= "name           = '{$_POST['good1_name'][$i]}', ";
                $sql .= "price          = '{$_POST['good1_price'][$i]}', ";
                $sql .= "update_time    = NOW() ";
                //echo $sql . "<br>";
                sql_query($sql);
            }
        }

        $good2_cnt = count($_POST['good2_name']);
        if($good2_cnt>0){
            $sql = "DELETE FROM tb_product_dog_etc WHERE customer_id = '{$user_id}' AND product_kind = 2";
            sql_query($sql);
        }
        for($i=0;$i<$good2_cnt;$i++){
            if(!empty($_POST['good2_name'][$i])) {
                $sql = "INSERT INTO tb_product_dog_etc SET ";
                $sql .= "customer_id    = '{$user_id}', ";
                $sql .= "product_kind   = '2', ";
                $sql .= "name           = '{$_POST['good2_name'][$i]}', ";
                $sql .= "price          = '{$_POST['good2_price'][$i]}', ";
                $sql .= "update_time    = NOW() ";
                //echo $sql . "<br>";
                sql_query($sql);
            }
        }

        //사료
        $good3_cnt = count($_POST['good3_name']);
        if($good3_cnt>0){
            $sql = "DELETE FROM tb_product_dog_etc WHERE customer_id = '{$user_id}' AND product_kind = 3";
            sql_query($sql);
        }
        for($i=0;$i<$good3_cnt;$i++){
            if(!empty($_POST['good3_name'][$i])) {
                $sql = "INSERT INTO tb_product_dog_etc SET ";
                $sql .= "customer_id    = '{$user_id}', ";
                $sql .= "product_kind   = '3', ";
                $sql .= "name           = '{$_POST['good3_name'][$i]}', ";
                $sql .= "price          = '{$_POST['good3_price'][$i]}', ";
                $sql .= "update_time    = NOW() ";
                //echo $sql . "<br>";
                sql_query($sql);
            }
        }

        //기타
        $good4_cnt = count($_POST['good4_name']);
        if($good4_cnt>0){
            $sql = "DELETE FROM tb_product_dog_etc WHERE customer_id = '{$user_id}' AND product_kind = 4";
            sql_query($sql);
        }
        for($i=0;$i<$good4_cnt;$i++){
            if(!empty($_POST['good4_name'][$i])) {
                $sql = "INSERT INTO tb_product_dog_etc SET ";
                $sql .= "customer_id    = '{$user_id}', ";
                $sql .= "product_kind   = '4', ";
                $sql .= "name           = '{$_POST['good4_name'][$i]}', ";
                $sql .= "price          = '{$_POST['good4_price'][$i]}', ";
                $sql .= "update_time    = NOW() ";
                //echo $sql . "<br>";
                sql_query($sql);
            }
        }



        echo "<script>location.href = '../set_beauty_store_add';</script>";
    break;

    //데이터 한개 삭제
    case 'deleteOne':
        $json['flag'] = true;
        $clear = array();
        if(ctype_alnum($_POST['no'])){
            $clear['no'] = $_POST['no'];
        }
        $que = "UPDATE tb_coupon SET del_yn = 'Y' WHERE coupon_seq = {$clear['no']}";
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

