@extends('layouts.app')

@section('title', '| Permissions')

@section('content')

<div class="panel panel-default">

    <div class="panel-heading">
        <div class="row">
            <div class="col-lg-12">

            @can('add permission')
            <a id="add-permission" href="#" class="btn btn-primary"><span class="fa fa-plus"></span> Add Permission</a>
            @endcan
            &nbsp;
            @can('view users')
            <a href="{{ route('users.index') }}" class="btn btn-info">Users</a>
            @endcan
            &nbsp;
            @can('add role')
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
                        <th>Module ID</th>
                        <th>Module</th>
                        <th>Permission</th>
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
<div id="permission-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal-title">Add Permission</h4>
            </div>
            <form id="permission-form" action="{{url('plots/')}}" method="POST">
                <div class="modal-body">

                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="content">

                            <div class="form-group row">
                                <label for="module_id" class="col-lg-4 col-form-label">Module <sup
                                        class="alert-danger">*</sup></label>
                                <div class="col-lg-6">
                                    <select name="module_id" id="module-id" class="form-control">
                                        <option value="-1">Select a module</option>
                                        @foreach($modules as $module)
                                        <option value="{{$module->id}}">{{$module->module}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="grant_number" class="col-lg-4 col-form-label">Permission Name<sup
                                        class="alert-danger">*</sup></label>
                                <div class="col-lg-6">
                                    <input class="form-control" type="text" value="" name="name"
                                           id="name" placeholder="e.g. create user">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" data-dismiss="modal">Close</button>
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
    const ORIGINAL_PERMISSIONS_URL = "{{url('permissions')}}";
    const PERMISSIONS_URL = "{{url('permissions')}}";
    const PERMISSIONS_GRID_URL = "{{url('permissions_grid') }}";
    const CREATE_PERMISSIONS_URL = "{{ URL::to('permissions/create') }}";
    const DELETE_PERMISSION_URL = "{{url('plot_documents')}}";
    var method = "";
    var url = "";
    var canEditPermission = {{auth()->user()-> can('edit permission')?"true":"false"}};
</script>
<script src="{{asset('js/permissions/permissions.js')}}"></script>
@endsection