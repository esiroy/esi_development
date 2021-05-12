@extends('layouts.admin')

@section('content')

  

    @if ($userImage == null)
        <img src="{{ Storage::url('user_images/noimage.jpg') }}" class="img-fluid border" alt="no photo uploaded">
    @else
        <img src="{{ Storage::url("$userImage->original") }}" class="img-fluid border" alt="profile photo">
    @endif


    <br>
    {{ $tutor->user->firstname ?? '' }} {{ $tutor->user->lastname ?? '' }}

    <br>


    @if (strtolower($member->gender) == 'male')
        {{ '男' }}
    @elseif (strtolower($member->gender) == 'female')
        {{ '女' }}
    @else 
        {{ '-'}}
    @endif    
    <br>

    test grade 1: {{  $questionnaireItem1->grade ?? "" }} <br>

    test grade 2: {{  $questionnaireItem1->grade ?? "" }} <br>

    test grade 3: {{  $questionnaireItem1->grade ?? "" }} <br>

    test grade 4: {{  $questionnaireItem1->grade ?? "" }} <br>


  


  
@endsection
