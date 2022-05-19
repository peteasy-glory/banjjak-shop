<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$user_id = $_SESSION['gobeauty_user_id'];

//print_r($_POST);
$other_worktime = array(10,11,12,13,14);

$que = "SELECT COUNT(*) AS cnt FROM tb_product_dog_worktime WHERE artist_id = '{$user_id}'";
//echo $que."<br>";
$res = mysqli_query($connection, $que);
$rs = mysqli_fetch_assoc($res);
//print_r($rs);
if($rs['cnt']>0){
    $sql  = "UPDATE tb_product_dog_worktime SET ";
    for($i=1;$i<=9;$i++) {
        if(empty($_POST['check'.$i])){
            $_POST['check'.$i] = 'n';
        }
        $sql .= "worktime{$i}_disp_yn = '{$_POST['check'.$i]}', ";
    }
    for($i=0;$i<count($_POST['check_add']);$i++){
        //echo $_POST['check_add'][$i]."<br>";
        //if($_POST['beauty_add'][$i] != '') {
            $sql .= "worktime{$other_worktime[$i]} = '{$_POST['beauty_add_time'][$i]}', ";
            $sql .= "worktime{$other_worktime[$i]}_title = '{$_POST['beauty_add'][$i]}', ";
            $sql .= "worktime{$other_worktime[$i]}_disp_yn = '{$_POST['check_add'][$i]}', ";
        //}
    }
    if(empty(count($_POST['check_add']))){
        $sql .= "worktime11_disp_yn = 'n', ";
        $sql .= "worktime12_disp_yn = 'n', ";
        $sql .= "worktime13_disp_yn = 'n', ";
        $sql .= "worktime14_disp_yn = 'n', ";
        $sql .= "worktime10_disp_yn = 'n', ";
    }
    $sql .= "update_dt = NOW() ";
    $sql .= " WHERE artist_id = '{$user_id}' ";
    //echo $sql."<br>";
    $set_res = mysqli_query($connection, $sql);
} else {
    $sql  = "INSERT INTO tb_product_dog_worktime SET ";
    $sql .= "artist_id = '{$user_id}', ";
    for($i=1;$i<=9;$i++) {
        if(empty($_POST['check'.$i])){
            $_POST['check'.$i] = 'n';
        }
        $sql .= "worktime{$i}_disp_yn = '{$_POST['check'.$i]}', ";
    }
    //추가된 미용구분에 대한 쿼리 생성
    for($i=0;$i<count($_POST['check_add']);$i++){
        $sql .= "worktime{$other_worktime[$i]} = '{$_POST['beauty_add_time'][$i]}', ";
        $sql .= "worktime{$other_worktime[$i]}_title = '{$_POST['beauty_add'][$i]}', ";
        $sql .= "worktime{$other_worktime[$i]}_disp_yn = '{$_POST['check_add'][$i]}', ";
    }
    $sql .= "reg_dt = NOW() ";
    //echo $sql."<br>";
    $set_res = mysqli_query($connection, $sql);
}


?>
<script>
    location.href = '../set_beauty_management_division';
</script>
