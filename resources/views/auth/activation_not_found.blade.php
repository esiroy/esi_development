@extends('layouts.esi-public')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 py-2">

            <div class="card">
                <div class="text-center">
                    <a href="{{ url('/') }}">
                    <img title="My Tutor" alt="My Tutor" src="{{ url('images/title_full.png') }}" style="vertical-align: middle;margin-top: 27px; margin-right: 11px;height: 64px; "></a> &nbsp; 
                    <span style="font-weight: bolder;position: relative;top: 20px;">無料会員登録</span> 
                </div>

                <hr style="background-color: rgb(0,175,239);height: 3px;border: none;">

              


                <div class="row justify-content-center  mx-2">
                    <div class="col-md-12">
                        <div class="card esi-card my-5">
                            <div class="card-header esi-card-header">{{ __('見つかりません') }}</div>
                            <div class="card-body py-3">

                                <div id="confirmation-message" class="mb-5 text-center">
                                    
                                    <p>アクティベーションコードが見つかりません</p>

                                    <p>Activation Code Not Found</p>
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
