<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");

$r_mode = (isset($_POST["mode"]) && $_POST["mode"] != "")? $_POST["mode"] : "";
$return_data = array("code" => "999999", "data" => "잘못된 접근입니다.");
$data = array();

if($r_mode != ""){
    if($r_mode == "upload_img"){
        $r_target = ($_POST["target"] && $_POST["target"] != "")? $_POST["target"] : "";
        $r_folder = ($_POST["folder"] && $_POST["folder"] != "")? $_POST["folder"] : "";
        $img_x = 0;
        $img_y = 0;

        // create folder
        make_user_directory($upload_static_directory.$upload_directory, $r_folder);

        // 설정
        $allowed_ext = array('jpg','jpeg','png','gif');

        // 변수 정리
        $error = $_FILES['files']['error'][0];
        $name = $_FILES['files']['name'][0];

        $ext = explode('.', $name);
        $ext = strtolower(array_pop($ext));
        $new_filename = strtotime(DATE("Y-m-d H:i:s")).str_pad(rand(0,99),"2","0",STR_PAD_LEFT).".".$ext;

        // 오류 확인
        if( $error != UPLOAD_ERR_OK ) {
            switch( $error ) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    //echo "파일이 너무 큽니다. ($error)";
                    $return_data = array("code" => "010101", "data" => "파일이 너무 큽니다. (".print_r($error, 1).")");
                    break;
                case UPLOAD_ERR_NO_FILE:
                    //echo "파일이 첨부되지 않았습니다. ($error)";
                    $return_data = array("code" => "010102", "data" => "파일이 첨부되지 않았습니다. (".print_r($error, 1).")");
                    break;
                default:
                    //echo "파일이 제대로 업로드되지 않았습니다. ($error)";
                    $return_data = array("code" => "010103", "data" => "파일이 제대로 업로드되지 않았습니다. (".print_r($error, 1).")");
            }
            //exit;
        }else{
            // 확장자 확인
            if( !in_array(strtolower($ext), $allowed_ext) ) {
                //echo "허용되지 않는 확장자입니다.";
                $return_data = array("code" => "010104", "data" => "허용되지 않는 확장자입니다.");
                //exit;
            }else{
                $upload_direcoty_full_path = $upload_directory."/".$r_folder."/".$new_filename;

                move_uploaded_file( $_FILES['files']['tmp_name'][0], $upload_static_directory.$upload_direcoty_full_path);
                correctImageOrientation($upload_static_directory.$upload_direcoty_full_path);

                // create thumbnail
                make_thumbnail($upload_static_directory.$upload_directory."/".$r_folder, $new_filename, "100", "100", "100");

                // image size
                $size = @getimagesize($upload_static_directory.$upload_direcoty_full_path);
                $img_x = $size[0];
                $img_y = $size[1];

                $sql = "
						INSERT INTO tb_file (
							`tmp_name`, `file_name`, `file_path`, `file_type`, `img_x`, 
							`img_y`, `target`, `reg_dt`
						) VALUES (
							'".$_FILES['files']['tmp_name'][0]."', '".$new_filename."', '".$upload_direcoty_full_path."', '".$ext."', '".$img_x."',
							'".$img_y."', '".$r_target."', NOW()
						)
					";
                $result = mysqli_query($connection, $sql);
                if($result){
                    $file_id = mysqli_insert_id($connection);

                    $img_data = array(
                        "f_seq" => $file_id,
                        "file_name" => $new_filename,
                        "file_path" => $upload_direcoty_full_path,
                        "target" => $r_target
                    );
                    $data[] = $img_data;
                    $return_data = array("code" => "000000", "data" => $data);
                    //echo '{"status":"success", "files":{"0":{"id":"1","name":"asdf","path":"pet_dog.png"}}}';
                }else{

                    $return_data = array("code" => "010105", "data" => "파일 업로드에 실패했습니다.","result"=>$result,"tmp_name"=>$_FILES['files']['tmp_name'][0],"file_name"=>$new_filename,"file_path"=>$upload_direcoty_full_path,"file_type"=>$ext,"img_x"=>$img_x,"img_y"=>$img_y,"target"=>$r_target,"upload_static_directory"=>$upload_static_directory,"upload_direcoty_full_path"=>$upload_direcoty_full_path,"upload_directory"=>$upload_directory);
                }
            }
        }
    }else if($r_mode == "upload_img_app"){
        $r_target = ($_POST["target"] && $_POST["target"] != "")? $_POST["target"] : "";
        $r_folder = ($_POST["folder"] && $_POST["folder"] != "")? $_POST["folder"] : "";
        $r_file = ($_POST["file"] && $_POST["file"] != "" )? $_POST["file"] : "";
        $img_x = 0;
        $img_y = 0;

        $file = $upload_static_directory.$upload_directory.'/appupload/'.$r_file; // upload_file
        $ext = array_pop(explode('.', $r_file));
        $new_filename = strtotime(DATE("Y-m-d H:i:s")).str_pad(rand(0,99),"2","0",STR_PAD_LEFT).".".$ext;

        $upload_direcoty_full_path = $upload_directory."/".$r_folder."/".$new_filename;
        copy($file, $upload_static_directory.$upload_direcoty_full_path); // file_upload
        correctImageOrientation($upload_static_directory.$upload_direcoty_full_path);

        // create thumbnail
        make_thumbnail($upload_static_directory.$upload_directory."/".$r_folder, $new_filename, "100", "100", "100");

        // image size
        $size = @getimagesize($upload_static_directory.$upload_direcoty_full_path);
        $img_x = $size[0];
        $img_y = $size[1];

        $sql = "
				INSERT INTO tb_file (
					`tmp_name`, `file_name`, `file_path`, `file_type`, `img_x`, 
					`img_y`, `target`, `reg_dt`
				) VALUES (
					'".$r_file."', '".$new_filename."', '".$upload_direcoty_full_path."', '".$ext."', '".$img_x."',
					'".$img_y."', '".$r_target."', NOW()
				)
			";
        $result = mysqli_query($connection, $sql);
        if($result){
            @unlink($file); // 기존 업로드된 파일 삭제처리
            $file_id = mysqli_insert_id($connection);

            $img_data = array(
                "f_seq" => $file_id,
                "file_name" => $new_filename,
                "file_path" => $upload_direcoty_full_path,
                "target" => $r_target
            );
            $data[] = $img_data;
            $return_data = array("code" => "000000", "data" => $data);
            //echo '{"status":"success", "files":{"0":{"id":"1","name":"asdf","path":"pet_dog.png"}}}';
        }else{
            $return_data = array("code" => "010401", "data" => "파일 업로드에 실패했습니다.".$sql);
        }
    }else if($r_mode == "get_file_list"){
        $r_file_list = ($_POST["file_list"] && $_POST["file_list"] != "")? $_POST["file_list"] : "";

        if($r_file_list != ""){
            if(strpos($r_file_list, ",") !== false){
                $where_qy = " AND f_seq IN (".$r_file_list.") ";
            }else{
                $where_qy = " AND f_seq = ".$r_file_list." ";
            }
        }

        if($where_qy != ""){
            $sql = "
					SELECT *
					FROM tb_file
					WHERE is_delete = '1'
						".$where_qy."
				";
            $result = mysqli_query($connection, $sql);
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row;
            }
        }

        $return_data = array("code" => "000000", "data" => $data);
    }else if($r_mode == "set_delete_file"){
        $r_f_seq = ($_POST["f_seq"] && $_POST["f_seq"] != "")? $_POST["f_seq"] : "";
        $r_user_id = ($_POST["user_id"] && $_POST["user_id"] != "")? $_POST["user_id"] : "";
        $r_delete_txt = ($_POST["delete_txt"] && $_POST["delete_txt"] != "")? $_POST["delete_txt"] : "";

        $sql = "
				UPDATE tb_file SET
					`is_delete` = '2',
					`delete_txt` = '".$r_user_id."|".$r_delete_txt."',
					`delete_dt` = NOW()
				WHERE f_seq = '".$r_f_seq."'
			";
        $result = mysqli_query($connection, $sql);
        if($result){
            $return_data = array("code" => "000000", "data" => "OK");
        }else{
            $return_data = array("code" => "010301", "data" => "파일 삭제에 실패했습니다.");
        }
    }else if($r_mode == 'test') {


        $r_target = ($_POST["target"] && $_POST["target"] != "")? $_POST["target"] : "";
        $r_folder = ($_POST["folder"] && $_POST["folder"] != "")? $_POST["folder"] : "";
        $img_x = 0;
        $img_y = 0;

        // create folder
        make_user_directory($upload_static_directory.$upload_directory, $r_folder);

        // 설정
        $allowed_ext = array('jpg','jpeg','png','gif');

        // 변수 정리
        $error = $_FILES['files']['error'][0];
        $name = $_FILES['files']['name'][0];

        $return_data = array("code" => "000000", "data" =>$error,$name,$r_target,$r_folder);

    }else{
        $return_data = array("code" => "999997", "data" => "허용되지 않은 접근입니다.");
    }
}else{
    $return_data = array("code" => "999998", "data" => "올바르지 않은 접근입니다.");
}

echo json_encode($return_data, JSON_UNESCAPED_UNICODE);

function correctImageOrientation($filename){
    if (function_exists('exif_read_data')) {
        $exif = exif_read_data($filename);
        if ($exif && isset($exif['Orientation'])) {
            $orientation = $exif['Orientation'];
            if ($orientation != 1) {
                $img = imagecreatefromjpeg($filename);
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
                    $img = imagerotate($img, $deg, 0);
                }
                // then rewrite the rotated image back to the disk as $filename
                imagejpeg($img, $filename, 95);
            } // if there is some rotation necessary
        } // if have the exif orientation info
    } // if function exists
}

// 썸네일 만들기
function make_thumbnail($fileDic, $fileName, $w='500', $h='375', $q='100'){
    if(!file_exists($fileDic."/thumb/".$fileName) && $fileName != ''){ //섬네일 파일이 없다면,
        //업로드 폴더가 없다면, 업로드 폴더 생성
        @mkdir("{$fileDic}/thumb", 0707);

        //업로드될 파일 명
        $thumb_filename = $fileDic."/thumb/".$fileName;

        //생성 기준 이미지
        $dest_file = $fileDic."/".$fileName;

        //썸네일 생성
        $size = @getimagesize($dest_file);
        if ($size[2] == 1){
            $src = imagecreatefromgif($dest_file);
        }elseif ($size[2] == 2){
            $src = imagecreatefromjpeg($dest_file);
        }elseif ($size[2] == 3){
            $src = imagecreatefrompng($dest_file);
        }

        $width = $w;
        $height = $h;

        $dst = imagecreatetruecolor($width, $height);

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
        imagejpeg($dst, $thumb_filename, $q);//기본 생성 퀄리티 = 100
        chmod($thumb_filename, 0606);
    }
}
?>