

<form id="writing-form" method="POST" enctype="multipart/form-data" novalidate action="{{ route('writingSaveEntry.store', ['form_id' => $form_id  ]) }}">

    @csrf

    @foreach($pages as $page) 
        <h2>{{ $page->page_id }}</h2>
     
            @if(isset($formFieldChildrenHTML[$page->page_id]))
                @foreach($formFieldChildrenHTML[$page->page_id] as $formFieldChildHTML) 
                    {!! $formFieldChildHTML !!}
                @endforeach
            @endif
            @if( $page->page_id == 1 )
                @foreach($formFieldHTML as $HTML) 
                    {!! $HTML !!}
                @endforeach
            @endif
        </section>
    @endforeach
  

    <input type="submit" value="Submit">
</form>
