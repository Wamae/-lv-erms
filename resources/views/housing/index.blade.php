@extends('layouts.app')

@section('title', '| Housing')

@section('content')

<div class="panel panel-default">

    <div class="panel-heading">
        <div class="row">
            <div class="col-lg-12">
            @can('add document')
            <a id="add-document" href="#" class="btn btn-primary"><span class="fa fa-plus"></span> Create Document</a>
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
                        <th>Name</th>
                        <th>Description</th>
                        <th>Category ID</th>
                        <th>Category</th>
                        <th>Sub Category ID</th>
                        <th>Sub Category</th>
                        <th>Uploaded File</th>
                        <th>Document Status ID</th>
                        <th>Status</th>
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
<div id="document-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal-title">Add Document</h4>
            </div>
            <form id="document-form" action="{{url('documents/')}}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">

                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="content">

                            <div class="form-group row">
                                <label for="sub_category_id" class="col-lg-4 col-form-label">Sub Category <sup
                                        class="alert-danger">*</sup></label>
                                <div id="sub-category-id-div" class="col-lg-6">
                                    <select id="sub-category-id" name="sub_category_id" class="form-control">
                                        <option value="-1">Select a sub category</option>
                                        @foreach($subCategories as $subCategory)
                                        <option value="{{$subCategory->id}}">{{$subCategory->sub_category}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="filename" class="col-lg-4 col-form-label">Filename<sup
                                        class="alert-danger">*</sup></label>
                                <div class="col-lg-6">
                                    <input class="form-control" type="text" value="" name="filename"
                                           id="filename" placeholder="e.g. Document123">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="filename" class="col-lg-4 col-form-label">Description </label>
                                <div class="col-lg-6">
                                    <input class="form-control" type="text" value="" name="description"
                                           id="description" placeholder="e.g.">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="filename" class="col-lg-4 col-form-label">Upload<sup
                                        class="alert-danger">*</sup></label>
                                <div class="col-lg-6">
                                    <input type="file" accept="image/*" name="documents"/>
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
    const ORIGINAL_MEMOS_URL = "{{url('housing')}}";
    const GET_SUB_CATEGORIES_URL = "{{url('get_sub_categories')}}";
    const DOCUMENTS_URL = "{{url('/uploads/documents')}}";
    const MEMOS_GRID_URL = "{{url('housing_grid') }}";
    const CREATE_MEMOS_URL = "{{ URL::to('housing/create') }}";
    const DELETE_MEMOS_URL = "{{url('memos')}}";
    var method = "";
    var url = "";
    var canEditDocument = {{auth()->user()-> can('edit document')?"true":"false"}};</script>
<script src="{{asset('js/housing/housing.js')}}"></script>
@endsection