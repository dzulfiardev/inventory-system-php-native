<?php
// order.php

include('database_connection.php');
include('function.php');

if (!isset($_SESSION['type'])) {
    header('location:login.php');
}

$title = 'Order';
include('header.php');

?>

<!-- Date Picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" />
<!-- Bootstrap Select -->
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<script>
    $(document).ready(function() {
        $('#inventory_order_date').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true
        });
    });
</script>

<div class="my-3">
    <span id="alert_action"></span>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-10">
                        <h3>Order List</h3>
                    </div>
                    <div class="col-md-2" align="right"><button type="button" name="add" id="add_button" class="btn btn-success">Add</button></div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="order_data" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Order Id</th>
                                <th>Customer Name</th>
                                <th>Total Amount</th>
                                <th>Payment Status</th>
                                <th>Order Status</th>
                                <th>Order Date</th>
                                <?php if ($_SESSION['type'] == 'master') {
                                    echo '<th>Created by </th>';
                                } ?>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="orderModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <form method="post" id="order_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Create Order</h4>
                    <button type="button" id="close" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Enter Reciver Name</label>
                                <input type="text" name="inventory_order_name" id="inventory_order_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="text" name="inventory_order_date" id="inventory_order_date" class="form-control" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Enter Reciver Address</label>
                        <textarea name="inventory_order_address" id="inventory_order_address" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Enter Product Details</label>
                        <hr>
                        <span id="span_product_details"></span>
                        <hr>
                    </div>
                    <div class="form-group">
                        <label>Select Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-control">
                            <option value="cash">Cash</option>
                            <option value="credit">Credit</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="inventory_order_id" id="inventory_order_id">
                    <input type="hidden" name="btn_action" id="btn_action">
                    <input type="submit" name="action" id="action" class="btn btn-info" value="Add">
                </div>
            </div>
        </form>
    </div>
</div>

<div id="orderdetailsModal" class="modal fade">
    <div class="modal-dialog modal-lg">
        <form method="post" id="product_form">
            <div class="modal-content">
                <div class="modal-header text-white bg-info">
                    <h4 class="modal-title"><i class="fa fa-plus"></i></h4>
                    <button type="button" class="close text-white" id="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="order_details">
                        <!-- Dynamic content with ajax -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        var orderdataTable = $('#order_data').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "order_fetch.php",
                type: "POST"
            },
            <?php if ($_SESSION["type"] == 'master') { ?> "columnDefs": [{
                    "targets": [4, 5, 6, 7, 8, 9],
                    "orderable": false,
                }, ],
            <?php } else { ?> "columnDefs": [{
                    "targets:": [4, 5, 6, 7, 8],
                    "orderable": false,
                }, ],
            <?php }
            ?> "pageLength": 10
        });

        $('#add_button').click(function() {
            $('#orderModal').modal('show');
            $('#order_form')[0].reset();
            $('.modal-title').html(`<i class="fa fa-plus"></i> Create Order`);
            $('.modal-header').attr('style', 'background-color:mediumseagreen;color:white;')
            $('#close').attr('style', 'color:white');
            $('#action').val('Add');
            $('#btn_action').val('Add');
            $('#span_product_details').html('');
            add_product_row();
        });

        // Add product Function 
        function add_product_row(count = 0) {
            var html = '';
            html += `<span id="row${count}"><div class="row">`;
            html += `<div class="col-md-8">`;
            html += `<select name="product_id[]" id="product_id${count}" class="form-control selectpicker" data-live-search="true" required>`;
            html += `<?= fill_product_list($connect); ?>`;
            html += `</select><input type="hidden" name="hidden_product_id[]" id="hidden_product_id${count}">`;
            html += `</div>`;
            html += `<div class="col-md-2">`;
            html += `<input type="text" name="quantity[]" class="form-control" required>`;
            html += `</div>`;
            html += `<div class="col-md-1">`;
            if (count == '') {
                html += `<button type="button" name="add_more" id="add_more" class="btn btn-success btn-sm">+</button>`;
            } else {
                html += `<button type="button" name="remove" id="${count}" class="btn btn-danger btn-sm remove">X</button>`;
            }
            html += `</div>`;
            html += `</div></div><br></span>`;
            $('#span_product_details').append(html);

            $('.selectpicker').selectpicker();
        }

        var count = 0;
        $(document).on('click', '#add_more', function() {
            count = count + 1;
            add_product_row(count);
        });
        $(document).on('click', '.remove', function() {
            var row_no = $(this).attr("id");
            $(`#row${row_no}`).remove();
        })
        // ------- End Add Product Function

        // Submit Action
        $(document).on('submit', '#order_form', function(event) {
            event.preventDefault();
            $('#action').attr('disabled', 'disabled');
            const form_data = $(this).serialize();
            $.ajax({
                url: "order_action.php",
                method: "POST",
                data: form_data,
                success: function(data) {
                    $('#order_form')[0].reset();
                    $('#orderModal').modal('hide');
                    $('#action').attr('disabled', false);
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        html: `<span class="font-weight-bold">${data}<span>`
                    })
                    orderdataTable.ajax.reload();
                }   
            });
        });

        // View Details
        $(document).on('click', '.view', function() {
            const order_id = $(this).attr("id");
            const btn_action = 'order_details';
            $.ajax({
                url: "order_action.php",
                method: "POST",
                data: {
                    order_id: order_id,
                    btn_action: btn_action
                },
                success: function(data) {
                    $('#orderdetailsModal').modal('show');
                    $('#order_details').html(data);
                    $('.modal-title').html('Order Details');
                    // $('#close').attr('style', 'color:white;');
                }
            })
        })

        // Fetch Single
        $(document).on('click', '.update', function() {
            const inventory_order_id = $(this).attr("id");
            const btn_action = 'fetch_single';
            $.ajax({
                url: "order_action.php",
                method: "POST",
                data: {
                    inventory_order_id: inventory_order_id,
                    btn_action: btn_action
                },
                dataType: "json",
                success: function(data) {
                    $('#orderModal').modal('show');
                    $('.modal-header').attr('style', 'background-color:#fffc69;');
                    $('#inventory_order_name').val(data.inventory_order_name);
                    $('#inventory_order_date').val(data.inventory_order_date);
                    $('#inventory_order_address').val(data.inventory_order_address);
                    $('#span_product_details').html(data.product_details);
                    $('#payment_status').val(data.payment_status);
                    $('.modal-title').html("Edit Order");
                    $('#inventory_order_id').val(inventory_order_id);
                    $('#action').val('Update');
                    $('#btn_action').val('Edit');
                    $('#close').attr('style', 'color:black');
                }
            })
        });

        $(document).on('click', '.delete', function() {
            const status = $(this).data('status');
            const btn_action = 'delete';
            const inventory_order_id = $(this).attr('id');
            const name = $(this).data('name');

            Swal.fire({
                title: 'Are you sure?',
                text: `to change status order on ${name}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        $.ajax({
                            url: "order_action.php",
                            method: "POST",
                            data: {
                                status: status,
                                btn_action: btn_action,
                                inventory_order_id: inventory_order_id,
                                name: name
                            },
                            success: function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: data
                                });
                                orderdataTable.ajax.reload();
                            }
                        })

                    )
                }
            })
        })

    })
</script>


<?php
include('footer.php');
?>