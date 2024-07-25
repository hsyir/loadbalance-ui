<?php

class Asset  extends BaseClass
{
    public function run()
    {

        $file = $this->data['file'];


        $filePath = DOC_ROOT . '/ui/dist/assets/' . $file;  // مسیر دایرکتوری که فایل‌های شما در آن ذخیره شده است

        // بررسی وجود فایل
        if (file_exists($filePath)) {
            $fileInfo = pathinfo($filePath);
            $extension = strtolower($fileInfo['extension']);

            // تعیین نوع محتوا بر اساس پسوند فایل
            switch ($extension) {
                case 'css':
                    header('Content-Type: text/css');
                    break;
                case 'js':
                    header('Content-Type: application/javascript');
                    break;
                case 'png':
                    header('Content-Type: image/png');
                    break;
                case 'jpg':
                case 'jpeg':
                    header('Content-Type: image/jpeg');
                    break;
                case 'gif':
                    header('Content-Type: image/gif');
                    break;
                case 'svg':
                    header('Content-Type: image/svg+xml');
                    break;
                default:
                    header('Content-Type: application/octet-stream');
            }

            // خواندن و بازگشت دادن محتوای فایل
            readfile($filePath);
            exit;
        } else {
            // اگر فایل وجود نداشت
            http_response_code(404);
            echo 'File not found.';
            exit;
        }
    }
}