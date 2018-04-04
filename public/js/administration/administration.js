$(document).ready(function () {
    $("#sub-category-id").hide();

    function clearInputs() {
        $("#filename").val("");
        $("#description").val("");
        $("#category-id").val("");
        $("#sub-category-id").html("");
    }

    $("#add-document").click(function () {
        //$("#save-changes").hide();
        clearInputs();
        $("#modal-title").html("Add Document");

        let url = ORIGINAL_DOCUMENTS_URL;
        $("#document-form").attr("action", url);
        method = "POST";

        $("#document-modal").modal("show");

    });

        $.ajax({
            url: GET_SUB_CATEGORIES_URL + "/" + CATEGORY_ID,
            data: {
                category_id: 0
            },
            type: "GET",
            dataType: "JSON",
            success: function (subCategories) {
                console.log(subCategories);
                let options = "<option value='-1'>Select a sub category</option>";
                for (let i = 0; i < subCategories.length; i++) {
                    options += `<option value='${subCategories[i].id}'>${subCategories[i].sub_category}</option>`;
                }
                $("#sub-category-id").html(options);
                $("#sub-category-id").show();
            }
        });

    $("#document-form").submit(function (event) {
        event.preventDefault();

        let categoryId = $("#category-id").val().trim();
        let subCategoryId = $("#sub-category-id").val().trim();
        let filename = $("#filename").val().trim();
        let description = $("#description").val().trim();

        if (categoryId == -1) {
            swal({title: TITLE, type: "error", html: "Select a category"});
            return false;
        }

        if (subCategoryId == -1) {
            swal({title: TITLE, type: "error", html: "Select a sub category"});
            return false;
        }

        if (filename.length < 0) {
            swal({title: TITLE, type: "error", html: "Fill in the filename"});
            return false;
        }

        var form = $('#document-form')[0];

        var data = new FormData(form);

        data.append("category_id", categoryId);
        data.append("sub_category_id", subCategoryId);
        data.append("filename", filename);
        data.append("description", description);
        data.append("_method", method);

        $.ajax({
            url: url,
            enctype: 'multipart/form-data',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            type: "POST",
            dataType: "JSON",
            success: function (response) {
                console.log(response);

                clearInputs();

                theGrid.ajax.reload();

                swal({title: TITLE, type: response.type, html: response.text});

                $("#document-modal").modal("hide");
            },
            error: function (jqXhr, json, errorThrown) {
                var errors = jqXhr.responseJSON;
                var errorsHtml = '';
                $.each(errors['errors'], function (index, value) {
                    errorsHtml += '<ul class="list-group"><li class="list-group-item alert alert-danger">' + value + '</li></ul>';
                });

                swal({
                    title: TITLE,
                    html: errorsHtml,
                    width: 'auto',
                    confirmButtonText: 'Try again',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn',
                    cancelButtonClass: 'cancel-class',
                    showCancelButton: true,
                    closeOnConfirm: true,
                    closeOnCancel: true,
                    type: 'error'
                }, function (isConfirm) {

                });
            }
        });
    });

    theGrid = $('#thegrid').DataTable({
        "processing": true,
        //"bFilter": false,
        "serverSide": true,
        "ordering": true,
        "responsive": false,
        "language": {
            "sLoadingRecords": "LOADING"
        },
        "ajax": DOCUMENTS_GRID_URL,
        "sEmptyTable": "No data available",
        "columns": [
            {"data": "id", "name": "documents.id", "targets": 0},
            {"data": "filename", "name": "filename", "targets": 1},
            {"data": "description", "name": "description", "targets": 2},
            {"data": "category_id", "name": "documents.category_id", "targets": 3, searchable: false, orderable: false, visible: false},
            {"data": "category", "name": "category", "targets": 4},
            {"data": "sub_category_id", "name": "sub_category_id", "targets": 5, searchable: false, orderable: false, visible: false},
            {"data": "sub_category", "name": "sub_category", "targets": 6},
            {"data": "file_path", "name": "file_path", "targets": 7, render: function (data, type, row) {

                    return `<a target='_blank' href='${DOCUMENTS_URL + data}'>View</a>`;

                }
            },
            {"data": "document_status_id", "name": "document_status_id", "targets": 8, searchable: false, orderable: false, visible: false},
            {"data": "status", "name": "status", "targets": 9},
            {"data": "created_by", "name": "documents.created_by", "targets": 10},
            {"data": "created_at", "name": "documents.created_at", "targets": 11},
            {"data": "updated_by", "name": "documents.updated_by", "targets": 12},
            {"data": "updated_at", "name": "documents.updated_at", "targets": 13},
            {
                "data": "action", "name": "action", "targets": 14, searchable: false, orderable: false,
                render: function (data, type, row) {
                    if (canEditDocument) {

                        return `<a href='#' onclick='showEditDialog(${JSON.stringify(row)})' class='mdl-button mdl-js-button mdl-button--raised mdl-button--colored'>Edit</a>`;
                    } else {
                        return "";
                    }
                    //<a href='#' onclick='removePermission(${JSON.stringify(row.id)})' class='btn btn-danger btn-xs'>Remove</a>
                }
            }
        ],
        "drawCallback": function (settings) {
            var api = this.api();
            var rows = api.rows({page: 'current'}).nodes();
            var last = null;

            api.column(2, {page: 'current'}).data().each(function (group, i) {
                if (last !== group) {
                    $(rows).eq(i).before(
                            '<tr class="group"><td colspan="9"><b>' + group + '</b></td></tr>'
                            );

                    last = group;
                }
            });
        }
    });

});

/**
 * Show edit permission dialog
 * @returns {undefined}
 */
function showEditDialog(row) {
    //$("#save-changes").show();
    let categoryId = row.category_id;

    $("#id").val(row.id);
    $("#category-id").val(categoryId);
    
    $("#filename").val(row.filename);
    $("#description").val(row.description);


    $.ajax({
        url: GET_SUB_CATEGORIES_URL + "/" + categoryId,
        data: {
            category_id: categoryId
        },
        type: "GET",
        dataType: "JSON",
        success: function (subCategories) {
            console.log(subCategories);
            let options = "<option value='-1'>Select a sub category</option>";
            for (let i = 0; i < subCategories.length; i++) {
                options += `<option value='${subCategories[i].id}'>${subCategories[i].sub_category}</option>`;
            }
            $("#sub-category-id").html(options);
            $("#sub-category-id").show();
            
            $("#sub-category-id").val(row.sub_category_id);
        }
    });

    url = ORIGINAL_DOCUMENTS_URL + "/" + row.id;
    $("#document-form").attr("action", url);
    method = "PATCH";

    $("#modal-title").html("Edit Document");

    $("#document-modal").modal('show');
}

/*$('#form-documents').dropzone({
 autoQueue: false,
 });*/

/*Dropzone.autoDiscover = false;
 Dropzone.autoProcessQueue = false;
 
 var myDropzone = new Dropzone("#my-dropzone");
 myDropzone.on("addedfile", function (file) {
 let categoryId = $("#category-id").val();
 let subCategoryId = $("#sub-category-id").val();
 //console.log("Document Type ID: ",documentTypeId);
 if ((categoryId != "-1") && (subCategoryId != "-1")) {
 console.log("FILE: ", file);
 //myDropzone.enqueueFile(file);
 } else {
 swal({title: TITLE, type: "error", html: "Select the category and sub category"});
 }
 });
 
 myDropzone.on("sending", function (file, xhr, formData) {
 let categoryId = $("#category-id").val();
 let subCategoryId = $("#sub-category-id").val();
 formData.append("category_id", categoryId);
 formData.append("sub_category_id", subCategoryId);
 console.log(formData);
 });*/





