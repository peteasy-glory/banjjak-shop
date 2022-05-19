<?
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$request_method = $_SERVER["REQUEST_METHOD"];

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

//if ($request_method == "POST"){

//==fileupload==//
make_user_directory($upload_static_directory2.$upload_directory2, $user_id);

//$upload_dir = $upload_static_directory2.$upload_directory2."/".$user_id;

// 설정
$allowed_ext = array('jpg','jpeg','png','gif');

// 변수 정리
$filename = $_REQUEST['myfile'];
$filename = trim($filename);

$new_filename = $_REQUEST['filepath'];
$new_filename = trim($new_filename);
$ext = array_pop(explode('.', $filename));


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

// 파일 이동
$upload_direcoty_full_path = $upload_directory2."/".$new_filename;

//move_uploaded_file( $_FILES['file']['tmp_name'], $upload_static_directory2.$upload_direcoty_full_path);

$oldfile = $upload_static_directory2."/upload/appupload/".$filename;
$newfile = $upload_static_directory2.$upload_direcoty_full_path;
$imgpath = $oldfile;
$target = $newfile;

//모바일 세로 이미지 로테이션...
$mklotaion=false;
$exifData = exif_read_data($imgpath);
//print_r($exifData);
if($exifData['Orientation']){
//echo "lotation";
    if($exifData['Orientation'] == 6) {
        // 시계방향으로 90도 돌려줘야 정상인데 270도 돌려야 정상적으로 출력됨
        $degree = 270;
    }
    else if($exifData['Orientation'] == 8) {
        // 반시계방향으로 90도 돌려줘야 정상
        $degree = 90;
    }
    else if($exifData['Orientation'] == 3) {
        $degree = 180;
    }
    if($degree) {
        if($exifData[FileType] == 1) {
            $source = imagecreatefromgif($imgpath);
            $source = imagerotate ($source , $degree, 0);
            imagegif($source, $target);
            $mklotaion=true;
        }
        else if($exifData[FileType] == 2) {
            $source = imagecreatefromjpeg($imgpath);
            $source = imagerotate ($source , $degree, 0);
            imagejpeg($source, $target);
            $mklotaion=true;
        }
        else if($exifData[FileType] == 3) {
            $source = imagecreatefrompng($imgpath);
            $source = imagerotate ($source , $degree, 0);
            imagepng($source, $target);
            $mklotaion=true;
//	echo "-3".$degree;
        }
    } //if($degree)
    //로테이션 처리...를 못했으면 그냥 업로드 처리
    if($mklotaion==false){
        copy($imgpath, $target);
    }
}else{  //if($exifData['Orientation'])
    copy($imgpath, $target);
}

$s3 = new TAwsS3('banjjak-s3', 'AKIATLSPGL6BNM6VOYWX', 'JJagfUCVzN4fCOrX3cdGHlX+8WL9PJ7T0GUHlFao');
$s3->resizeImage($target, $target);
$s3->fileToS3($target, $upload_directory."/".$new_filename);
@unlink($imgpath);
//==fileupload==//


echo $upload_direcoty_full_path;
//}
?>
