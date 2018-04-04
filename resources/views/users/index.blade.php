@extends('layouts.app')

@section('title', '| Permissions')

@section('content')

<div class="panel panel-default">

    <div class="panel-heading">
        <div class="row">
            <div class="col-lg-9">

            @can('add user')
            <a id="create-user" href="#" class="btn btn-primary"><span class="fa fa-plus"></span> Create User</a>
            @endcan
            &nbsp;
            @can('view permissions')
            <a href="{{ route('permissions.index') }}" class="btn btn-info">Permissions</a>
            @endcan
            &nbsp;
            @can('view roles')
            <a href="{{ route('roles.index') }}" class="btn btn-info">Roles</a>
            @endcan
            
            </div>

        </div>
    </div>
    <div class="panel-body">
        <div class="non-print search-panel ">
        </div>
        <br>
        <div class="">
            <table id="thegrid" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Role_IDs</th>
                    <th>Role</th>
                    <th>Created By</th>
                    <th>Created On</th>
                    <th>Updated By</th>
                    <th>Updated On</th>
                    <th>Actions</th>
                </tr>
                </thead>

            </table>
        </div>
    </div>

</div>

<!-- Modal -->
<div id="user-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal-title">Create User</h4>
            </div>
            <form id="user-form" action="{{url('users/')}}" method="POST">
                <div class="modal-body">

                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="content">

                            <div class="form-group row">
                                <label for="grant_number" class="col-lg-4 col-form-label">Email <sup
                                            class="alert-danger">*</sup></label>
                                <div class="col-lg-6">
                                    <input class="form-control" type="email" value="" name="email"
                                           id="email" placeholder="e.g. john.doe@gmail.com">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="grant_number" class="col-lg-4 col-form-label">Username <sup
                                            class="alert-danger">*</sup></label>
                                <div class="col-lg-6">
                                    <input class="form-control" type="text" value="" name="name"
                                           id="name" placeholder="e.g. john.doe">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="grant_number" class="col-lg-4 col-form-label">First Name <sup
                                            class="alert-danger">*</sup></label>
                                <div class="col-lg-6">
                                    <input class="form-control" type="text" value="" name="first_name"
                                           id="first-name" placeholder="e.g. John">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="grant_number" class="col-lg-4 col-form-label">Last Name <sup
                                            class="alert-danger">*</sup></label>
                                <div class="col-lg-6">
                                    <input class="form-control" type="text" value="" name="last_name"
                                           id="last-name" placeholder="e.g. Doe">
                                </div>
                            </div>

                            <div class="form-group row" id="password-div">
                                <label for="password" class="col-lg-4 col-form-label">Password <sup
                                            class="alert-danger">*</sup></label>
                                <div class="col-lg-6">
                                    <input class="form-control" type="password" value="" name="password"
                                           id="password" placeholder="Enter password">
                                </div>
                            </div>

                            <div class="form-group row"  id="confirm-password-div">
                                <label for="grant_number" class="col-lg-4 col-form-label">Confirm Password <sup
                                            class="alert-danger">*</sup></label>
                                <div class="col-lg-6">
                                    <input class="form-control" type="password" value="" name="password_confirmation"
                                           id="password-confirmation" placeholder="Confirm password">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="grant_number" class="col-lg-4 col-form-label">Roles</label>
                                <div class="col-lg-6">
                                    <select name="roles[]" id="roles" multiple class="form-control">
                                        <option value="-1" selected disabled>Select roles</option>
                                        @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{ucwords($role->name)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="save-changes" type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">Save Changes
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

@endsection
@section('scripts')
<script>
    const TITLE = "{{$title}}";
    const ORIGINAL_USERS_URL = "{{url('users')}}";
    const USERS_URL = "{{url('users')}}";
    const USERS_GRID_URL = "{{url('users_grid') }}";
    const CREATE_USERS_URL = "{{ URL::to('users/create') }}";
    var method = "";
    var url = "";
    var canEditUser = {{auth()->user()->can('edit user')?"true":"false"}};
</script>
<script src="{{asset('js/users/users.js')}}"></script>
@endsection