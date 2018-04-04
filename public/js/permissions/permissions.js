$(document).ready(function () {

    function clearInputs() {
        $("#name").val("");
        $("#module_id").val("");
    }

    $("#add-permission").click(function () {
        $("#modal-title").html("Add Permission");

        let url = ORIGINAL_PERMISSIONS_URL;
        $("#permission-form").attr("action", url);
        method = "POST";

        $("#permission-modal").modal("show");

    });

    $("#permission-form").submit(function (event) {
        event.preventDefault();

        let moduleId = $("#module-id").val().trim();
        let name = $("#name").val().trim();

        if (moduleId == -1) {
            swal({title: TITLE, type: "error", html: "Select a module"});
            return false;
        }
        
        if (name.length < 0) {
            swal({title: TITLE, type: "error", html: "Fill in the permission name"});
            return false;
        }

        $.ajax({
            url: url,
            data: {
                _method: method,
                module_id: moduleId,
                name: name
                
            },
            type: "POST",
            dataType: "JSON",
            success: function (response) {
                console.log(response);

                clearInputs();

                theGrid.ajax.reload();

                swal({title: TITLE, type: response.type, html: response.text});

                $("#permission-modal").modal("hide");
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
        "ajax": PERMISSIONS_GRID_URL,
        "sEmptyTable": "No data available",
        "columns": [
            {"data": "id", "name": "permissions.id", "targets": 0},
            {"data": "module_id", "name": "module_id", "targets": 1,searchable: false,orderable: false,visible:false},
            {"data": "module", "name": "module", "targets": 2,"visible": false},
            {"data": "name", "name": "permissions.name", "targets": 3},
            {"data": "created_by", "name": "permissions.created_by", "targets": 4},
            {"data": "created_at", "name": "permissions.created_at", "targets": 5},
            {"data": "updated_by", "name": "permissions.updated_by", "targets": 6},
            {"data": "updated_at", "name": "permissions.updated_at", "targets": 7},
            {
                "data": "action", "name": "action", "targets": 8, searchable: false, orderable: false,
                render: function (data, type, row) {
                    if(canEditPermission) {

                        return `<a href='#' onclick='showEditDialog(${JSON.stringify(row)})' class='mdl-button mdl-js-button mdl-button--raised mdl-button--colored'>Edit</a>`;
                    }else{
                        return "";
                    }
                //<a href='#' onclick='removePermission(${JSON.stringify(row.id)})' class='btn btn-danger btn-xs'>Remove</a>
                }
            }
        ],
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
 
            api.column(2, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="9"><b>'+group+'</b></td></tr>'
                    );
 
                    last = group;
                }
            } );
        }
    });

});

/**
 * Show edit permission dialog
 * @returns {undefined}
 */
function showEditDialog(row) {
    $("#id").val(row.id);
    $("#module-id").val(row.module_id);
    $("#name").val(row.name);

    url = ORIGINAL_PERMISSIONS_URL + "/" + row.id;
    $("#permission-form").attr("action", url);
    method = "PATCH";

    $("#modal-title").html("Edit Permission");

    $("#permission-modal").modal('show');
}

/**
 * Remove a document
 * @param permissionId
 */
function removePermission(permissionId) {
    $.ajax({
        url: ORIGINAL_PERMISSIONS_URL + "/" + permissionId,
        type: "POST",
        //contentType: "JSON",
        data: {"_token" : $("input[name='_token']").val(),_method:"DELETE"},
        success: function (response) {
            console.log(response == "1");
            if (response == "1") {
                theGrid.ajax.reload();
                swal({title: TITLE, type: "success", html: "Document removed successfully"});
            } else {
                swal({title: TITLE, type: "error", html: "Failed to remove document"});
            }
        }

    });
}



