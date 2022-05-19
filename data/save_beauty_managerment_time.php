<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$user_id = $_SESSION['gobeauty_user_id'];

//print_r($_POST);



$sql  = "UPDATE tb_product_dog_worktime SET ";

for($i=1;$i<=14;$i++) {
    if($_POST['worktime'.$i] != '') {
        $sql .= "worktime{$i} = '" . $_POST['worktime'.$i] . "', ";
    }
}

$sql .= "update_dt = NOW() ";
$sql .= " WHERE artist_id = '{$user_id}' ";
//echo $sql."<br>";
$set_res = sql_query($sql);
$returl_url = 'set_beauty_management_time';
if($_POST['backurl'])   $returl_url = $_POST['backurl'];


?>
<script>
    alert('저장되었습니다.');
     parent.location.href = '../<?php echo $returl_url;?>';
</script>
