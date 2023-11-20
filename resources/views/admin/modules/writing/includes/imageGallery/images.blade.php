@php        
    $files = File::files(public_path(). '/storage/uploads/writing_materials');
@endphp

@if ( count($files) > 0 )
<div class="row">
    @foreach ($files as $file) 
    <div class="col-md-2 mb-4 img-container">
        <div class="img-wrapper" style="margin:auto; border:3px solid #D2E0EB; background-color:#F6FBFD; display: flex;height: 100%; max-height: 125px; overflow:hidden">

            @php                                                                
                $filename   = $file->getRelativePathname();
                $filetype  = pathinfo($filename, PATHINFO_EXTENSION);
                $image = url('storage/uploads/writing_materials/' . $filename);

                if ($filetype == 'jpg' || 
                    $filetype == 'jpeg' || 
                    $filetype == 'png' || 
                    $filetype == 'gif'  
                ) {
                    $shownImage =  $image;                                                                    
                } else {
                    $shownImage = $mp3Image;                                                                    
                }
                echo "<img src='$shownImage' class='img-fluid' style='margin: auto;'>";
            @endphp 
        </div>

        <div class="img-url-container text-center d-none" style="background-color:#D2E0EB;">
            <span class="img-url text-break small">{{$shownImage}}</span>
        </div>

        <div class="img-filename-container text-center" style="background-color:#D2E0EB;">
            <span class="img-filename text-break small">{{$filename}}</span>
        </div>
    </div>
    @endforeach
 </div>
 @else 
    {{ "No Image Found, Please upload using upload files tab" }}
 @endif