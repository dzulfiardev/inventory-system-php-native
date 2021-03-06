<?php
include('database_connection.php');

$title = 'Login | Inventory System Management';

if (isset($_SESSION['type'])) {
    header('location:index.php');
}

$message = '';

if (isset($_POST["login"])) {

    $query = "SELECT * FROM user_detail WHERE user_email = :user_email";

    $statement = $connect->prepare($query);
    $statement->execute(
        ['user_email' => $_POST["user_email"]]
    );

    $count = $statement->rowCount();
    if ($count > 0) {
        $result = $statement->fetchAll();
        foreach ($result as $row) {
            if (password_verify($_POST['user_password'], $row['user_password'])) {
                if ($row['user_status'] == 'active') {
                    $_SESSION['type'] = $row['user_type'];
                    $_SESSION['user_id'] = $row['user_id'];
                    $_SESSION['user_name'] = $row['user_name'];
                    header('location:index.php');
                } else {
                    $message = '<label class="text-danger"><small>Your account has disabled, contact Master</small></label>';
                }
            } else {
                $message = '<label class="text-danger"><small>Wrong password</small></label>';
            }
        }
    } else {
        $message = '<label class="text-danger"><small>Wrong email address</small></label>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>


    <title><?= $title ?></title>
</head>

<body>
    <br>
    <div class="container" style="min-height:100vh">
        <h2 align="center">Inventory Management System</h2>
        <br>
        <div class="d-flex justify-content-center">
            <div class="col-md-6">
                <div class="panel panel-default card">
                    <div class="panel-heading card-header">Login</div>
                    <div class="panel-body card-body">
                        <form action="" method="post">
                            <?= $message; ?>
                            <div class="form-group">
                                <label>User Email</label>
                                <input type="text" name="user_email" class="form-control" required autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="user_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="login" value="Login" class="btn btn-info" required>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-dark">
        <div class="container">
            <div class="row bd-highlight">
                <div class="col text-white d-flex align-items-center justify-content-center mt-3">
                    <p><i class="far fa-copyright"></i> Copyright
                        <?= date('Y'); ?> <a href="#" class="text-white">Dzulfikar Sauki</a>
                    </p>
                </div>
            </div>
        </div>
    </footer>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <!-- Bootstrap Javascript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
</body>

</html>