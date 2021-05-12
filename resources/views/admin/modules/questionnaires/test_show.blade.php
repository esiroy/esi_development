@extends('layouts.admin')

@section('content')

    @if ($userImage == null)
        <img src="{{ Storage::url('user_images/noimage.jpg') }}" class="img-fluid border" alt="no photo uploaded">
    @else
        <img src="{{ Storage::url("$userImage->original") }}" class="img-fluid border" alt="profile photo">
    @endif

@endsection
