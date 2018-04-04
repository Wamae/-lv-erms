@extends('layouts.app')

@section('title', '| Categories')

@section('content')

<div class="panel panel-default">

    <div class="panel-heading">
        <div class="row">
            <div class="col-lg-12">{{ $title }}</div>

            @can('add category')
            <a id="add-category" href="#" class="btn btn-primary"><span class="fa fa-plus"></span> Add Category</a>
            @endcan

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
                        <th>Category</th>
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
<div id="category-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal-title">Add Category</h4>
            </div>
            <form id="category-form" action="{{url('categories/')}}" method="POST">
                <div class="modal-body">

                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="content">
                            
                            <div class="form-group row">
                                <label for="category" class="col-lg-4 col-form-label">Category<sup
                                        class="alert-danger">*</sup></label>
                                <div class="col-lg-6">
                                    <input class="form-control" type="text" value="" name="category"
                                           id="category" placeholder="e.g. Category One">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button id="save-changes" type="submit" class="btn btn-primary">Save Changes
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
    const ORIGINAL_CATEGORIES_URL = "{{url('categories')}}";
    const CATEGORIES_URL = "{{url('categories')}}";
    const CATEGORIES_GRID_URL = "{{url('categories_grid') }}";
    const CREATE_CATEGORIES_URL = "{{ URL::to('categories/create') }}";
    const DELETE_CATEGORY_URL = "{{url('categories')}}";
    var method = "";
    var url = "";
    var canEditCategory = {{auth()->user()-> can('edit category')?"true":"false"}};
</script>
<script src="{{asset('js/categories/categories.js')}}"></script>
@endsection