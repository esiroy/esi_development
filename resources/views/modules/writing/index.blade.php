

<form id="writing-form" novalidate method="POST" enctype="multipart/form-data" action="{{ route('writingSaveEntry.store', ['form_id' => $form_id  ]) }}">

    @csrf

    @foreach($pages as $page) 
       
     
            @if(isset($formFieldChildrenHTML[$page->page_id]))
                @foreach($formFieldChildrenHTML[$page->page_id] as $formFieldChildHTML) 
                    {!! $formFieldChildHTML !!}
                @endforeach
            @endif
           
     
    @endforeach
  

    <input type="submit" value="Submit">
</form>
