@extends('layouts.admin')

@section('content')


    <div class="container bg-light px-0">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light ">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active"><a href="{{ url('admin/writing') }}">Writing</a></li>
                        <li class="breadcrumb-item " aria-current="page">Writing</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


    <div class="container bg-light">
        <div class="row">
            <div class="col-md-12">
                
                <div>Writing Entries</div>

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

                <div class="table-responsive ">
                    <table class="table table-bordered table-sm table-striped">
                        <thead>
                            <tr>
                                @foreach ($formFields as $formField)
                                    <th class="small text-center bg-light text-dark font-weight-bold">{{ $formField->name }}</th>
                                @endforeach    
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($entries as $entry)
                            <tr>
                                @foreach ($formFields as $formField)
                                <td id="{{$formField->id}}" class="small text-center bg-light text-dark font-weight-bold">
                                    @if (isset($fieldValue[$entry->id][$formField->id]))
                                        {{ $fieldValue[$entry->id][$formField->id]  }}
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
@endsection
