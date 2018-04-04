$(document).ready(function () {

    function clearInputs() {
        $("#category").val("");
    }

    $("#add-category").click(function () {
        $("#modal-title").html("Add Category");

        let url = ORIGINAL_CATEGORIES_URL;
        $("#category-form").attr("action", url);
        method = "POST";

        $("#category-modal").modal("show");

    });

    $("#category-form").submit(function (event) {
        event.preventDefault();

        let category = $("#category").val().trim();
        console.log(category);

        if (category.length === 0) {
            swal({title: TITLE, type: "error", html: "Fill in the category name"});
            return false;
        }

        $.ajax({
            url: url,
            data: {
                _method: method,
                category: category

            },
            type: "POST",
            dataType: "JSON",
            success: function (response) {
                console.log(response);

                clearInputs();

                theGrid.ajax.reload();

                swal({title: TITLE, type: response.type, html: response.text});

                $("#category-modal").modal("hide");
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
        "ajax": CATEGORIES_GRID_URL,
        "sEmptyTable": "No data available",
        "columns": [
            {"data": "id", "name": "categories.id", searchable: false, ordarable: false, visible: false, "targets": 0},
            {"data": "category", "name": "category", "targets": 1},
            {"data": "created_by", "name": "categories.created_by", "targets": 2},
            {"data": "created_at", "name": "categories.created_at", "targets": 3},
            {"data": "updated_by", "name": "categories.updated_by", "targets": 4},
            {"data": "updated_at", "name": "categories.updated_at", "targets": 5},
            {
                "data": "action", "name": "action", "targets": 6, searchable: false, orderable: false,
                render: function (data, type, row) {
                    if (canEditCategory) {

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
    $("#id").val(row.id);
    $("#category").val(row.category);

    url = ORIGINAL_CATEGORIES_URL + "/" + row.id;
    $("#category-form").attr("action", url);
    method = "PATCH";

    $("#modal-title").html("Edit Category");

    $("#category-modal").modal('show');
}




