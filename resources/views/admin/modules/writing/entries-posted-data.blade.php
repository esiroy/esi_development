@extends('layouts.admin')

@section('content')
       

    <div class="container bg-light px-0">
        <div class="row">
            <div class="col-md-12">
                @include('admin.modules.writing.includes.menu.top')      
            </div>
        </div>
    </div>

    <div class="container bg-light">
        <div class="row">
            <div class="col-md-12">
                @if (Auth::user()->user_type == 'ADMINISTRATOR')  
                <div class="card esi-card mb-2">                                 
                    @include('admin.modules.writing.includes.menu.navigation')
                </div>
                @endif

               
                @foreach ($entries as $entry)
                    @php 
                        $values = json_decode($entry->value, true);
                    @endphp

                    @foreach ($values as $index => $value)                                             
                        @php
                            $numIndex = explode("_", $index);
                            $fieldValue[$entry->id][$numIndex[0]] = $value;
                        @endphp
                    @endforeach                  
                @endforeach


                <div class="card">
                    <div class="card-header card-header esi-card-header-title text-center bg-darkblue text-white h5 font-weight-bold">
                         Writing Entries
                    </div>

                    <div class="card-body p-0 m-0 b-0">

                        <div class="table-responsive mb-0">
                            <table class="table esi-table table-bordered table-striped  ">
                                <thead>
                                    <tr>
                                        @foreach ($formFields as $formField)
                                            <td class="{{ $formField->name }}_head" style="max-width:40px">{{ $formField->name }}</td>
                                        @endforeach    
                                    
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($entries as $entry)
                                    <tr>
                                        @foreach ($formFields as $formField)
                                        <td id="{{$formField->id}}" class="text-center bg-light text-dark {{ $formField->name }}_data" style="max-width:40px">
                                            @if ($formField->type == 'uploadfield')
                                                @if (isset($fieldValue[$entry->id][$formField->id]))
                                                    @php 
                                                        $writingFields = new \App\Models\WritingEntries;
                                                        $writingFields->generateFileAnchorLink( $fieldValue[$entry->id][$formField->id] );
                                                    @endphp
                                                    
                                                @endif
                                            @else 
                                                @if (isset($fieldValue[$entry->id][$formField->id]))
                                                    {!! $fieldValue[$entry->id][$formField->id]  !!}
                                                @endif
                                            @endif                                    
                                        </td>
                                        @endforeach
                                       
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>   
@endsection


@section('styles')
    @parent
    <style>
        .esi-table img {
            width: 100%;
            padding: 10px;
        }
    </style>
@endsection