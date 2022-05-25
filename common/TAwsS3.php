<?php
require '/Users/banjjak_dev/Desktop/Dev_Project/2.Dev/3.Web/www/html/subdomain/banjjak_sol/aws/aws-autoloader.php';
//require '/var/www/html/subdomain/banjjak_sol/vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\Credentials\CredentialProvider;

class TAwsS3
{
    private $s3Clint;
    private $s3Bucket;
    private $s3Key;
    private $s3Secret;

    public function __construct($bucket, $key, $secret){
        $this->s3Bucket = $bucket;
        $this->s3Key = $key;
        $this->s3Secret = $secret;

        $this->createS3Client();;
    }

    private function createS3Client(){
        $this->s3Clint = new S3Client([
            'region' => 'ap-northeast-2',
            'version' => 'latest',
            'credentials' => [
                'key'    => $this->s3Key,
                'secret' => $this->s3Secret,
            ]
        ]);
    }

    public function fileToS3($src, $dest){
        try{
            $result = $this->s3Clint->putObject([
                'Bucket'=>$this->s3Bucket,
                'Key' =>  $dest,
                'SourceFile'=>$src
            ]);
            return $result;
        } catch (S3Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }

    public function deleteFileToS3($file){
        try{
            $result = $this->s3Clint->deleteObject([
                'Bucket'=>$this->s3Bucket,
                'Key' =>  $file
            ]);
            return $result;
        } catch (S3Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }

    public function resizeImage($file, $newfile) {
        $w = 0;
        $h = 0;
        list($width, $height) = getimagesize($file); // 업로드 파일의 가로세로 구하기
        if($width > 1080){ // 가로가 1280보다 크면
            $w = 1080;
            $h = 1080*($height/$width); // 가로 기준으로 세로 비율 구하기
        }else if($height > 1920){ // 세로가 1920보다 크면
            $h = 1920;
            $w = 1920*($width/$height); // 세로 기준으로 가로 비율 구하기
        }
        if(strpos(strtolower($file), ".jpg"))
            $src = imagecreatefromjpeg($file);
        else if(strpos(strtolower($file), ".png"))
            $src = imagecreatefrompng($file);
        else if(strpos(strtolower($file), ".gif"))
            $src = imagecreatefromgif($file);
        $dst = imagecreatetruecolor($w, $h);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
        // 이미지 회전
        if (function_exists('exif_read_data')) {
            $exif = exif_read_data($newfile);
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
        if(strpos(strtolower($newfile), ".jpg"))
            imagejpeg($dst, $newfile);
        else if(strpos(strtolower($newfile), ".png"))
            imagepng($dst, $newfile);
        else if(strpos(strtolower($newfile), ".gif"))
            imagegif($dst, $newfile);
    }

}

