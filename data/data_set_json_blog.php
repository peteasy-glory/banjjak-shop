<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

$return = array();

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

if($user_id != null && $user_id != ""){
    $list = $_POST["list"];

    foreach($list as $key => $data){
        $linkStr = addslashes($data["link"]);
        $titleStr = addslashes($data["title"]);
        $descStr = addslashes($data["desc"]);
        $thumbStr = addslashes($data["thumb"]);
        $seqStr = addslashes($data["blog_seq"]);
        $modeStr = addslashes($data["mode"]);
        $delYnStr = addslashes($data["del_yn"]);
        $blogger_name = addslashes($data["blogger_name"]);
        $post_date = $data["post_date"];

        $query = "";
        if($seqStr == 0 && $modeStr == "write"){
            $query = "INSERT INTO `tb_blog`(`customer_id`, `link`, `title`, `desc`, `thumbnail`, `post_date`, `blogger_name`) 
                VALUES('{$user_id}', '{$linkStr}', '{$titleStr}', '{$descStr}', '{$thumbStr}', '{$post_date}', '{$blogger_name}')";
        }else if($seqStr > 0 && $modeStr == "update"){
            $query = "UPDATE tb_blog 
                SET `del_yn` ='{$delYnStr}', `link` = '{$linkStr}', `title` = '{$titleStr}', 
                    `desc` = '{$descStr}', `thumbnail` = '{$thumbStr}', `post_date` = '{$post_date}', `blogger_name` = '{$blogger_name}',
                    `update_date` = CURRENT_TIMESTAMP
                WHERE `blog_seq` = '{$seqStr}'";
        }else if($seqStr > 0 && $modeStr == "delete"){
            $query = "UPDATE tb_blog SET `del_yn` = 'Y', `update_date` = CURRENT_TIMESTAMP WHERE `blog_seq` = '{$seqStr}'";
        }

        mysqli_query($connection, $query);
    }

    $return["result"] = true;
}else{
    $return["result"] = false;
}

echo json_encode($return, JSON_UNESCAPED_UNICODE);

?>