<?php
define("GOOGLE_SERVER_KEY", "yourserverkey");

include "../include/db_connection.php";

function all_push($arr_userapikey,$title,$memo,$path,$image){
//          $API_ACCESS_KEY= 'AAAAKR8K-yk:APA91bFGTYpY4e0uOZw1IfOmyMc9dQQlDfsXCWKUAkoJBMPudzEdXYuXJVHgkZrmXp8ikj0qKrtb8rV63-jcgCMsEiZaCdwc1bCUyiSrCsayIdcEkFhS29Ok5zK559Bh8c9rYrA-T5cY';

//          $postJSONData = array(
// //                'registration_ids' => $arr_userapikey,
//                  'content_available' => true,
//                 'priority' =>'high',
//                 'notification' => array(
//                         'title' => $title,
//                         'body' =>$memo,
//                         'count' =>'0',
//                         'sound' =>'default',
// 			'image' => $image,
//                         'path' => $path
//                 ),
//                  'data'  => array(
//                         'title' =>$title,
//                          'msg' =>$memo,
//                          'count' =>'0',
//                          'path' =>$path,
// 			'image' => $image
//                  )
//          );

//         if ($arr_userapikey != null && $arr_userapikey != "") {
//                 if(is_array($id)) {
//                         $postJSONData['registration_ids'] = $arr_userapikey;
//                 } else {
//                         $postJSONData['to'] = $arr_userapikey;
//                 }
//         }

//          $headers = array
//          (
//                 'Authorization: key=' .$API_ACCESS_KEY,
//                 'Content-Type: application/json'
//          );

// 	$request_msg =  json_encode( $postJSONData );
// 	echo $request_msg."<br>";
//          $ch = curl_init();
//          curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
//          curl_setopt( $ch,CURLOPT_POST, true );
//          curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
//          curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
//          curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
//          curl_setopt( $ch,CURLOPT_POSTFIELDS, $request_msg );
//          $result = curl_exec($ch );
//          curl_close( $ch );

//          return $result;

 }


function send_fcm($message, $id) {
// $url = 'https://fcm.googleapis.com/fcm/send';

// $headers = array (
// 'Authorization: key='.'AAAAKR8K-yk:APA91bFGTYpY4e0uOZw1IfOmyMc9dQQlDfsXCWKUAkoJBMPudzEdXYuXJVHgkZrmXp8ikj0qKrtb8rV63-jcgCMsEiZaCdwc1bCUyiSrCsayIdcEkFhS29Ok5zK559Bh8c9rYrA-T5cY',
// 'Content-Type: application/json'
// );

// $fields = array ( 
// 'data' => array ("message" => $message),
// 'notification' => array ("body" => $message)	
// );

// if ($id != null && $id != "") {
// 	if(is_array($id)) {
// 		$fields['registration_ids'] = $id;
// 	} else {
// 		$fields['to'] = $id;
// 	}
// }

// $fields['priority'] = "high";

// $fields = json_encode ($fields);
// echo $fields."<br>";
// $ch = curl_init ();
// curl_setopt ( $ch, CURLOPT_URL, $url );
// curl_setopt ( $ch, CURLOPT_POST, true );
// curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
// curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
// curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

// $result = curl_exec ( $ch );
// if ($result === FALSE) {
// //die('FCM Send Error: ' . curl_error($ch));
// } 
// curl_close ( $ch );
// return $result;
// }
// //$customer_id = "pickmon@pickmon.com";
// $customer_id = "sheepoi@naver.com";
// $sql = "select token from tb_customer where id = '".$customer_id."';";
// $result = mysql_query($sql);
// $tokens = "c341OyPSDnE:APA91bF7icI15I2qtj_8CtqKQA8t24er7DevdclmJU_UAEyK120eB8EPU6DtUHhroBX142eJRnWkaVIG9cm6mJQNlbJpZ_Q-OAXvzuKqBedCr4EAy8zmAAniR5cXixEeS9jwVRtEHNfr";
// while ($rows = mysql_fetch_object($result)) {
// 	$tokens = $rows->token;
// 	echo $tokens."<br>";
// }
// $wr_subject = 'gogogogogogo movemove.';
// //$tokens = 'eVv2k7Q1oe0:APA91bGsRNl7VcvHDbmTC80-C1CXjWH8VCi8bzBJvTsp0wr19v6XDfYYfgKDY6yJ9c81UHhZHzbsGN6ZIM86f3TBNunvASKj5216eFIyD5KDbB1bbAhUpw6dfsMfNT-iip4LoxilCNP6';
// //$rt = send_fcm($wr_subject, "/topics/global");
// //$rt = send_fcm($wr_subject, $tokens);
// //echo $rt;
// //$arr_userapikey,$title,$memo,$path,$image
// //$rt = all_push ($tokens, "푸시테스트타이틀", "푸시테스트메모입니다. 문장이 들어갑니다. 끝내줘요.", "http://gobeauty.kr/start_notice/", "http://gobeauty.kr/images/logo_login.jpg");
// //echo $rt;

?>
