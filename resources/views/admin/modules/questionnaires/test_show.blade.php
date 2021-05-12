@extends('layouts.admin')

@section('content')

  
    test grade 1: {{  $questionnaireItem1->grade ?? "" }}


    {{ getQuestionnnaireGradeTranslation( $questionnaireItem1->grade) ?? '' }}


  
@endsection
