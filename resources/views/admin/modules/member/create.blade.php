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
        <div class="container">
            <create-member-component/>
        </div>
    </div>
</div>

</div>
@endsection
