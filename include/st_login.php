<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$customer_id = (isset($_POST['user_id']))? $_POST['user_id'] : "";

$login_sql = "select * from tb_customer where id = '".$customer_id."' and enable_flag = 1";
$res = mysqli_query($connection, $login_sql);
$row = mysqli_fetch_array($res);

//탈퇴 회원 일때
if($row["enable_flag"] != 1){
    //쿠키 전체 삭제
    $past = time() - 3600;
    foreach ($_COOKIE as $key => $value)
    {
        setcookie($key, '', $past, '/','.'.$_SERVER['HTTP_HOST'] );
    }

    session_destroy();
}else{
    $is_pc = (isset($_POST['is_pc']) && $_POST['is_pc'] === "true" ) ? true : false;

    $id = $row["id"];
    $artist_flag = $row["artist_flag"];
    $my_shop_flag = $row["my_shop_flag"];

    //정상 회원
    $_SESSION['gobeauty_user_id'] = $id;
    $_SESSION['gobeauty_user_nickname'] = $row["nickname"];
    $_SESSION['is_pc'] = $is_pc;
    $_SESSION['my_shop_flag'] = $my_shop_flag;
    $_SESSION['is_token'] = "1";

    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if ($user_agent) {
        $token_index = strpos($user_agent, "APP_GOPET_PARTNER_iOS");
        if ($token_index > 0) { ?>

            <script>
                window.webkit.messageHandlers.onAppLogin.postMessage('<?=$id?>');
                location.reload();
            </script>

        <?php	}
    }
    if ($user_agent) {
        $token_index = strpos($user_agent, "APP_GOPET_PARTNER_AND");
        if ($token_index > 0) { ?>

            <script>
                window.Android.onAppLogin('<?=$id?>');
                location.reload();
            </script>

        <?php	}
    }

    if($artist_flag == "1"){
        $artist_sql = "SELECT * FROM tb_shop_artist WHERE artist_id = '{$id}' AND del_yn = 'N'";
        $artist_result = mysqli_query($connection, $artist_sql);

        if($artist_data = mysqli_fetch_object($artist_result)){
            $_SESSION['artist_flag'] = true;
            $_SESSION['shop_user_id'] = $artist_data->customer_id;
            $_SESSION['shop_user_nickname'] = $artist_data->name;
        }
    }

    cookie_save($id,$master_key_name);
    $return_data = array("code" => "000000", "data" => "login");
}


echo json_encode($return_data, JSON_UNESCAPED_UNICODE);

?>