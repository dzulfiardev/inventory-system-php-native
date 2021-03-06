<?php
// category_fetch.php
include('database_connection.php');

$query = '';

$output = [];

$query .= " SELECT * FROM category ";

if (isset($_POST["search"]["value"])) {
    $query .= 'WHERE category_name LIKE "%' . $_POST['search']['value'] . '%"';
    $query .= 'OR category_status LIKE "%' . $_POST["search"]["value"] . '%"';
}

if (isset($_POST['order'])) {
    $query .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= 'ORDER BY category_id DESC ';
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
    if ($row['category_status'] == 'active') {
        $status = '<span class="badge badge-success">Active</span>';
    } else {
        $status = '<span class="badge badge-danger">Inactive</span>';
    }
    $sub_array = [];
    $sub_array[] = $row['category_id'];
    $sub_array[] = $row['category_name'];
    $sub_array[] = $status;
    $sub_array[] = '<button type="button" name="update" id="' . $row["category_id"] . '" class="btn btn-warning btn-sm update">Update</button>';
    $sub_array[] = '<button type="button" name="delete" id="' . $row["category_id"] . '" class="btn badge badge-secondary delete" data-status="' . $row["category_status"] . '" data-name = "' . $row['category_name'] . '">Change Status</button>';
    $data[] = $sub_array;
}

$output = [
    "draw" => intVal($_POST["draw"]),
    "recordsTotal" => $filtered_rows,
    "recordsFiltered" => get_total_all_records($connect),
    "data" => $data
];

function get_total_all_records($connect)
{
    $statement = $connect->prepare("SELECT * FROM category");
    $statement->execute();
    return $statement->rowCount();
}

echo json_encode($output);
