@extends('layouts.admin')

@section('content')

  
    {{ getQuestionnnaireGradeTranslation( $questionnaireItem1->grade) ?? '' }}

  
@endsection
