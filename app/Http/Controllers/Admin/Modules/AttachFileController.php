<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UploadFile;

use Gate, Auth, Config;

class AttachFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
       return view('admin.modules.attachFile.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, UploadFile $uploadFile)
    {

      
        
        /********************************************
        *               log to report card
        **********************************************/
        $storagePath = 'public/uploads/report_files/';
        $publicURL = 'storage/uploads/report_files/';
       

        $file = $request->file('file');

        print_r ($file);

       
        if ($file) {

            $uploadFileName = $uploadFile->uploadFile($storagePath, $file);

            if ($uploadFileName) 
            {

         


                $writingGrade = [
                    'writing_entry_id'  => "test",
                    'course'            => "test",
                    'material'          => "test",
                    'subject'           => "test",
                    'appointed'         => "test",
                    'grade'             => "test",
                    'words'             => "test",
                    'content'           => "test",
                    'attachment'        =>  url($publicURL . basename($uploadFileName));,
                ];


                //E-Mail Template
                $emailTemplate = 'emails.writing.teacherAutoreply';                    
                $formatEntryHTML = view('emails.writing.writingTutorReplyHTML', compact('writingGrade'))->render();
                //E-Mail Recipient
                $emailTo['name']    = "roy robert abellana";
                $emailTo['email']   = "abellana@gmail.com"; 
                //Email Reply To
                $emailFrom['name']   = Config::get('mail.from.name');
                $emailFrom['email']  = Config::get('mail.from.address');

                $emailSubject = "Test attachment";
                $emailMessage =  $formatEntryHTML;

                $attachment_url = $publicURL . basename($uploadFileName);
                $realImagePath  = realpath($attachment_url);
                $fileURL = url($publicURL . basename($uploadFileName));

                $attachment = [
                    'fileURL' => $fileURL,
                    'clientOriginalName' => $file->getClientOriginalName(),
                    'realPath' => $file->getRealPath(),
                    'clientMimeType'  => $file->getClientMimeType()
                ];
                
                $job = new \App\Jobs\SendAutoReplyJob($emailTo, $emailFrom, $emailSubject, $emailMessage, $emailTemplate, $attachment);
                dispatch($job);  

                echo "sent!";

            } 
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
