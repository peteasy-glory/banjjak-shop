<?php
    //$file_path = "/opt/apache/htdocs/pet/upload/appupload/";
    $file_path = "/var/www/html/subdomain/banjjak_sol/shop/upload/appupload/";
    $file_path = $file_path . basename( $_FILES['uploaded_file']['name']);
    if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path)) {
        echo "success";
    } else{
        echo "fail";
    }

 ?>
