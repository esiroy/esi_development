@extends('layouts.esi-app')

@section('content')
<div class="container bg-light">
    <div class="esi-box mb-5">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Writing</li>
            </ol>
        </nav>

        <div class="container pb-5">
            <div class="row">
                <!--[start sidebar]-->
                @include('modules.member.sidebar.index')
                <!--[end sidebar]-->           

                <div class="col-md-9">
                    <div class="row">

                        <div class="col-12  message-container">

                            <div class="card esi-card  mb-3">
                                <h5 class="card-header esi-card-header  py-2">ありがとうございます。 エントリが正常に送信されました。</h5>
                                <div class="card-body pb-5">
                                    <div class="mt-4">
                                        <p class="text-center">                                           
                                            48時間以内に添削結果を送信します。
                                        </p>
                                        <div class="text-center">
                                            <a type="submit" href="{{ url('/writing') }}" class="btn-pink col-4 text-white" >ライティングサービスに戻る</a>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>


                        </div>

                    </div>
                </div>

            </div>
        </div>  


    </div>
</div>
@endsection