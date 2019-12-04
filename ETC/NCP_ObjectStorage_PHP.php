<?php
header("Content-Type:application/json; charset=utf-8");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT');

// 에러 출력
// error_reporting(E_ALL);
// ini_set("display_errors", 1);

// [필수] COMPOSER 설치 
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
$s3 = Aws\S3\S3Client::factory(array(
    'region' => 'kr-standard',
    'version' => 'latest',
    'credentials' => array(
        'key' => 'ACCESS_KEY',
        'secret'  => 'SECRET_KEY',
    ),
    'endpoint' => 'https://kr.object.ncloudstorage.com',));

// 버킷 목록 조회
$result = $s3->listBuckets([]);
for($i=0;$i<count($result['Buckets']);$i++)
{
    echo $result['Buckets'][$i]['Name'];
    echo "<br>";
}

// 오브젝트 업로드
try {
    $bucket = 'BUCKET_NAME';
    $folder_name = 'FOLDER_NAME';
    // 폴더 생성
    // 'Bucket'=> 버켓 이름
    // 'Key'=> 폴더 이름 + "/"
    $result = $s3->putObject([
        'Bucket' => $bucket,
        'Key'    => $folder_name.'/',
    ]);

    // 파일 업로드(이미지)
    $img = $_POST['file'];
    $name = $_POST['name'];
    $img = str_replace('data:image/jpeg;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $object = base64_decode($img);
    $object_name = $name.".jpg";

    $result = $s3->putObject([
        'Bucket' => $bucket,
        'Key'    => $folder_name.'/'.$object_name,
        'Body'   => $object,
        'ACL'    => 'public-read'
    ]);

    // Print the URL to the object.
    echo $result['ObjectURL'] . PHP_EOL;
} catch (S3Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}

?>

