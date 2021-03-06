<?php
// order_action.php
include('database_connection.php');
include('function.php');

if (isset($_POST['btn_action'])) {

    if ($_POST['btn_action'] == 'Add') {

        $query = "INSERT INTO inventory_order (user_id, inventory_order_total, inventory_order_date, inventory_order_name, inventory_order_address, payment_status, inventory_order_status, inventory_order_created_date)
        VALUES
        (:user_id, :inventory_order_total, :inventory_order_date, :inventory_order_name, :inventory_order_address, :payment_status, :inventory_order_status, :inventory_order_created_date)
        ";

        $statement = $connect->prepare($query);
        $statement->execute([
            ':user_id' => $_SESSION["user_id"],
            ':inventory_order_total' => 0,
            ':inventory_order_date' => $_POST["inventory_order_date"],
            ':inventory_order_name' => $_POST["inventory_order_name"],
            ':inventory_order_address' => $_POST["inventory_order_address"],
            ':payment_status' => $_POST["payment_status"],
            ':inventory_order_status' => 'active',
            ':inventory_order_created_date' => date("Y-m-d")
        ]);

        $result = $statement->fetchAll();
        $statement = $connect->query("SELECT LAST_INSERT_ID()");
        $inventory_order_id = $statement->fetchColumn();

        if (isset($inventory_order_id)) {
            $total_amount = 0;
            for ($count = 0; $count < count($_POST["product_id"]); $count++) {

                $product_details = fetch_product_details($_POST["product_id"][$count], $connect);

                $sub_query = "INSERT INTO inventory_order_product (inventory_order_id, product_id, quantity, price, tax)
                VALUES
                (:inventory_order_id, :product_id, :quantity, :price, :tax)
                ";

                $statement = $connect->prepare($sub_query);
                $statement->execute([
                    ':inventory_order_id' => $inventory_order_id,
                    ':product_id' => $_POST["product_id"][$count],
                    ':quantity' => $_POST["quantity"][$count],
                    ':price' => $product_details['price'],
                    ':tax' => $product_details['tax']
                ]);

                $base_price = $product_details['price'] * $_POST["quantity"][$count];
                $tax = ($base_price / 100) * $product_details['tax'];
                $total_amount = $total_amount + ($base_price + $tax);
            }

            $update_query = "UPDATE inventory_order
                            SET inventory_order_total = '" . $total_amount . "'
                            WHERE inventory_order_id = '" . $inventory_order_id . "'
            ";

            $statement = $connect->prepare($update_query);
            $statement->execute();
            $result = $statement->fetchAll();
            if (isset($result)) {
                echo 'Order Created,';
                echo ' total $ ' . $total_amount;
                echo ' with Order ID ' . $inventory_order_id;
            }
        }
    }

    if ($_POST['btn_action'] == 'order_details') {

        $query = "SELECT * FROM inventory_order 
                INNER JOIN inventory_order_product ON inventory_order_product.inventory_order_id = inventory_order.inventory_order_id
                INNER JOIN product ON product.product_id = inventory_order_product.product_id
                WHERE inventory_order.inventory_order_id = '" . $_POST['order_id'] . "'
                ";

        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();

        $output = '<div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <td><b>Product Id</b></td>
                            <td><b>Product Name</b></td>
                            <td><b>Price</b></td>
                            <td><b>Tax</b></td>
                            <td><b>Quantity</b></td>
                            <td><b>Total</b></td>
                        </tr>
                ';

        foreach ($result as $row) {

            $baseprice = $row['price'] * $row['quantity'];
            $tax = ($baseprice / 100) * $row['tax'];
            $totalamount = $baseprice + $tax;
            $output .=  '
                        <tr>
                            <td>' . $row['product_id'] . '</td>
                            <td>' . $row['product_name'] . '</td>
                            <td>$ ' . $row['price'] . '</td>
                            <td>' . $row['tax'] . ' %</td>
                            <td>' . $row['quantity'] . '</td>
                            <td>$ ' . $totalamount . '</td>
                        </tr>
            ';
        }
        $output .= '    
                            <tr>
                                <td colspan="5" align="right"><b>Order Total</b></td>  
                                <td><b>$ ' . get_total_inventory_order_product($connect, $_POST['order_id']) . '</b></td>
                            </tr>
                        </table>
                        <div align="right"><a href="print_order.php?order_id=' . $_POST['order_id'] . '" class="btn btn-sm btn-success" target="_blank"><i class="fas fa-print"></i> Print</a></div>
                    </div>
                    ';

        echo $output;
    }

    if ($_POST['btn_action'] == 'fetch_single') {

        $query = "SELECT * FROM inventory_order WHERE inventory_order_id = '" . $_POST['inventory_order_id'] . "' ";

        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $output = [];

        foreach ($result as $row) {
            $output['inventory_order_name'] = $row['inventory_order_name'];
            $output['inventory_order_date'] = $row['inventory_order_date'];
            $output['inventory_order_address'] = $row['inventory_order_address'];
            $output['payment_status'] = $row['payment_status'];
        }

        $sub_query = "SELECT * FROM inventory_order_product 
                    WHERE inventory_order_id = '" . $_POST["inventory_order_id"] . "'    
                    ";
        $statement = $connect->prepare($sub_query);
        $statement->execute();
        $sub_result = $statement->fetchAll();

        $product_details = '';
        $count = 0;

        foreach ($sub_result as $sub_row) {
            $product_details .= '
			<script>
			$(document).ready(function(){
				$("#product_id' . $count . '").selectpicker("val", ' . $sub_row["product_id"] . ');
				$(".selectpicker").selectpicker();
			});
            </script>

            <span id="row' . $count . '">
                <div class="row">
                    <div class="col-md-8">
                        <select name="product_id[]" id="product_id' . $count . '" class="form-control selectpicker" data-live-search="true" required>
                            ' . fill_product_list($connect) . '
                        </select>
                        <input type="hidden" name="hidden_product_id[]" id="hidden_product_id' . $count . '" value="' . $sub_row["product_id"] . '">
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="quantity[]" class="form-control" value="' . $sub_row["quantity"] . '" required>
                    </div>
                    <div class="col-md-1">
           ';

            if ($count == 0) {
                $product_details .= '<button type="button" name="add_more" id="add_more" class="btn btn-success btn-sm">+</button>';
            } else {
                $product_details .= '<button type="button" name="remove" id="remove" class="btn btn-danger btn-sm remove">X</button>';
            }

            $product_details .= '
                    </div>
                </div><br>
            </span>
            ';

            $count = $count + 1;
        }

        $output['product_details'] = $product_details;
        echo json_encode($output);
    }

    // Update Method
    if ($_POST['btn_action'] == 'Edit') {

        $delete_query = "DELETE FROM inventory_order_product WHERE inventory_order_id = '" . $_POST["inventory_order_id"] . "'  
        ";

        $statement = $connect->prepare($delete_query);
        $statement->execute();
        $delete_result = $statement->fetchAll();

        if (isset($delete_result)) {

            $total_amount = 0;
            for ($count = 0; $count < count($_POST["product_id"]); $count++) {

                $product_detail = fetch_product_details($_POST["product_id"][$count], $connect);

                $sub_query = "INSERT INTO inventory_order_product (inventory_order_id, product_id, quantity, price, tax) VALUES 
                    (:inventory_order_id, :product_id, :quantity, :price, :tax)
                    ";
                $statement = $connect->prepare($sub_query);
                $statement->execute([
                    ':inventory_order_id' => $_POST["inventory_order_id"],
                    ':product_id' =>         $_POST["product_id"][$count],
                    ':quantity' =>           $_POST["quantity"][$count],
                    ':price' =>              $product_detail['price'],
                    ':tax' =>                $product_detail['tax']
                ]);
                $base_price = $product_detail['price'] * $_POST["quantity"][$count];
                $tax = ($base_price / 100) * $product_detail['tax'];
                $total_amount = $total_amount + ($base_price + $tax);
            }
            $update_query = "UPDATE inventory_order
                        SET inventory_order_name = :inventory_order_name,
                        inventory_order_date = :inventory_order_date,
                        inventory_order_address = :inventory_order_address,
                        inventory_order_total = :inventory_order_total,
                        payment_status = :payment_status
                        WHERE inventory_order_id = :inventory_order_id
                         ";
            $statement = $connect->prepare($update_query);
            $statement->execute([
                ':inventory_order_name' => $_POST["inventory_order_name"],
                ':inventory_order_date' => $_POST["inventory_order_date"],
                ':inventory_order_address' => $_POST["inventory_order_address"],
                ':inventory_order_total' => $total_amount,
                ':payment_status' => $_POST["payment_status"],
                ':inventory_order_id' => $_POST["inventory_order_id"]
            ]);

            $result = $statement->fetchAll();
            if (isset($result)) {
                echo 'Order Edited !';
            }
        }
    }

    // Change status or delete action
    if ($_POST['btn_action'] == 'delete') {

        $status = 'active';
        if ($_POST['status'] == 'active') {
            $status = 'inactive';
        }

        $query = "UPDATE inventory_order SET
                  inventory_order_status = :inventory_order_status
                  WHERE inventory_order_id = :inventory_order_id  
                ";
        $statement = $connect->prepare($query);
        $statement->execute([
            ':inventory_order_status' => $status,
            ':inventory_order_id' => $_POST["inventory_order_id"]
        ]);

        $result = $statement->fetchAll();

        if (isset($result)) {
            echo $_POST['name'] . ' status change to ' . $status;
        }
    }
}
