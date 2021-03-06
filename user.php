<?php
// user.php
include('database_connection.php');

if (!isset($_SESSION['type'])) {
    header('location:login.php');
}

if ($_SESSION['type'] != 'master') {
    header('location:index.php');
}

$title = 'User List';

include('header.php');

?>

<div class="mt-3">
    <span id="alert_action"></span>
</div>
<div class="row">
    <div class="col-lg-12 my-3">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                        <h3 class="card-heading"><?= $title ?></h3>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align="right">
                        <button type="button" name="add" id="add_button" data-toggle="modal" data-target="#userModal" class="btn btn-success btn-xs">Add</button>
                    </div>
                </div>

                <div class="clear:both"></div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 table-responsive">
                        <table id="user_data" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Email</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Edit</th>
                                    <th>Change Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add user modal -->
    <div id="userModal" class="modal fade">
        <div class="modal-dialog">
            <form method="post" id="user_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fa fa-plus"></i> Add User</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Enter User Name</label>
                            <input type="text" name="user_name" id="user_name" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label>Enter User Email</label>
                            <input type="email" name="user_email" id="user_email" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label>Enter User Password</label>
                            <input type="password" name="user_password" id="user_password" class="form-control" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="user_id" id="user_id" />
                        <input type="hidden" name="btn_action" id="btn_action" />
                        <input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var userdataTable = $('#user_data').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "user_fetch.php",
                type: "POST",
            },
            "columnDefs": [{
                "target": [4, 5],
                "orderable": false
            }],
            "pageLength": 20,
        });

        $('#add_button').click(function() {
            $('#user_form')[0].reset();
            $('.modal-title').html(`<i class="fa fa-plus"></i> Add User`);
            $('#action').val("Add");
            $('#btn_action').val('Add');
        });

        $(document).on('submit', '#user_form', function(event) {
            event.preventDefault()
            $('#alert_action').removeClass('display_none');
            $('#action').attr('disabled', 'disabled');
            var form_data = $(this).serialize();
            $.ajax({
                url: "user_action.php",
                method: "POST",
                data: form_data,
                success: function(data) {
                    $('#user_form')[0].reset();
                    $('#userModal').modal('hide');
                    $('#alert_action').fadeIn().html(`<div class="alert alert-success">${data}</div>`);
                    $('#action').attr('disabled', false);
                    setTimeout(() => {
                        $('#alert_action').addClass('display_none').fadeOut();
                    }, 4000);
                    userdataTable.ajax.reload();
                }
            })
        });

        $(document).on('click', '.update', function() {
            var user_id = $(this).attr("id");
            var btn_action = 'fetch_single';
            $.ajax({
                url: "user_action.php",
                method: "POST",
                data: {
                    user_id: user_id,
                    btn_action: btn_action
                },
                dataType: "json",
                success: function(data) {
                    $('#userModal').modal('show');
                    $('#user_name').val(data.user_name);
                    $('#user_email').val(data.user_email);
                    $('.modal-title').html(`<i class="fa fa-pencil-square-o"></i> Edit User`);
                    $('#user_id').val(user_id);
                    $('#btn_action').val('Edit');
                    $('#action').val('Edit');
                    $('#btn_password').attr('required', false);

                }
            });
        });

        // Change Status
        $(document).on('click', '.delete', function() {
            const user_id = $(this).attr('id');
            const status = $(this).data('status');
            const btn_action = 'delete';
            const user_name = $(this).data('name');

            Swal.fire({
                title: 'Are you sure?',
                html: `to change status on <b>${user_name}</b>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        $.ajax({
                            url: "user_action.php",
                            method: "POST",
                            data: {
                                user_id: user_id,
                                status: status,
                                btn_action: btn_action
                            },
                            success: function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    html: data
                                });
                                userdataTable.ajax.reload();
                            }
                        })
                    )
                }
            })

        });

    });
</script>


<?php
include('footer.php');
?>