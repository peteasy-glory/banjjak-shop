<?php
ini_set('memory_limit', -1);

include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

// 여기서 비율적으로 계산(함수 실행전에 사이즈 체크 후 비율 계산하기)
function correctImageOrientation($filename){
    $w = 0;
    $h = 0;

    list($width, $height) = getimagesize($filename); // 업로드 파일의 가로세로 구하기

    if($width > 1080){ // 가로가 1280보다 크면
        $w = 1080;
        $h = 1080*($height/$width); // 가로 기준으로 세로 비율 구하기
    }else if($height > 1920){ // 세로가 1920보다 크면
        $h = 1920;
        $w = 1920*($width/$height); // 세로 기준으로 가로 비율 구하기
    }

    $img = imagecreatefromjpeg($filename);

    if($width > 1080 || $height > 1920){
        $dst = imagecreatetruecolor($w, $h);
        imagecopyresampled($dst, $img, 0, 0, 0, 0, $w, $h, $width, $height);
    }

    // 이미지 회전
    if (function_exists('exif_read_data')) {
        $exif = exif_read_data($filename);
        if ($exif && isset($exif['Orientation'])) {
            $orientation = $exif['Orientation'];
            if ($orientation != 1) {
                $deg = 0;
                switch ($orientation) {
                    case 3:
                        $deg = 180;
                        break;
                    case 6:
                        $deg = 270;
                        break;
                    case 8:
                        $deg = 90;
                        break;
                }
                if ($deg) {
                    $dst = imagerotate($dst, $deg, 0);
                }
            } // if there is some rotation necessary
        } // if have the exif orientation info
    } // if function exists

    imagejpeg($dst, $filename); // 리사이징 및 회전이 완려된 파일 붙여넣기
}


$user_id = $_SESSION['gobeauty_user_id'];


make_user_directory($upload_static_directory.$upload_directory, $user_id);

// 설정
$allowed_ext = array('jpg','jpeg','png','gif');


// 변수 정리
$filepath_appuploaded = "";
if($_FILES['myfile']['name']) { // ios/pc용 
    $error = $_FILES['myfile']['error'];
    $name = $_FILES['myfile']['name'];
} else { // android
    //$_POST['myfile'] = 'uywkx_202203221039312.jpg';
    $name = $_POST['myfile'];
    $name = trim($name);
    // android app 업로드용 파일 경로
    $filepath_appuploaded = $upload_static_directory . "/upload/appupload/" . $name;
}
//$_POST['filepath'] = 'pettester@peteasy.kr/pet_gallery_1.jpg';
$new_filename = $_POST['filepath'];
$ext = array_pop(explode('.', $name));



// 오류 확인
if( $error != UPLOAD_ERR_OK ) {
    switch( $error ) {
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            echo "파일이 너무 큽니다. ($error)";
            break;
        case UPLOAD_ERR_NO_FILE:
            echo "파일이 첨부되지 않았습니다. ($error)";
            break;
        default:
            echo "파일이 제대로 업로드되지 않았습니다. ($error)";
    }
    exit;
}

// 확장자 확인
if( !in_array(strtolower($ext), $allowed_ext) ) {
    echo "허용되지 않는 확장자입니다.";
    exit;
}

$upload_direcoty_full_path = $upload_directory."/".$new_filename;
//echo "<br>";
//echo $upload_direcoty_full_path;

if(file_exists($filepath_appuploaded)) {    // android용

    if(!copy($filepath_appuploaded, $upload_static_directory.'/'.$upload_direcoty_full_path)){
        echo '파일복사실패';
    } else {
        //echo '파일복사성공';
    }
} else {    // pc / iphone용
//	imagejpeg($_FILES['myfile']['tmp_name'], $upload_static_directory.$upload_direcoty_full_path, 50);
    move_uploaded_file( $_FILES['myfile']['tmp_name'], $upload_static_directory.'/'.$upload_direcoty_full_path);
}

// 이미지 ori 조정
correctImageOrientation($upload_static_directory.$upload_direcoty_full_path);


    echo $upload_direcoty_full_path;

?>
