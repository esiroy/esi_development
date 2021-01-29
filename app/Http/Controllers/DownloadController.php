<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response, Storage;

class DownloadController extends Controller
{
    public function index(Request $request)
    {
        if (is_object($request)) {
            try
            {
                $name = $request->filename;
                $folder_id = $request->folder_id;
                $file = public_path() . "/storage/uploads/" . $folder_id . "/" . $name;
                $content_type = $this->mime_content_type($file);
                $headers = array('Content-Type: ' . $content_type . '');
                return Response::download($file, $name);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }

        } else {

            abort(403, 'Unauthorized action.');

        }
    }

    public function downloadLessonMaterial($filename)
    {

        try
        {
            
            $file = public_path() . "/storage/uploads/lesson_materials/" . $filename;


            $name = basename($filename);

            $content_type = $this->mime_content_type($file);
            $headers = array('Content-Type: ' . $content_type . '');
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        return Response::download($file, $name);

    }

    public function mime_content_type($filename)
    {
        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $temp_filename = explode('.', $filename);

        $ext = strtolower(array_pop($temp_filename));

        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        } elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        } else {
            return 'application/octet-stream';
        }
    }

}
