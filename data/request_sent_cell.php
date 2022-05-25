<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

$user_id = $_SESSION['gobeauty_user_id'];

$sql = "SELECT a.*, b.name from tb_customer AS a 
        left JOIN tb_shop AS b ON a.id = b.customer_id
        WHERE a.id = '".$user_id."'
";
$result = mysqli_query($connection, $sql);
$row = mysqli_fetch_assoc($result);

if($result) {
    $sql_2 = "select * from tb_item_rental where customer_id = '".$user_id."' and product_no = 'sent_cell_service'";
    $result_2 = mysqli_query($connection, $sql_2);
    $cnt = mysqli_num_rows($result_2);
    if($cnt > 0){
        echo "aready";
    }else{
        $sql_3 = "
            INSERT INTO `tb_item_rental` (`product_no`, `customer_id`, `name`, `cellphone`, `persnal_info_agree`, `data_give_finish`, `reg_date`) 
            VALUES ('sent_cell_service', '".$user_id."', '".$row['name']."', '".$row['cellphone']."', '1', '0', NOW());
        ";
        $result_3 = mysqli_query($connection, $sql_3);
        if($result_3){
            echo "success";
        }else{
            echo "fail";
        }
    }
}else{
    echo "fail";
}
?>
