<?php

function app_push($arr_userapikey,$title,$memo,$path,$image){
        return [];
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
// 	//echo $request_msg."<br>";
//          $ch = curl_init();
//          curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
//          curl_setopt( $ch,CURLOPT_POST, true );
//          curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
//          curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
//          curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
//          curl_setopt( $ch,CURLOPT_POSTFIELDS, $request_msg );
//          $result = curl_exec($ch );
        //  curl_close( $ch );

        //  return $result;
}

//$rt = all_push ($tokens, "푸시테스트타이틀", "푸시테스트메모입니다. 문장이 들어갑니다. 끝내줘요.", "http://gobeauty.kr/mainpage/?sequence=4", "http://gobeauty.kr/images/logo_login.jpg");
//echo $rt;

?>
