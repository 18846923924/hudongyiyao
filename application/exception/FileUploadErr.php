<?php


namespace app\exception;


class FileUploadErr extends BaseException
{
    public function __construct($debug = '')
    {
        parent::__construct('文件上传失败', 1003, $debug);
    }
}