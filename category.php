<?php
// category.php

include('database_connection.php');

if (!isset($_SESSION['type'])) {
    header('location:index.php');
}

if ($_SESSION['type'] != 'master') {
    header('location:index.php');
}

$title = 'Category';

include('header.php');

?>

<div class="my-3">
    <span id="alert_action"></span>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-8">
                        <h3 class="card-title">Category List</h3>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-4" align="right">
                        <button type="button" name="add" id="add_button" data-toggle="modal" data-target="#category_modal" class="btn btn-success">Add</button>
                    </div>
                </div>
                <div style="clear:both"></div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="table-responsive">
                        <table id="category_data" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Category Name</th>
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
</div>

<div id="category_modal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="category_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Add Category</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <label for="">Enter Category Name</label>
                    <input type="text" name="category_name" id="category_name" class="form-control" required>
                    <input type="hidden" name="old_category_name" id="old_category_name">
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="category_id" id="category_id">
                    <input type="hidden" name="btn_action" id="btn_action">
                    <input type="submit" name="action" id="action" class="btn btn-info" value="Add">
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#add_button').click(function() {
            $('#category_form')[0].reset();
            $('.modal-title').html("<i class='fa fa-plus'> Add Category</i>");
            $('#action').val('Add');
            $('#btn_action').val('Add');
            // $('#alert_action').removeClass('display_none');
        });

        // Load Edit/update form view
        $(document).on('click', '.update', function() {
            const category_id = $(this).attr('id');
            const btn_action = 'fetch_single';
            const name = $(this).data('name');
            $.ajax({
                url: 'category_action.php',
                method: "POST",
                data: {
                    category_id: category_id,
                    btn_action: btn_action,
                    name: name
                },
                dataType: "json",
                success: function(data) {
                    $('#category_modal').modal('show');
                    $('#category_name').val(data.category_name);
                    $('.modal-title').html('Edit Category');
                    $('#category_id').val(category_id);
                    $('#btn_action').val('Edit');
                    $('#action').val('Edit');
                    $('#old_category_name').val(data.category_name);
                }
            });
        });

        // Delete / Inactive Method
        $(document).on('click', '.delete', function() {
            const category_id = $(this).attr('id');
            const status = $(this).data('status');
            const btn_action = 'delete';
            const category_name = $(this).data('name');

            Swal.fire({
                title: 'Are you sure?',
                html: `to change status on <b>${category_name}</b>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        $.ajax({
                            url: "category_action.php",
                            method: "POST",
                            data: {
                                category_id: category_id,
                                btn_action: btn_action,
                                status: status,
                                category_name: category_name
                            },
                            success: function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    html: data
                                });
                                categorydataTable.ajax.reload();
                            }
                        })
                    )
                }
            })
        })

        // Submit Action
        $(document).on('submit', '#category_form', function(event) {
            event.preventDefault();
            $('#action').attr('disabled', 'disabled');
            var form_data = $(this).serialize();
            $.ajax({
                url: "category_action.php",
                method: "POST",
                data: form_data,
                success: function(data) {
                    $('#category_form')[0].reset();
                    $('#category_modal').modal('hide');
                    $('#alert_action').fadeIn().html(`<div class="alert alert-success">${data}</div>`);
                    $('#action').attr('disabled', false);
                    // setTimeout(() => {
                    //     $('#alert_action').addClass('display_none');
                    // }, 4000);
                    categorydataTable.ajax.reload();
                }
            })
        })

        var categorydataTable = $('#category_data').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: 'category_fetch.php',
                type: "POST",
            },
            "columnDefs": [{
                "target": [3, 4],
                "orderable": false
            }],
            "pageLength": 25,
        });
    });
</script>

<?php
include('footer.php');
?>