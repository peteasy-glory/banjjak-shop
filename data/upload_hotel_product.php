<?
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$request_method = $_SERVER["REQUEST_METHOD"];

$user_id = $_SESSION['gobeauty_user_id'];
$user_name = $_SESSION['gobeauty_user_nickname'];

//if ($request_method == "POST"){

//==fileupload==//


//$upload_dir = $upload_static_directory2.$upload_directory2."/".$user_id;

// 설정
$allowed_ext = array('jpg','jpeg','png','gif');

// 변수 정리
$error = $_FILES['myfile']['error'];
$name = $_FILES['myfile']['name'];
$expl = explode('.', $name);
$ext = array_pop($expl);
$new_filename = $_POST['filepath'];


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
$upload_directory2 = '/upload/hotel';
$upload_direcoty_full_path = $upload_directory2."/".$new_filename;
$upload_static_directory3 = "/var/www/html/subdomain/banjjak_sol/shop";

make_user_directory($upload_static_directory3.$upload_directory2, $user_id);

move_uploaded_file( $_FILES['myfile']['tmp_name'], $upload_static_directory3.$upload_direcoty_full_path);

$s3 = new TAwsS3('banjjak-s3', 'AKIATLSPGL6BNM6VOYWX', 'JJagfUCVzN4fCOrX3cdGHlX+8WL9PJ7T0GUHlFao');
$s3->resizeImage($upload_static_directory3.$upload_direcoty_full_path, $upload_static_directory3.$upload_direcoty_full_path);
$s3->fileToS3($upload_static_directory3.$upload_direcoty_full_path, $upload_directory2."/".$new_filename);
//==fileupload==//

// image size
$size = @getimagesize($upload_static_directory3.$upload_direcoty_full_path);
//$img_x = 0;
//$img_y = 0;
$img_x = $size[0];
$img_y = $size[1];
$r_target = "tb_hotel_product.image";

//global $connection;
$sql = "
    INSERT INTO tb_file (
        `tmp_name`, `file_name`, `file_path`, `file_type`, `img_x`, 
        `img_y`, `target`, `reg_dt`
    ) VALUES (
        '".$_FILES['myfile']['tmp_name'][0]."', '".$new_filename."', '".$upload_direcoty_full_path."', '".$ext."', '".$img_x."',
        '".$img_y."', '".$r_target."', NOW()
    )
";
$result = mysqli_query($connection, $sql);
$review_seq = mysqli_insert_id($connection);

echo json_encode($review_seq);
//}
?>
