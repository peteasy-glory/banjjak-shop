<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$user_id = $_SESSION['gobeauty_user_id'];

for ($index = 0; list($key, $value) = each($_POST);) {
    if (Is_numeric($key)) {
        if ($value == "true") {
            $select_sql = "select * from tb_working_region where customer_id = '" . $user_id . "' and region_id = " . $key . ";";
            $select_result = mysqli_query($connection,$select_sql);
            if ($shop_datas = mysqli_fetch_object($select_result)) {
            } else {
                $sql = "insert into tb_working_region (customer_id, region_id, update_time) values ('" . $user_id . "', " . $key . ", now());";
                //echo $sql;
                $result = mysqli_query($connection,$sql);
            }
        } else {
            $sql = "delete from tb_working_region where customer_id = '" . $user_id . "' and region_id = " . $key . ";";
            $result = mysqli_query($connection,$sql);
        }
    }
}

/*	$sql = "delete from tb_per_diem_zone where customer_id = '".$user_id."';";
	$result = mysqli_query($connection,$sql);
	$sql = "update tb_working_region set zone_id = null where customer_id = '".$user_id."';";
	$result = mysqli_query($connection,$sql);
*/

?>
<script>
	location.href = "../shop_bussiness_trip";
</script>