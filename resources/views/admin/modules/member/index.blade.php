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
           <!--Member List -->

            <!--[start card] -->
            <div class="card">
                <div class="card-header">
                    Member List
                </div>
                <div class="card-body">
                    <div class="row">
                        <form class="form-inline" style="width:100%" method="GET">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="member_id" class="small col-4">ID:</label>
                                    <input name="member_id" type="text" class="form-control form-control-sm col-8"
                                     value="{{ request()->has('member_id') ? request()->get('member_id') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name" class="small col-4">Name:</label>
                                    <input name="name" type="text" class="form-control form-control-sm col-8" 
                                        value="{{ request()->has('name') ? request()->get('name') : '' }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">                                 
                                    <label for="email" class="small col-2">Email:</label>
                                    <input name="email" type="text" class="form-control form-control-sm col-4" value="{{ request()->has('email') ? request()->get('email') : '' }}">                                   
                             
                                    <input type="submit" class="btn btn-primary btn-sm col-1 ml-1" value="Go"></button>                                   
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="row">
                        <div class="col-12 pt-3">
                            @can('report_access', Auth::user())
                            <button type="button" class="btn btn-primary btn-sm">Generate Member List</button>
                            @endcan
                            <button type="button" class="btn btn-primary btn-sm">Sort Soon to Expire</button>
                            <button type="button" class="btn btn-primary btn-sm">Sort Expired</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 pt-3">
                           <member-list-component                               
                                :members="{{ json_encode($members) }}"
                                api_token="{{ Auth::user()->api_token }}"
                                csrf_token="{{ csrf_token() }}"                            

                                :can_member_access="{{ $can_member_access }}"                
                                :can_member_edit="{{ $can_member_edit }}"
                                :can_member_delete="{{ $can_member_delete }}"
                                :can_member_view="{{ $can_member_view }}"

                            />
                        </div>
                    </div>
                </div>
            </div><!--[end card]-->

            
            <!--Member List -->

            @if ($can_member_create)
            <member-create-component                
                :memberships="{{ json_encode($memberships) }}"
                :attributes="{{ json_encode($attributes) }}"
                :shifts="{{ json_encode($shifts) }}"

                :can_member_access="{{ $can_member_access }}"
                :can_member_create="{{ $can_member_create }}"                
                :can_member_edit="{{ $can_member_edit }}"
                :can_member_delete="{{ $can_member_delete }}"
                :can_member_view="{{ $can_member_view }}"

                api_token="{{ Auth::user()->api_token }}"
                csrf_token="{{ csrf_token() }}"             
            />
            @endif

        </div>

    </div>
</div>
@endsection


@section('scripts')
@parent
<script type="text/javascript">
    window.addEventListener('load', function() {
    });
</script>
@endsection    