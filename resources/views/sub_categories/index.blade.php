@extends('layouts.app')

@section('title', '| Sub Categories')

@section('content')

<div class="panel panel-default">

    <div class="panel-heading">
        <div class="row">
            <div class="col-lg-12">

            @can('add sub category')
            <a id="add-sub-category" href="#" class="btn btn-primary"><span class="fa fa-plus"></span> Add Sub Category</a>
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
                        <th>Sub Category</th>
                        <th>Category ID</th>
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
<div id="sub-category-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal-title">Add Category</h4>
            </div>
            <form id="sub-category-form" action="{{url('sub_categories/')}}" method="POST">
                <div class="modal-body">

                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="content">
                            
                            <div class="form-group row">
                                <label for="sub_category" class="col-lg-4 col-form-label">Sub Category<sup
                                        class="alert-danger">*</sup></label>
                                <div class="col-lg-6">
                                    <input class="form-control" type="text" value="" name="sub_category"
                                           id="sub-category" placeholder="e.g. Sub Category One">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="category_id" class="col-lg-4 col-form-label">Category <sup
                                        class="alert-danger">*</sup></label>
                                <div class="col-lg-6">
                                    <select name="category_id" id="category-id" class="form-control">
                                        <option value="-1">Select a category</option>
                                        @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->category}}</option>
                                        @endforeach
                                    </select>
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
    const ORIGINAL_SUB_CATEGORIES_URL = "{{url('sub_categories')}}";
    const SUB_CATEGORIES_URL = "{{url('sub_categories')}}";
    const SUB_CATEGORIES_GRID_URL = "{{url('sub_categories_grid') }}";
    const CREATE_SUB_CATEGORIES_URL = "{{ URL::to('sub_categories/create') }}";
    const DELETE_SUB_CATEGORY_URL = "{{url('sub_categories')}}";
    var method = "";
    var url = "";
    var canEditSubCategory = {{auth()->user()-> can('edit sub category')?"true":"false"}};
</script>
<script src="{{asset('js/sub_categories/sub_categories.js')}}"></script>
@endsection