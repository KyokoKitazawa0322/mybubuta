<?php
namespace Models;
    
class UploadFileDao{
        
    /**
     * 画像アップロード
     * @return 'ObjectURL'(アップロードした対象画像のURL)
     * @throws MyS3Exception
     */
    protected function uploadImage($fileName, $fileTmpName){

        $s3client = new \Aws\S3\S3Client([
            'credentials' => [
                'key' => getenv('AWS_ACCESS_KEY_ID'),
                'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
            ],
            'region' => 'ap-northeast-1',
            'version' => 'latest',
        ]);

        try {
            $result = $s3client->putObject([
                'ACL' => 'public-read',
                'Bucket' => getenv('S3_BUCKET_NAME'),
                'Key' => $fileName,
                'SourceFile' => $fileTmpName,
                'ContentType' => mime_content_type($fileTmpName),
            ]);
            return $result['ObjectURL'];
        } catch (\S3Exception $e) {
            throw new MyS3Exception($e->getMessage(), (int)$e->getCode());
        }
    }
}
?>