<?php
// brand_action.php 
include('database_connection.php');

if (isset($_POST['btn_action'])) {

    if ($_POST['btn_action'] == 'Add') {

        $query = "INSERT INTO brand (category_id, brand_name) VALUES (:category_id, :brand_name)";
        $statement = $connect->prepare($query);
        $statement->execute([
            ':category_id' => $_POST["category_id"],
            ':brand_name' => $_POST["brand_name"]
        ]);
        $result = $statement->fetchAll();

        if (isset($result)) {
            echo '<span class="font-weight-bold">' . $_POST['brand_name'] . '</span> is new brand added';
        }
    }

    // Method fetch_single
    if ($_POST['btn_action'] == 'fetch_single') {

        $query = "SELECT * FROM brand WHERE brand_id = :brand_id";
        $statement = $connect->prepare($query);
        $statement->execute([
            ':brand_id' => $_POST["brand_id"]
        ]);

        $result = $statement->fetchAll();
        foreach ($result as $row) {
            $output['category_id'] = $row['category_id'];
            $output['brand_name'] = $row['brand_name'];
        }

        echo json_encode($output);
    }

    // Method Update
    if ($_POST['btn_action'] == 'Edit') {

        $query = " UPDATE brand SET 
                category_id = :category_id,
                brand_name = :brand_name 
                WHERE brand_id = :brand_id
        ";
        $statement = $connect->prepare($query);
        $statement->execute([
            ':category_id' => $_POST['category_id'],
            ':brand_name' => $_POST['brand_name'],
            ':brand_id' => $_POST['brand_id']
        ]);

        $result = $statement->fetchAll();

        if (isset($result)) {
            echo $_POST['old_brand_name'] . ' updated change to <span class="font-weight-bold">' . $_POST['brand_name'] . '</span>';
        }
    }

    // Change status method
    if ($_POST['btn_action'] == 'delete') {
        $status = 'Active';
        if ($_POST['status'] == 'active') {
            $status = 'Inactive';
        }

        $query = "UPDATE brand SET brand_status = :brand_status WHERE brand_id = :brand_id";

        $statement = $connect->prepare($query);
        $statement->execute([
            ':brand_status' => $status,
            ':brand_id' => $_POST['brand_id']
        ]);

        $result = $statement->fetchAll();

        if (isset($result)) {
            echo '<span class="font-weight-bold text-capitalize">' . $_POST['brand_name'] . '</span> change status to <b>' . $status . '</b>';
        }
    }
}
