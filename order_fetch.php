<?php
//order_fetch.php
include('database_connection.php');
include('function.php');

$query = '';
$output = [];

$query .= "
            SELECT * FROM inventory_order WHERE 
        ";

if ($_SESSION['type'] == 'user') {
    $query .= 'user_id = "' . $_SESSION["user_id"] . '" AND ';
}

if (isset($_POST["search"]["value"])) {
    $query .= '(inventory_order_id LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR inventory_order_name LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR inventory_order_total LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR inventory_order_status LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR inventory_order_date LIKE "%' . $_POST["search"]["value"] . '%") ';
}

if (isset($_POST["order"])) {
    $query .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= 'ORDER BY inventory_order_id DESC ';
}

if ($_POST["length"] != -1) {
    $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();

$data = [];
$filtered_rows = $statement->rowCount();

foreach ($result as $row) {
    $payment_status = '';

    if ($row['payment_status'] == 'cash') {
        $payment_status = '<span class="badge badge-primary">Cash</span>';
    } else {
        $payment_status = '<span class="badge badge-warning">Credit</span>';
    }

    $status = '';
    if ($row['inventory_order_status'] == 'active') {
        $status = '<span class="badge badge-success">Active</span>';
    } else {
        $status = '<span class="badge badge-danger">Inactive</span>';
    }

    $sub_array = [];
    $sub_array[] = $row['inventory_order_id'];
    $sub_array[] = $row['inventory_order_name'];
    $sub_array[] = '$ ' . $row['inventory_order_total'];
    $sub_array[] = $payment_status;
    $sub_array[] = $status;
    $sub_array[] = $row['inventory_order_date'];
    if ($_SESSION['type'] == 'master') {
        $sub_array[] = get_user_name($connect, $row['user_id']);
    }
    if ($_SESSION['type'] == 'master') {
        $sub_array[] = '<button class="btn btn-info btn-sm view" name="view" id="' . $row["inventory_order_id"] . '">View</button>';
    }
    $sub_array[] = '<button type="button" name="update" id="' . $row["inventory_order_id"] . '" class="btn btn-warning btn-sm update">Update</button>';
    $sub_array[] = '<button type="button" name="delete" id="' . $row["inventory_order_id"] . '" class="btn badge badge-secondary delete" data-status="' . $row["inventory_order_status"] . '" data-name="' . $row['inventory_order_name'] . '">Change Status</button>';
    $data[] = $sub_array;
}

function get_total_all_records($connect)
{
    $statement = $connect->prepare("SELECT * FROM inventory_order");
    $statement->execute();
    return $statement->rowCount();
}

$output = [
    "draw" => intval($_POST["draw"]),
    "recordsTotal" => $filtered_rows,
    "recordsFiltered" => get_total_all_records($connect),
    "data" => $data
];

echo json_encode($output);
