<?php
//user_action.php

include('database_connection.php');

if (isset($_POST['btn_action'])) {

    // Add Method
    if ($_POST['btn_action'] == 'Add') {
        $query = " INSERT INTO user_detail (user_email, user_password, user_name, user_type, user_status) VALUES (:user_email, :user_password, :user_name, :user_type, :user_status)
        ";

        $datainsert = [
            ':user_email' => $_POST["user_email"],
            ':user_password' => password_hash($_POST["user_password"], PASSWORD_DEFAULT),
            ':user_name' => $_POST["user_name"],
            ':user_type' => 'user',
            ':user_status' => 'active'
        ];

        $statement = $connect->prepare($query);
        $statement->execute($datainsert);

        $result = $statement->fetchAll();
        if (isset($result)) {
            echo 'New User Added';
        }
    }

    // Fetch single method / tampil data form edit 
    if ($_POST['btn_action'] == 'fetch_single') {
        $query = " SELECT * FROM user_detail WHERE user_id = :user_id
        ";

        $statement = $connect->prepare($query);
        $statement->execute([
            ':user_id' => $_POST['user_id']
        ]);

        $result = $statement->fetchAll();
        foreach ($result as $row) {
            $output['user_email'] = $row['user_email'];
            $output['user_name'] = $row['user_name'];
        }

        echo json_encode($output);
    }

    // Edit Method
    if ($_POST['btn_action'] == 'Edit') {

        if ($_POST['user_password'] != '') {
            $query = " UPDATE user_detail SET 
                    user_name = '" . $_POST["user_name"] . "', 
                    user_email = '" . $_POST["user_email"] . "',
                    user_password = '" . password_hash($_POST["user_password"], PASSWORD_DEFAULT) . "'
                    WHERE user_id = '" . $_POST["user_id"] . "' 
            ";
        } else {
            $query = " UPDATE user_detail SET 
                    user_name = '" . $_POST["user_name"] . "', 
                    user_email = '" . $_POST["user_email"] . "'
                    WHERE user_id = '" . $_POST["user_id"] . "' 
                    ";
        }

        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();

        if (isset($result)) {
            echo 'User Details Edited';
        }
    }

    // Active or inactive Method
    if ($_POST['btn_action'] == 'delete') {
        $status = 'active';
        if ($_POST['status'] == 'active') {
            $status = 'inactive';
        }
        $query = " UPDATE user_detail SET user_status = :user_status WHERE user_id = :user_id
        ";

        $statement = $connect->prepare($query);
        $statement->execute([
            ':user_status' => $status,
            ':user_id' => $_POST["user_id"]
        ]);

        $result = $statement->fetchAll();

        if (isset($result)) {
            echo 'User Status Change to <b>' . $status . '</b>';
        }
    }
}
