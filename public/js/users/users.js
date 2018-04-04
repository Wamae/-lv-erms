$(document).ready(function () {
    /**
     * Clear all input fields
     */
    function clearInputs() {
        $("#name").val("");
        $("#first-name").val("");
        $("#last-name").val("");
        $("#password").val("");
        $("#password-confirmation").val("");
    }

    $("#create-user").click(function () {
        $("#modal-title").html("Create User");

        let url = ORIGINAL_USERS_URL;
        $("#user-form").attr("action", url);
        method = "POST";

        $("#user-modal").modal("show");

    });

    function validate(email, name, firstName, lastName, password, confirmPassword,isEdit) {
        if (email.length <= 0) {
            swal({title: TITLE, text: "Please enter the email", type: "error"});
            return false
        } else if (name.length <= 0) {
            swal({title: TITLE, text: "Please enter the username", type: "error"});
            return false
        } else if (firstName.length == 0) {
            swal({title: TITLE, text: "Please enter the first name", type: "error"});
            return false;
        } else if (lastName.length == 0) {
            swal({title: TITLE, text: "Please enter the last name", type: "error"});
            return false;
        } else if (password.length == 0 && (isEdit == false)) {
            swal({title: TITLE, text: "Please enter the password", type: "error"});
            return false;
        } else if (confirmPassword.length == 0 && (isEdit == false)) {
            swal({title: TITLE, text: "Please enter the confirmation password", type: "error"});
            return false;
        }

        return true;
    }

    $("#user-form").submit(function (event) {
        event.preventDefault();

        let name = $("#name").val().trim();
        let email = $("#email").val().trim();
        let firstName = $("#first-name").val().trim();
        let lastName = $("#last-name").val().trim();
        let password = $("#password").val().trim();
        let confirmPassword = $("#password-confirmation").val().trim();
        let roles = $("#roles").val();
        console.log(roles);

        let isEdit = (method === "PATCH")?true:false;

        if (validate(email, name, firstName, lastName, password, confirmPassword,isEdit) === false) {
            return false;
        }


        $.ajax({
            url: url,
            data: {
                _method: method,
                name: name,
                email: email,
                first_name: firstName,
                last_name: lastName,
                password: password,
                password_confirmation: confirmPassword,
                roles: roles
            },
            type: "POST",
            dataType: "JSON",
            success: function (response) {
                console.log(response);

                clearInputs();

                theGrid.ajax.reload();

                swal({title: TITLE, type: response.type, html: response.text});

                $("#user-modal").modal("hide");
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
        "responsive": true,
        "language": {
            "sLoadingRecords": "LOADING"
        },
        "ajax": USERS_GRID_URL,
        "sEmptyTable": "No data available",
        "columnDefs": [
            {
                "targets": [0],
                "visible": false,
                "searchable": false
            }
        ],
        "columns": [
            {"data": "id", "name": "users.id", "targets": 0},
            {"data": "name", "name": "permissions.name", "targets": 1},
            {"data": "email", "name": "email", "targets": 2},
            {"data": "first_name", "name": "first_name", "targets": 3},
            {"data": "last_name", "name": "last_name", "targets": 4},
            {"data": "role_ids", "name": "role_ids", "targets": 5,searchable:false,orderable:false,visible:false},
            {"data": "role", "name": "roles.name", "targets": 6},
            {"data": "created_by", "name": "permissions.created_by", "targets": 7},
            {"data": "created_at", "name": "permissions.created_at", "targets": 8},
            {"data": "updated_by", "name": "permissions.updated_by", "targets": 9},
            {"data": "updated_at", "name": "permissions.updated_at", "targets": 10},
            {
                "data": "action", "name": "action", "targets": 11, searchable: false, orderable: false,
                render: function (data, type, row) {
                    if(canEditUser) {
                        return `<a href='#' onclick='showEditDialog(${JSON.stringify(row)})' class='mdl-button mdl-js-button mdl-button--raised mdl-button--colored'>Edit</a>`;
                    }else{
                        return "";
                    }
                }
            },
        ]
    });

    $('#roles').multiSelect();

    $('#user-modal').on('hidden.bs.modal', function () {
        $("#confirm-password-div").show();
        $("#password-div").show();
    })

});

/**
 * Show edit permission dialog
 * @returns {undefined}
 */
function showEditDialog(row) {
    $("#confirm-password-div").hide();
    $("#password-div").hide();
    //console.log(row.role_ids);
    let roleIds = row.role_ids;

    $('#roles').multiSelect('deselect_all');

    if(roleIds){
        let rolesArray = roleIds.split(",");
        console.log("ROLES: ",rolesArray);
        $('#roles').multiSelect('select', rolesArray);
        //$('#roles').multiSelect('select_all');
        console.log(rolesArray);
        /*$.each(roleIds.split(","), function(i,e){
            console.log(e);
            $("#roles option[value='" + e + "']").prop("selected", true);

        });*/
        //$("#roles").val(rolesArray);
    }

    $("#id").val(row.id);
    $("#email").val(row.email);
    $("#name").val(row.name);
    $("#first-name").val(row.first_name);
    $("#last-name").val(row.last_name);
    //$("#password").val(row.name);
    //$("#password-confirmation").val("");

    url = ORIGINAL_USERS_URL + "/" + row.id;
    $("#user-form").attr("action", url);
    method = "PATCH";

    $("#modal-title").html("Edit Permission");

    $("#user-modal").modal('show');
}

/**
 * Remove a document
 * @param permissionId
 */
/*function removePermission(permissionId) {
    $.ajax({
        url: ORIGINAL_PERMISSIONS_URL + "/" + permissionId,
        type: "POST",
        //contentType: "JSON",
        data: {"_token" : $("input[name='_token']").val(),_method:"DELETE"},
        success: function (response) {
            console.log(response == "1");
            if (response == "1") {
                theGrid.ajax.reload();
                swal({title: TITLE, type: "success", html: "Document Removed Successfully"});
            } else {
                swal({title: TITLE, type: "error", html: "Failed to Remove Document"});
            }
        }

    });
}*/



