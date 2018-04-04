/**
 * Show edit plot dialog
 * @returns {undefined}
 */
function showEditDialog(row) {
    console.log(row);

    /**
     * Check if a permission is in a Role
     * @param id
     * @param rolePermissions
     * @returns {string}
     */
    function isPermissionInRole(id, rolePermissions) {
        for (var i = 0; i < rolePermissions.length; i++) {
            console.log("isInRole: ", rolePermissions[i]);
            if (id == rolePermissions[i].permission_id) {
                return "checked";
            }
        }
        return "";

    }

    $.ajax({
        url: ROLES_PERMISSIONS_URL + "/" + row.id,
        dataType: "JSON",
        success: function (permissions) {

            let allPermissions = permissions.all_permissions;
            let rolePermissions = permissions.roles_permissions;

            console.log("rolePermissions: ", rolePermissions);

            let html = "";

            for (var i = 0; i < allPermissions.length; i++) {
                let id = allPermissions[i].id;
                let checked = isPermissionInRole(id, rolePermissions);

                console.log("checked: ", checked);

                html += `<input name="permissions[]" type="checkbox" value="${id}" class="permissions" ${checked}>
                        <label for="view plots">${allPermissions[i].name}</label><br>`;

            }

            $("#permissions-list").html(html);

        }
    });

    $("#role-name").val(row.role);

    url = ROLES_URL + "/" + row.id;
    method = "PATCH";

    $("#modal-title").html("Edit Role");

    $("#role-modal").modal('show');
}

$(function () {

    var theGrid = null;
    theGrid = $('#thegrid').DataTable({
        ajax: ROLES_GRID_URL,
        processing: true,
        serverSide: true,
        sEmptyTable: "No data available",
        pageLength: 5,
        columns: [
            {data: "id", name: "id", target: 0, searchable: false, orderable: false, visible: false},
            {data: "role", name: "roles.name", target: 1, visible: false},
            {data: "permission", name: "permissions.name", target: 2},
            {data: "created_by", name: "roles.created_by", target: 3},
            {data: "created_at", name: "roles.created_at", target: 4},
            {data: "updated_by", name: "roles.updated_by", target: 5},
            {data: "updated_at", name: "roles.updated_at", target: 6},
            {
                data: "action",
                name: "action",
                target: 7,
                searchable: false,
                orderable: false,
                render: function (data, type, row) {

                    if (canEditRole) {
                        return `<a href='#' onclick='showEditDialog(${JSON.stringify(row)})' class='mdl-button mdl-js-button mdl-button--raised mdl-button--colored'>Edit</a> `;
                    } else {
                        return "";
                    }
                }
            }
        ],
        drawCallback: function (settings) {
            var api = this.api();
            var rows = api.rows({page: 'current'}).nodes();
            var last = null;

            api.column(1, {page: 'current'}).data().each(function (group, i) {
                if (last !== group) {
                    $(rows).eq(i).before(
                        '<tr class="group" style="background-color: #5bc0de;"><td colspan="6"><b>' + group + '</b></td></tr>'
                    );

                    last = group;
                }
            });
        }
    });

    $("#btn-add-dialog").click(function () {

        url = CREATE_ROLE_URL;
        method = "POST";

        $("#role-name").val("");
        $('input:checkbox[class=permissions]').removeAttr('checked');

        $("#modal-title").html("Add Role");

        $("#role-modal").modal("show");

    });

    function validate(roleName, permissions) {
        if (roleName.length <= 0) {
            swal({title: TITLE, text: "Please enter the role name", type: "error"});
            return false
        } else if (permissions.length == 0) {
            swal({title: TITLE, text: "Please select at least one permission", type: "error"});
            return false;
        }

        return true;
    }

    $("#save-changes").click(function () {
        let roleName = $("#role-name").val().trim();
        let permissions = [];

        $("input:checkbox[class=permissions]:checked").each(function () {
            permissions.push($(this).val());
        });

        if (validate(roleName, permissions) === false) {
            return false;
        }

        $.ajax({
            url: url,
            data: {_method: method, name: roleName, "permissions[]": permissions},
            type: "POST",
            dataType: "JSON",
            success: function (response) {
                console.log(response);
                theGrid.ajax.reload();
                swal({title: TITLE, type: response.type, html: response.text});

                $("#plot-modal").modal("hide");
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
                    $("#plot-modal").modal("show");
                });
            }
        });

    });


});