<?php

namespace App\Jobs;

use App\{Video, Course, Section, CLogs};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\{Storage};

use \Aws\S3\{S3Client, MultipartUploader, ObjectUploader};
use Aws\MediaConvert\MediaConvertClient;  
use Aws\Exception\{AwsException,MultipartUploadException};

use AWS;

class UploadBigFilesToAwsS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 0;

    protected $filedata;
    protected $video_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filedata, $video_id)
    {
        $this->filedata = $filedata;
        $this->video_id = $video_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $s3disk = Storage::disk('s3');
        $s3Client = $s3disk->getDriver()->getAdapter()->getClient();
        $uploader = $s3Client->putObject([
            "Bucket" => $s3disk->getDriver()->getAdapter()->getBucket(),
            "Key" => $this->filedata['filepath'],
            "SourceFile" => storage_path("temporary/course/video/{$this->filedata['filename']}"),
        ]);

        $video = Video::find($this->video_id);
        if($video){
            switch ($video->upload_status) {
                case 'uploading':
                    $duration = 0;
                    if(array_key_exists("ObjectURL", $uploader)){
                        /**
                         * Getting Video Length
                         * 
                         */
                        if ($fp_remote = fopen($uploader["ObjectURL"], 'rb')) {
                            $localtempfilename = tempnam('/tmp', 'getID3');
                            if ($fp_local = fopen($localtempfilename, 'wb')) {
                                while ($buffer = fread($fp_remote, 8192)) {
                                    fwrite($fp_local, $buffer);
                                }
                                fclose($fp_local);
                                $getID3 = new \getID3;
                                $ThisFileInfo = $getID3->analyze($localtempfilename);
                                $duration = $ThisFileInfo['playtime_string'];
                                unlink($localtempfilename);
                            }
                        }
            
                        $video->cdn_url = $uploader["ObjectURL"];
                        $video->uploading_status = "done";
                        $video->length = $duration;
                        $video->save();                    
                        unlink(storage_path("temporary/course/video/{$this->filedata['filename']}"));
                    }else{
                        $s3Bucket = $s3disk->getDriver()->getAdapter()->getBucket();
                        $s3disk->put($this->filedata['filepath'], storage_path("temporary/course/video/{$this->filedata['filename']}"), 'public');
                        $OBJECT_URL = "https://{$s3Bucket}.s3-ap-northeast-1.amazonaws.com{$filePath}";
                        /**
                         * Getting Video Length
                         * 
                         */
                        if ($fp_remote = fopen($OBJECT_URL, 'rb')) {
                            $localtempfilename = tempnam('/tmp', 'getID3');
                            if ($fp_local = fopen($localtempfilename, 'wb')) {
                                while ($buffer = fread($fp_remote, 8192)) {
                                    fwrite($fp_local, $buffer);
                                }
                                fclose($fp_local);
                                $getID3 = new \getID3;
                                $ThisFileInfo = $getID3->analyze($localtempfilename);
                                $duration = $ThisFileInfo['playtime_string'];
                                unlink($localtempfilename);
                            }
                        }
            
                        $video->cdn_url = $OBJECT_URL;
                        $video->uploading_status = "done";
                        $video->length = $duration;
                        $video->save();  
                        unlink(storage_path("temporary/course/video/{$this->filedata['filename']}"));
                    }
                break;
            }
        }else{
            $video->uploading_status = "failed";
            $video->save();

            CLogs::insert([
                "message" => "Failed to upload...video record not found! ".date("Y-m-d H:i:s"),
                "payload" => json_encode($this->filedata),
                "signature" => $this->video_id,
            ]);

            unlink(storage_path("temporary/course/video/{$this->filedata['filename']}"));
            $s3Client->deleteObject([
                'Bucket' => $s3disk->getDriver()->getAdapter()->getBucket(),
                'Key'    => $this->filedata['filepath']
            ]); 
        }

    }
}
