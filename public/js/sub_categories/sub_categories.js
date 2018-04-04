$(document).ready(function () {

    function clearInputs() {
        $("#category-id").val("-1");
        $("#sub-category").val("");
    }

    $("#add-sub-category").click(function () {
        $("#modal-title").html("Add Category");

        let url = ORIGINAL_SUB_CATEGORIES_URL;
        $("#sub-category-form").attr("action", url);
        method = "POST";

        $("#sub-category-modal").modal("show");

    });

    $("#sub-category-form").submit(function (event) {
        event.preventDefault();

        let categoryId = $("#category-id").val().trim();
        let subCategory = $("#sub-category").val().trim();

        if (subCategory.length < 0) {
            swal({title: TITLE, type: "error", html: "Fill in the sub category name"});
            return false;
        }
        
        if (categoryId == -1) {
            swal({title: TITLE, type: "error", html: "Select a category"});
            return false;
        }

        $.ajax({
            url: url,
            data: {
                _method: method,
                category_id: categoryId,
                sub_category: subCategory

            },
            type: "POST",
            dataType: "JSON",
            success: function (response) {
                console.log(response);

                clearInputs();

                theGrid.ajax.reload();

                swal({title: TITLE, type: response.type, html: response.text});

                $("#sub-category-modal").modal("hide");
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
        "ajax": SUB_CATEGORIES_GRID_URL,
        "sEmptyTable": "No data available",
        "columns": [
            {"data": "id", "name": "categories.id", searchable: false, ordarable: false, visible: false, "targets": 0},
            {"data": "sub_category", "name": "sub_category", "targets": 1},
            {"data": "category_id", "name": "category_id", searchable: false, ordarable: false, visible: false, "targets": 2},
            {"data": "category", "name": "category", "targets": 3},
            {"data": "created_by", "name": "categories.created_by", "targets": 4},
            {"data": "created_at", "name": "categories.created_at", "targets": 5},
            {"data": "updated_by", "name": "categories.updated_by", "targets": 6},
            {"data": "updated_at", "name": "categories.updated_at", "targets": 7},
            {
                "data": "action", "name": "action", "targets": 8, searchable: false, orderable: false,
                render: function (data, type, row) {
                    if (canEditSubCategory) {

                        return `<a href='#' onclick='showEditDialog(${JSON.stringify(row)})' class='mdl-button mdl-js-button mdl-button--raised mdl-button--colored'>Edit</a>`;
                    } else {
                        return "";
                    }
                }
            }
        ]
    });

});

/**
 * Show edit category dialog
 * @returns {undefined}
 */
function showEditDialog(row) {
    console.log(row);
    $("#id").val(row.id);
    $("#category-id").val(row.category_id);
    $("#sub-category").val(row.sub_category);

    url = ORIGINAL_SUB_CATEGORIES_URL + "/" + row.id;
    $("#category-form").attr("action", url);
    method = "PATCH";

    $("#modal-title").html("Edit Category");

    $("#sub-category-modal").modal('show');
}




