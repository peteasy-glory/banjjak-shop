<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

    $artist_id = $_POST['id'];
    $json['flag'] = true;
    if($artist_id == '') {
        $json['flag'] = false;
        echo json_encode($json);
        exit;
    } else {
        $sql = "SELECT id FROM tb_customer WHERE id = '{$artist_id}' AND (my_shop_flag = 0 AND admin_flag = 0 AND artist_flag = 0)";
        //echo $sql;
        $rs = sql_fetch_array($sql);
        if(!empty($rs[0]['id'])){
            $json['id'] = $rs[0]['id'];
            echo json_encode($json);
            exit;
        } else {
            $json['flag'] = false;
            echo json_encode($json);
            exit;
        }
    }
?>

