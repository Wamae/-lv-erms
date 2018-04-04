{{-- \resources\views\roles\index.blade.php --}}
@extends('layouts.app')

@section('title', '| User Group')

@section('content')

<div class="panel panel-default">

    <div class="panel-heading">
        <div class="row">
            <div class="col-lg-12">

            @can('add role')
            <a id="btn-add-dialog" href="#" class="btn btn-primary"><span class="fa fa-plus"></span> Add User Group</a>
            @endcan
            &nbsp;
            @can('view users')
            <a href="{{ route('users.index') }}" class="btn btn-info">Users</a>
            @endcan
            &nbsp;
            @can('view permissions')
            <a href="{{ route('permissions.index') }}" class="btn btn-info">Permissions</a>
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
                    <th>User Group</th>
                    <th>Permissions</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Updated By</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
                </thead>

            </table>
        </div>
    </div>

</div>

<!-- Modal -->
<div id="role-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal-title">Add Role</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', null, array('id'=>'role-name','class' => 'form-control','placeholder'=>'Enter role name')) }}
                </div>

                <h5><b>Assign Permissions</b></h5>

                <div id="permissions-list" class='form-group'>
                    @foreach ($permissions as $permission)
                    
                    {{ Form::checkbox('permissions[]', $permission->id ,false,array('class'=>'permissions')) }}
                    {{ Form::label($permission->name, ucfirst($permission->name)) }}<br>

                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" data-dismiss="modal">Close</button>
                <button id="save-changes" type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" data-dismiss="modal">Save Changes
                </button>
            </div>
        </div>

    </div>
</div>

@endsection
@section('scripts')
<script>
    const TITLE = "{{$title}}";
    const ROLES_URL = "{{url('roles')}}";
    const ROLES_PERMISSIONS_URL = "{{url('role_permissions')}}";
    const ROLES_GRID_URL = "{{url('roles_grid') }}";
    const CREATE_ROLE_URL = "{{ URL::to('roles/create') }}";
    var method = "";
    var url = "";
    var canEditRole = {{auth()->user()->can('edit role')?"true":"false"}};
</script>
<script src="{{asset('js/roles/roles.js')}}"></script>
@endsection