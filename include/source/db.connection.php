<?php


    $connection = mysqli_connect("175.126.123.165","gobeautypet","pebjjdb!0901$") or die("서버 점검중 입니다.");
    mysqli_select_db($connection,"gobeautypet");
    mysqli_query($connection, "SET NAMES utf8");
  
    
    //var_dump($connection);

   // $sql="select * from tb_customer where id = 'pettester@peteasy.kr'    enable_flag = 1;";
   // $result = mysqli_query($connection, $sql);
   // echo $result;



?>
