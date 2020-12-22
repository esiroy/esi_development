@extends('layouts.admin')

@section('content')

<div class="container bg-light px-0">
    @include('admin.modules.member.includes.menu')
    <div class="esi-box">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-light ">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Members</li>
            </ol>
        </nav>

        <div class="container bg-light">
            @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
            @elseif (session('error_message'))
            <div class="alert alert-danger">
                {{ session('error_message') }}
            </div>
            @endif

            <div class="card esi-card">
                <div class="card-header esi-card-header">
                    Member Details
                </div>
                <div class="card-body esi-card-body">
     
                </div>
                <!--[end] card body-->
            </div>
            
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script type="text/javascript">
    window.addEventListener('load', function() {
       //
    });

</script>
@endsection
