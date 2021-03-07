<?php
// product.php

include('database_connection.php');

include('function.php');

if (!isset($_SESSION['type'])) {
    header('location:login.php');
}

if ($_SESSION['type'] != 'master') {
    header('location:index.php');
}

$title = 'Product';
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
                    <div class="col-sm-10">
                        <h3 class="card-title">Product List</h3>
                    </div>
                    <div class="col-md-2" align="right">
                        <button type="button" id="add_button" class="btn btn-success">Add</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table id="product_data" class="table table-bordered table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>ID</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Enter By</th>
                                    <th>Status</th>
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
</div>

<div id="productModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="product_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Add Product</h4>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select Category</label>
                        <select name="category_id" id="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            <?= fill_category_list($connect); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Select Brand</label>
                        <select name="brand_id" id="brand_id" class="form-control" required>
                            <option value="">Select Brand</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="product_name" id="product_name" class="form-control" required>
                        <input type="hidden" name="old_product_name" id="old_product_name">
                    </div>
                    <div class="form-group">
                        <label>Enter Product Description</label>
                        <textarea type="text" name="product_description" id="product_description" class="form-control" required rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Enter Product Quantity</label>
                        <div class="input-group">
                            <input type="text" name="product_quantity" id="product_quantity" class="form-control" required pattern="[+-]?([0-9]*[.])?[0-9]+">
                            <span class="input-group-prepend">
                                <select name="product_unit" id="product_unit" class="custom-select" required>
                                    <option value="">Select Unit</option>
                                    <option value="Bags">Bags</option>
                                    <option value="Bottles">Bottles</option>
                                    <option value="Box">Box</option>
                                    <option value="Dozens">Dozens</option>
                                    <option value="Feet">Feet</option>
                                    <option value="Gallon">Gallon</option>
                                    <option value="Grams">Grams</option>
                                    <option value="Inch">Inch</option>
                                    <option value="Kg">Kg</option>
                                    <option value="Liters">Liters</option>
                                    <option value="Meter">Meter</option>
                                    <option value="Nos">Nos</option>
                                    <option value="Packet">Packet</option>
                                    <option value="Rolls">Rolls</option>
                                    <option value="Pcs">Pcs</option>
                                </select>
                            </span>
                        </div>
                        <div class="form-group">
                            <label>Enter Product Base Price</label>
                            <input type="text" name="product_base_price" id="product_base_price" class="form-control" required pattern="[+-]?([0-9]*[.])?[0-9]+">
                        </div>
                        <div class="form-group">
                            <label>Enter Product Tax (%)</label>
                            <input type="text" name="product_tax" id="product_tax" class="form-control" required pattern="[+-]?([0-9]*[.])?[0-9]+">
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="product_id" id="product_id">
                            <input type="hidden" name="btn_action" id="btn_action">
                            <input type="submit" name="action" id="action" class="btn btn-info" value="Add">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="productdetailsModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="product_form">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Product Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="product_details">
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
        var productdataTable = $('#product_data').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "product_fetch.php",
                type: "POST"
            },
            "columnDefs": [{
                "targets": [7, 8, 9],
                "orderable": false
            }, ],
            "pageLength": 10
        });

        $('#add_button').click(function() {
            $('#productModal').modal('show');
            $('#product_form')[0].reset();
            $('.modal-title').html(`<i class="fa fa-plus"></i> Add Product`);
            $('#action').val('Add');
            $('#btn_action').val('Add');
        });

        $('#category_id').change(function() {
            const category_id = $('#category_id').val();
            const btn_action = 'load_brand';
            $.ajax({
                url: "product_action.php",
                method: "POST",
                data: {
                    category_id: category_id,
                    btn_action: btn_action
                },
                success: function(data) {
                    $('#brand_id').html(data)
                }
            });
        });

        $(document).on('submit', '#product_form', function(event) {
            event.preventDefault()
            $('#action').attr('disabled', 'disabled');
            const form_data = $(this).serialize();
            $.ajax({
                url: "product_action.php",
                method: "POST",
                data: form_data,
                success: function(data) {
                    $('#product_form')[0].reset();
                    $('#productModal').modal('hide');
                    $('#alert_action').fadeIn().html(`<div class="alert alert-success">${data}</div>`);
                    $('#action').attr('disabled', false);
                    productdataTable.ajax.reload();
                }
            })
        });

        $(document).on('click', '.view', function() {
            const product_id = $(this).attr("id");
            const btn_action = 'product_details';
            $.ajax({
                url: "product_action.php",
                method: "POST",
                data: {
                    product_id: product_id,
                    btn_action: btn_action
                },
                success: function(data) {
                    $('#productdetailsModal').modal('show');
                    $('#product_details').html(data);
                    $('.modal-title').html('Detail Product');
                }
            })
        });

        // Fetch Single
        $(document).on('click', '.update', function() {
            const product_id = $(this).attr("id");
            const btn_action = 'fetch_single';
            $.ajax({
                url: "product_action.php",
                method: "POST",
                data: {
                    product_id: product_id,
                    btn_action: btn_action
                },
                dataType: "json",
                success: function(data) {
                    $('#productModal').modal('show');
                    $('#category_id').val(data.category_id);
                    $('#brand_id').html(data.brand_select_box);
                    $('#brand_id').val(data.brand_id);
                    $('#product_name').val(data.product_name);
                    $('#product_description').val(data.product_description);
                    $('#product_quantity').val(data.product_quantity);
                    $('#product_unit').val(data.product_unit);
                    $('#product_base_price').val(data.product_base_price);
                    $('#product_tax').val(data.product_tax);
                    $('.modal-title').html('Edit Product');
                    $('#product_id').val(product_id);
                    $('#action').val('Edit');
                    $('#btn_action').val('Edit');
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            })
        });

        // Change Status 
        $(document).on('click', '.delete', function() {
            const status = $(this).data('status');
            const btn_action = 'delete';
            const product_id = $(this).attr('id');
            const product_name = $(this).data('name');

            Swal.fire({
                title: 'Are you sure?',
                text: `to change status product ${product_name}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        $.ajax({
                            url: "product_action.php",
                            method: "POST",
                            data: {
                                status: status,
                                btn_action: btn_action,
                                product_id: product_id,
                                product_name: product_name
                            },
                            success: function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: data
                                })
                                productdataTable.ajax.reload();
                            }
                        })
                    )
                }
            })

        });

    })
</script>

<?php
include('footer.php');
?>