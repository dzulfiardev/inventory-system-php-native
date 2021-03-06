<?php
// index.php
include('database_connection.php');
include('function.php');

if (!isset($_SESSION["type"])) {
    header("location:login.php");
}

$title = 'Home Page';

include('header.php');
?>

<h2 align="center" class="mb-4">Inventory Management System</h2>

<br>
<?php if ($_SESSION['type'] == 'master') : ?>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <strong>Total User</strong>
                </div>
                <div class="card-body" align="center">
                    <h1><?= count_total_user($connect) ?></h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <strong>Total Category</strong>
                </div>
                <div class="card-body" align="center">
                    <h1><?= count_total_category($connect) ?></h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <strong>Total Brand</strong>
                </div>
                <div class="card-body" align="center">
                    <h1><?= count_total_brand($connect) ?></h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <strong>Total Item in Stock</strong>
                </div>
                <div class="card-body" align="center">
                    <h1><?= count_total_product($connect) ?></h1>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<hr>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <strong>Total Order Value</strong>
            </div>
            <div class="card-body" align="center">
                <h1>$ <?= count_total_order_value($connect) ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <strong>Total Cash Order Value</strong>
            </div>
            <div class="card-body" align="center">
                <h1>$ <?= count_total_cash_order_value($connect) ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <strong>Total Cash Order Credit</strong>
            </div>
            <div class="card-body" align="center">
                <h1>$ <?= count_total_credit_order_value($connect) ?></h1>
            </div>
        </div>
    </div>
</div>
<hr>
<?php if ($_SESSION['type'] == 'master') : ?>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong>Value User Wise</strong>
                <div class="card-body" align="center">
                    <?= get_user_wise_total_order($connect); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
</div>

<?php
include('footer.php');
?>