<?php
// category_action.php

include('database_connection.php');

if (isset($_POST['btn_action'])) {

    // Add Method
    if ($_POST['btn_action'] == 'Add') {
        $query = " INSERT INTO category (category_name) VALUES (:category_name)
        ";

        $datainsert = [
            ':category_name' => $_POST["category_name"]
        ];

        $statement = $connect->prepare($query);
        $statement->execute($datainsert);

        $result = $statement->fetchAll();
        if (isset($result)) {
            echo '<b>' . $_POST['category_name'] . '</b> category name added';
        }
    }

    // Fetch Single Method
    if ($_POST['btn_action'] == 'fetch_single') {

        $query = " SELECT * FROM category WHERE category_id = :category_id
        ";
        $statement = $connect->prepare($query);
        $statement->execute([
            ':category_id' => $_POST['category_id']
        ]);

        $result = $statement->fetchAll();
        foreach ($result as $row) {
            $output['category_name'] = $row['category_name'];
        }

        echo json_encode($output);
    }

    // Edit Method 
    if ($_POST['btn_action'] == 'Edit') {

        $query = " UPDATE category SET 
            category_name = '" . $_POST["category_name"] . "'
            WHERE category_id = '" . $_POST["category_id"] . "'
        ";

        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();

        if (isset($result)) {
            echo $_POST['old_category_name'] . ' updated change to <span class="font-weight-bold">' . $_POST['category_name'] . '</span>';
        }
    }

    // Delete / Inactive Method
    if ($_POST['btn_action'] == 'delete') {
        $status = 'Active';
        if ($_POST['status'] == 'active') {
            $status = 'Inactive';
        }

        $query = " UPDATE category SET category_status = :category_status WHERE category_id = :category_id
        ";

        $statement = $connect->prepare($query);
        $statement->execute([
            ':category_status' => $status,
            ':category_id' => $_POST["category_id"]
        ]);

        $result = $statement->fetchAll();

        if (isset($result)) {
            echo '<span class="font-weight-bold">' . $_POST['category_name'] . '</span> category status is   <b>' . $status . '</b>';
        }
    }
}
