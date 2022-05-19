<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");


$crypto = new Crypto();

$return_data = array();
$return_data['post'] = $_POST;
$return_data['get'] = $_GET;
if ( !is_null($return_data['get']) ) {
    $sql = " SELECT 
            pet.*
        FROM 
            tb_pet_type pet
        WHERE 
            pet.type = '{$_GET['type']}'
            AND pet.enable_flag=1 ORDER BY type_seq ASC, name ASC
    ";    
    if ( $result = mysqli_query($connection,$sql) ) {
        if ( $rows = mysqli_num_rows($result) ) {
            $return_data['rows'] = $rows;
            while ( $row = mysqli_fetch_assoc($result) ) {
                $return_data['row'][] = $row;
            }
        } else {
            $return_data['rows'] = $rows;
            $return_data['row'] = null;
        }
        $return_data['res'] = true;
    } else {
        $return_data['error'] = mysqli_error($connection);
        $return_data['errno'] = mysqli_errno($connection);
        $return_data['res'] = false;
        
    }
} else {
    $return_data['error'] = '전달값이 존재하지 않습니다.';
    $return_data['res'] = false;
}


echo json_encode($return_data);

?>

