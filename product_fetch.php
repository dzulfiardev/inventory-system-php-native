<?php
// product_fetch.php
include('database_connection.php');
include('function.php');

$query = '';

$output = [];
$query .= "SELECT * FROM product 
        INNER JOIN brand ON brand.brand_id = product.brand_id
        INNER JOIN category ON category.category_id = product.category_id
        INNER JOIN user_detail ON user_detail.user_id = product.product_enter_by
        ";

if (isset($_POST["search"]["value"])) {
    $query .= 'WHERE brand.brand_name LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR category.category_name LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR product.product_name LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR product.product_quantity LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR user_detail.user_name LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR product.product_id LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR product.product_status LIKE "%' . $_POST["search"]["value"] . '%" ';
}

if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $_POST['order'][0]['column'] . ' ' . $_POST['order'][0]['dir'] . ' ';
} else {
    $query .= 'ORDER BY product_id DESC ';
}

if ($_POST['length'] != -1) {
    $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);
$statement->execute();

$result = $statement->fetchAll();
$data = [];
$filtered_rows = $statement->rowCount();

foreach ($result as $row) {
    $status = '';
    if ($row['product_status'] == 'active') {
        $status = '<span class="badge badge-success">Active</span>';
    } else {
        $status = '<span class="badge badge-danger">Inactive</span>';
    }
    $sub_array = [];
    $sub_array[] = $row['product_id'];
    $sub_array[] = $row['category_name'];
    $sub_array[] = $row['brand_name'];
    $sub_array[] = $row['product_name'];
    $sub_array[] = avaible_product_quantity($connect, $row["product_id"]) . ' ' . $row["product_unit"];
    $sub_array[] = $row['user_name'];
    $sub_array[] = $status;
    $sub_array[] = '<button type="button" name="view" id="' . $row["product_id"] . '" class="btn btn-info btn-sm view">View</button>';
    $sub_array[] = '<button type="button" name="update" id="' . $row["product_id"] . '" class="btn btn-warning btn-sm update">Update</button>';
    $sub_array[] = '<button type="button" name="delete" id="' . $row["product_id"] . '" class="btn badge badge-secondary delete" data-status="' . $row["product_status"] . '" data-name="' . $row["product_name"] . '">Change Status</button>';
    $data[] = $sub_array;
}

function get_totall_all_records($connect)
{
    $statement = $connect->prepare('SELECT * FROM product');
    $statement->execute();
    return $statement->rowCount();
}

$output = [
    "draw" => intval($_POST["draw"]),
    "recordsTotal" => $filtered_rows,
    "recordsFiltered" => get_totall_all_records($connect),
    "data" => $data
];

echo json_encode($output);
