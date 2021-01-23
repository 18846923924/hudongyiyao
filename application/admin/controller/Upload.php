<?php
/**
 * Description:
 * Created by PhpStorm.
 *
 * Date: 2019/4/12
 * Time: 16:21
 */

namespace app\admin\controller;


use app\exception\FileUploadErr;
use app\exception\UserOff;
use app\Response;
use app\services\AdminUserService;
use app\services\AlbumService;
use app\services\FileService;
use app\services\GoodsService;
use app\services\ShopService;
use think\Controller;
use think\facade\Env;
use think\Request;

class Upload extends Controller
{
    private $admin_user;

    protected function initialize()
    {
        if(!$this->admin_user = AdminUserService::getInstance()->getAdminLoginStatus())
            throw new UserOff();
    }

    public function upload()
    {
        $file = request()->file('file');
        $data = FileService::getInstance()->upload($file);
        return Response::wrapData($data);
    }

    public function albumPicUpload()
    {
        $file = request()->file('file');
        $data = FileService::getInstance()->upload($file);
        $a_id = $this->request->post('a_id');
        $ap_id = AlbumService::getInstance()->addAlbumPic($a_id,$data['save_name'],$data['name'],$data['width'],$data['height'],$data['size']);
        $data['ap_id'] = $ap_id;
        return Response::wrapData($data);
    }

    public function bigUpload()
    {
        header("Access-Control-Allow-origin:*");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            exit; // finish preflight CORS requests here
        }
        if (!empty($_REQUEST['debug'])) {
            $random = rand(0, intval($_REQUEST['debug']));
            if ($random === 0) {
                header("HTTP/1.0 500 Internal Server Error");
                exit;
            }
        }
        // 5 minutes execution time
        @set_time_limit(5 * 60);
        $targetDir = Env::get('root_path').'/public/upload_tmp/';   //切片保留路径
        $cleanupTargetDir = true; // Remove old files
        $maxFileAge = 5 * 3600; // Temp file age in seconds
        // Create target dir
        if (!file_exists($targetDir)) {
            @mkdir($targetDir);
        }
        // Get a file name
        if (isset($_REQUEST["name"])) {
            $fileName = $_REQUEST["name"];
        } elseif (!empty($_FILES)) {
            $fileName = $_FILES["file"]["name"];
        } else {
            $fileName = uniqid("file_");
        }
        $filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;
        // Chunking might be enabled
        $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
        $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
        // Remove old temp files
        if ($cleanupTargetDir) {
            if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
                throw new FileUploadErr('Failed to open temp directory');
            }
            while (($file = readdir($dir)) !== false) {
                $tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;
                // If temp file is current file proceed to the next
                if ($tmpfilePath == "{$filePath}_{$chunk}.part" || $tmpfilePath == "{$filePath}_{$chunk}.parttmp") {
                    continue;
                }
                // Remove temp file if it is older than the max age and is not the current file
                if (preg_match('/\.(part|parttmp)$/', $file) && (@filemtime($tmpfilePath) < time() - $maxFileAge)) {
                    @unlink($tmpfilePath);
                }
            }
            closedir($dir);
        }

        // Open temp file
        if (!$out = @fopen("{$filePath}_{$chunk}.parttmp", "wb")) {
            throw new FileUploadErr('Failed to open output stream');
        }

        if (!empty($_FILES)) {
            if ($_FILES["file"]["error"] || !is_uploaded_file($_FILES["file"]["tmp_name"])) {
                throw new FileUploadErr('Failed to move uploaded file');
            }

            // Read binary input stream and append it to temp file
            if (!$in = @fopen($_FILES["file"]["tmp_name"], "rb")) {
                throw new FileUploadErr('Failed to open input stream');
            }
        } else {
            if (!$in = @fopen("php://input", "rb")) {
                throw new FileUploadErr('Failed to open input stream');
            }
        }

        while ($buff = fread($in, 4096)) {
            fwrite($out, $buff);
        }

        @fclose($out);
        @fclose($in);

        rename("{$filePath}_{$chunk}.parttmp", "{$filePath}_{$chunk}.part");

        $index = 0;
        $done = true;
        for ($index = 0; $index < $chunks; $index++) {
            if (!file_exists("{$filePath}_{$index}.part")) {
                $done = false;
                break;
            }
        }
        if ($done) {
            $dir = date('Ymd');
            $uploadDir = Env::get('root_path').'/public/uploads/'.$dir;       //最终上传路径
            // Create target dir
            if (!file_exists($uploadDir)) {
                @mkdir($uploadDir);
            }
            $save_name = strtolower(str_random(32));//随机一个存储文件名
            $extension = pathinfo($fileName)['extension'];
            $uploadPath = $uploadDir . DIRECTORY_SEPARATOR .$save_name.'.'.$extension;
            $file_save_path = $dir.'/' . $save_name.'.'.$extension;
            if (!$out = @fopen($uploadPath, "wb")) {
                throw new FileUploadErr('Failed to open output stream');
            }

            if (flock($out, LOCK_EX)) {
                for ($index = 0; $index < $chunks; $index++) {
                    if (!$in = @fopen("{$filePath}_{$index}.part", "rb")) {
                        break;
                    }

                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
                    @fclose($in);
                    @unlink("{$filePath}_{$index}.part");
                }
                flock($out, LOCK_UN);
            }
            @fclose($out);
            $data = ['file_path'=>$file_save_path,'file_name'=>$fileName];
            return Response::wrapData($data);
        }
    }

    public function delFile(Request $request)
    {
        $file = $request->post('file');
        FileService::getInstance()->delPic($file);
        return Response::wrapData(null);
    }

    public function excelImport()
    {
        $file = request()->file('file');
        $data = FileService::getInstance()->excelImport($file);
        return Response::wrapData($data);
    }

}