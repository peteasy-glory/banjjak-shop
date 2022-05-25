<?php

/*

$s3Client = new S3Client([
        'region' => 'ap-northeast-2',
        'version' => 'latest',
        # 자격증명
        'credentials' => [
          'key'    => 'AKIATLSPGL6BNM6VOYWX',
          'secret' => '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',
        ],
    ]);

$fp = fopen("sam.txt", r);
$result = $s3Client->putObject([
    'Bucket'=>'banjjak-s3',
    'Key' =>  'sam.txt',
    'Body' => $fp
]);   
   */

require '/var/www/html/subdomain/banjjak_sol/vendor/autoload.php';

// AWS S3에 파일 업로드할 때 필요한 클래스들을 불러옵니다.

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\Credentials\CredentialProvider;

// Create a S3Client 
$s3Client = new S3Client([
    'region' => 'ap-northeast-2',
    'version' => 'latest',
    'credentials' => [
        'key'    => 'AKIATLSPGL6BNM6VOYWX',
        'secret' => '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',
    ]
]);

$fp = fopen("1.png", r);
$result = $s3Client->putObject([
    'Bucket'=>'banjjak-s3',
    'Key' =>  '1.png',
    'Body' => $fp
]);
fclose($fp);

/*
$s3Client = S3Client::factory(array(
  'region' => 'ap-northeast-2', // S3 리전을 입력합니다.(저는 서울 리전)
  'version' => 'latest',
  'signature' => 'v4',
  'credentials' => [
    'key'    => 'AKIATLSPGL6BNM6VOYWX',
    'secret' => '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',
  ],
));

$s3_path = '/img/img/img/1.png'; // 업로드할 위치와 파일명 입니다.
$file_url = 'https://partner.banjjakpet.com/upload/pettester@peteasy.kr/pet_gallery_20220326161900.png'; // 업로드할 이미지의 URL 주소 입니다.
$file_data = file_get_contents($file_url); // URL주소로 부터 데이터를 받아옵니다.
$result = $s3Client->putObject(array(
  'Bucket' => 'banjjak-s3',
  'Key'    => $s3_path,
  'Body'   => $file_data,
  'ACL'    => 'public-read'
));
*/
/*
#Bucket 연결 객체
$s3Client = new S3Client([
        'region' => 'ap-northeast-2',
        'version' => 'latest',
        # 자격증명
        'credentials' => [
          'key'    => 'AKIATLSPGL6BNM6VOYWX',
          'secret' => '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',
        ],
    ]);


#File Upload to S3
$fp = fopen($upload_file, r);
$result = $s3Client->putObject([
    'Bucket'=>'banjjak-s3',
    'Key' =>  $_FILES['file1']['name'],
    'Body' => $fp
]);
fclose($fp);
*/

