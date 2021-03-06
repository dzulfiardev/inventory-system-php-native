<?php
// user_fetch.php
include('database_connection.php');

$query = '';
$output = [];

$query .= " SELECT * FROM user_detail WHERE user_type = 'user' AND  
        ";

if (isset($_POST["search"]["value"])) {
    $query .= 'user_id LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR user_email LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR user_name LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR user_status LIKE "%' . $_POST["search"]["value"] . '%" ';
}

if (isset($_POST["order"])) {
    $query .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= 'ORDER BY user_id DESC ';
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
    $status = '';
    if ($row['user_status'] == 'active') {
        $status = '<span class="badge badge-success">Active</span>';
    } else {
        $status = '<span class="badge badge-danger">Inactive</span>';
    }

    $sub_array = array();
    $sub_array[] = $row['user_id'];
    $sub_array[] = $row['user_email'];
    $sub_array[] = $row['user_name'];
    $sub_array[] = '<b class="text-capitalize">' . $row['user_type'] . '</b>';
    $sub_array[] = $status;
    $sub_array[] = '<button type="button" name="update" id="' . $row["user_id"] . '" class="btn btn-warning btn-sm update">Update</button>';
    $sub_array[] = '<button type="button" name="delete" id="' . $row["user_id"] . '" class="btn badge badge-secondary delete" data-status="' . $row["user_status"] . '" data-name="' . $row["user_name"] . '">Change Status</button>';
    $data[] = $sub_array;
}

$output = [
    "draw" => intval($_POST["draw"]),
    "recordsTotal" => $filtered_rows,
    "recordsFiltered" => get_total_all_records($connect),
    "data" => $data
];

echo json_encode($output);

function get_total_all_records($connect)
{
    $statement = $connect->prepare(" SELECT * FROM user_detail WHERE user_type = 'user' ");
    $statement->execute();
    return $statement->rowCount();
}
