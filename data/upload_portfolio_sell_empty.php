<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/include/check_login_shop.php");

$user_id = $_SESSION['gobeauty_user_id'];
make_user_directory($upload_static_directory2 . $upload_directory2, $user_id);

// 설정
$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');

// 변수 정리
//$error = $_FILES['myfile']['error'];
//$name = $_FILES['myfile']['name'];

$filename = $_POST['filepath'];
$filename = trim($filename);
echo "filename:" . $filename . "<br>";

$new_filename = $_POST['newfilepath'];
$new_filename = trim($new_filename);
echo "newfile:" . $newfilename . "<br>";

$filename_array = explode('.', $filename);
$ext = array_pop($filename_array);

// 오류 확인
if ($error != UPLOAD_ERR_OK) {
    switch ($error) {
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
if (!in_array(strtolower($ext), $allowed_ext)) {
    echo "허용되지 않는 확장자입니다.";
    exit;
}

$upload_direcoty_full_path = $upload_directory2 . "/" . $new_filename;
//move_uploaded_file( $_FILES['myfile']['tmp_name'], $upload_static_directory2.$upload_direcoty_full_path);

$oldfile = $upload_static_directory2 . "/upload/appupload/" . $filename;
$newfile = $upload_static_directory2 . $upload_direcoty_full_path;
$imgpath = $oldfile;
$target = $newfile;

//모바일 세로 이미지 로테이션...
$mklotaion = false;
//			$tmpp_file=$_FILES["imgupfile"]["tmp_name"];
$exifData = exif_read_data($imgpath);
//print_r($exifData);
if ($exifData['Orientation']) {
    //echo "lotation";
    if ($exifData['Orientation'] == 6) {
        // 시계방향으로 90도 돌려줘야 정상인데 270도 돌려야 정상적으로 출력됨
        $degree = 270;
    } else if ($exifData['Orientation'] == 8) {
        // 반시계방향으로 90도 돌려줘야 정상
        $degree = 90;
    } else if ($exifData['Orientation'] == 3) {
        $degree = 180;
    }
    //echo $exifData['Orientation'];
    if ($degree) {
        if ($exifData[FileType] == 1) {
            $source = imagecreatefromgif($imgpath);
            $source = imagerotate($source, $degree, 0);
            imagegif($source, $target);
            $mklotaion = true;
            //	echo "-1".$degree;
        } else if ($exifData[FileType] == 2) {
            $source = imagecreatefromjpeg($imgpath);
            $source = imagerotate($source, $degree, 0);
            imagejpeg($source, $target);
            $mklotaion = true;
            //	echo "-2/".$degree."/".$source."/".$target;
        } else if ($exifData[FileType] == 3) {
            $source = imagecreatefrompng($imgpath);
            $source = imagerotate($source, $degree, 0);
            imagepng($source, $target);
            $mklotaion = true;
            //	echo "-3".$degree;
        }
    } //if($degree)
    //로테이션 처리...를 못했으면 그냥 업로드 처리
    if ($mklotaion == false) {
        echo "1->".$imgpath." -- ".$target;
        //move_uploaded_file($imgpath, $target);
        copy($imgpath, $target);
    }
} else {  //if($exifData['Orientation'])
    //move_uploaded_file($imgpath, $target);
    echo "2->".$imgpath." -- ".$target;
    copy($imgpath, $target);
    //echo "그냥업로드";
} //if($exifData['Orientation'])
//기존 앱에서 올린 파일 삭제..
//@unlink($imgpath);


//echo $new_filename;
// 파일 정보 출력
/*echo "<h2>파일 정보</h2>
<ul>
	<li>파일명: $name</li>
	<li>확장자: $ext</li>
	<li>파일형식: {$_FILES['myfile']['type']}</li>
	<li>파일크기: {$_FILES['myfile']['size']} 바이트</li>
</ul>";
*/
//header('Content-type: application/json');
?>

<!-- {
    "upfilename": "<?= $upload_direcoty_full_path ?>",
    "allpath": "<?= $upload_static_directory2 . $upload_direcoty_full_path ?>",
    "oldfile":"<?=$oldfile?>",
    "newfile":"<?=$newfile?>"
    } -->