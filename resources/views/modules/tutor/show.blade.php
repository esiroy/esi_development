@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">
    <div class="esi-box">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Tutor</li>
            </ol>
        </nav>

        <div class="container">

            <div class="card esi-card">
                <div class="card-header esi-card-header">Tutor Details</div>
                <div class="card-body">

                    <table border="0" cellspacing="9" cellpadding="2" class="tutorInformation">
                        <tbody>
                            <tr>
                                <td colspan="3">

                                    <div class="photo pt-1">
                                        @if ($tutor->filename == null)
                                        <img src="{{ Storage::url('user_images/noimage.jpg') }}" class="img-fluid border" alt="no photo uploaded" width="145px">
                                        @else
                                        <img src="{{ Storage::url("$tutor->original") }}" class="img-fluid border" alt="profile photo" width="145px">
                                        @endif
                                    </div>

                                </td>
                            </tr>

                            <tr valign="top">
                                <td class="red">&nbsp;</td>
                                <td>Skype Name</td>
                                <td>:</td>
                                <td><strong>{{ $tutor->skype_name }}</strong></td>
                            </tr>

                            <tr valign="top">
                                <td class="red">&nbsp;</td>
                                <td>氏名　(英字)</td>
                                <td>:</td>
                                <td><strong>{{ $tutor->user->firstname }}</strong></td>
                            </tr>

                            <tr valign="top">
                                <td class="red">&nbsp;</td>
                                <td>氏名　(漢字)</td>
                                <td>:</td>
                                <td><strong>{{ $tutor->user->japanese_firstname }}</strong></td>
                            </tr>


                            <tr valign="top">
                                <td class="red">&nbsp;</td>
                                <td>趣味</td>
                                <td>:</td>
                                <td>{{ $tutor->hobby }}</td>
                            </tr>

                            <tr valign="top">
                                <td class="red">&nbsp;</td>
                                <td>主な学歴、資格</td>
                                <td>:</td>
                                <td><strong>{{ $tutor->major }}　</strong></td>
                            </tr>

                            <tr valign="top">
                                <td class="red">&nbsp;</td>
                                <td>動画あいさつ</td>
                                <td>:</td>
                                <td>
                                    <strong>{!! $tutor->introduction ?? '' !!}</strong>
                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection


@section('styles')
@parent
    <style>
       a { 
           color: #c60000;
       }

       a:hover {
            color: #6e0000;
            text-decoration: none;
       }

    </style>
@endsection
