<?php
// brand.php
include('database_connection.php');

include('function.php');

if (!isset($_SESSION['type'])) {
    header('location:login.php');
}

if ($_SESSION['type'] != 'master') {
    header('location:index.php');
}

$title = 'Brand';

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
                    <div class="col-md-10">
                        <h3 class="card-title">Brand List</h3>
                    </div>
                    <div class="col-md-2" align="right">
                        <button type="button" name="add" id="add_button" class="btn btn-success btn-xs">Add</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="brand_data" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Category</th>
                                <th>Brand Name</th>
                                <th>Status</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="brand_modal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="brand_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Add Brand</h4>
                    <button class="close" type="button" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            <?= fill_category_list($connect) ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="brand_name" id="brand_name" class="form-control" required>
                        <input type="hidden" name="old_brand_name" id="old_brand_name">
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="brand_id" id="brand_id">
                        <input type="hidden" name="btn_action" id="btn_action">
                        <input type="submit" name="action" id="action" class="btn btn-info" value="Add">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#add_button').click(function() {
            $('#brand_modal').modal('show');
            $('#brand_form')[0].reset();
            $('.modal-title').html('<i class="fa fa-plus"></i> Add Brand');
            $('#action').val('Add');
            $('#btn_action').val('Add');
        })

        // Fetch single
        $(document).on('click', '.update', function() {
            const brand_id = $(this).attr("id");
            const btn_action = 'fetch_single';
            const name = $(this).data('name');

            $.ajax({
                url: "brand_action.php",
                method: "POST",
                data: {
                    brand_id: brand_id,
                    btn_action: btn_action,
                    // name: name
                },
                dataType: "json",
                success: function(data) {
                    $('#brand_modal').modal('show');
                    $('#brand_id').val(brand_id);
                    $('#category_id').val(data.category_id);
                    $('#brand_name').val(data.brand_name);
                    $('.modal_title').html('Brand Edit');
                    $('#btn_action').val('Edit');
                    $('#action').val('Edit');
                    $('#old_brand_name').val(data.brand_name);
                }
            })
        })

        // Delete / Change Status
        $(document).on('click', '.delete', function() {
            const brand_id = $(this).attr("id");
            const btn_action = 'delete';
            const status = $(this).data('status');
            const brand_name = $(this).data('name');
            Swal.fire({
                title: 'Are you sure?',
                html: `to change status on <b>${brand_name}</b>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        $.ajax({
                            url: "brand_action.php",
                            method: "POST",
                            data: {
                                brand_id: brand_id,
                                btn_action: btn_action,
                                status: status,
                                brand_name: brand_name
                            },
                            success: function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    html: data
                                })
                                branddataTable.ajax.reload();
                            }
                        })
                    )
                }
            })
        });

        // Add brand submit
        $(document).on('submit', '#brand_form', function(event) {
            event.preventDefault();
            $('#action').attr('disabled', 'disabled');
            const form_data = $(this).serialize();
            $.ajax({
                url: "brand_action.php",
                method: "POST",
                data: form_data,
                success: function(data) {
                    $('#brand_form')[0].reset();
                    $('#brand_modal').modal('hide');
                    $('#alert_action').fadeIn().html(`<div class="alert alert-success">${data}</div>`);
                    $('#action').attr('disabled', false);
                    branddataTable.ajax.reload();
                }
            });
        });

        var branddataTable = $('#brand_data').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "brand_fetch.php",
                type: "POST",
            },
            "columnDefs": [{
                "targets": [4, 5],
                "orderable": false,
            }, ],
            "pageLength": 10,
        });
    });
</script>


<?php
include('footer.php');
?>