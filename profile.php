<?php
// profile.php
include('database_connection.php');

if (!isset($_SESSION['type'])) {
    header('location:index.php');
}

$query = "SELECT * FROM user_detail WHERE user_id = '" . $_SESSION["user_id"] . "' ";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$name = '';
$email = '';
$user_id = '';
foreach ($result as $row) {
    $name = $row['user_name'];
    $email = $row['user_email'];
}

$title = 'Edit Profile';

include('header.php');

?>
<br>
<div class="container">
    <div class="card my-3">
        <div2 class="card-header">
            <h2>Edit Profile</h2>
        </div2>
        <div class="card-body">
            <form method="POST" id="edit_profile_form">
                <span id="message"></span>
                <div class="form-group col-md-6">
                    <label>Name</label>
                    <input type="text" name="user_name" id="user_name" class="form-control" value="<?= $name ?>">
                </div>
                <div class="form-group col-md-6">
                    <label>Email</label>
                    <input type="text" name="user_email" id="user_email" class="form-control" value="<?= $email ?>">
                </div>
                <hr>
                <div class="form-group col-md-6">
                    <label class="text-muted">Leave Password blank if you do not want to change</label>
                </div>
                <div class="form-group col-md-6">
                    <label>New Password</label>
                    <input type="password" name="user_new_password" id="user_new_password" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label>Repeat Password</label>
                    <input type="password" name="user_re_enter_password" id="user_re_enter_password" class="form-control">
                    <span id="error_password"></span>
                </div>
                <div class="form-group col-md-6">
                    <input type="submit" name="edit_profile" id="edit_submit" value="Edit" class="btn btn-info">
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#edit_profile_form').on('submit', function(event) {
            event.preventDefault()
            if ($('#user_new_password').val() != '') {
                if ($('#user_new_password').val() != $('#user_re_enter_password').val()) {
                    $('#error_password').html(`<label class="text-danger"><small>Password Not Match</small></label>`)
                    $('#user_re_enter_password').addClass('is-invalid')
                    return false;
                } else {
                    $('#error_password').html('')
                }
            }

            $('#edit_submit').attr('disabled', 'disabled')
            $.ajax({
                url: "edit_profile.php",
                method: "POST",
                data: $(this).serialize(),
                success: function(data) {
                    $('#edit_submit').attr('disabled', false)
                    $('#user_new_password').val('')
                    $('#user_re_enter_password').val('')
                    $('#user_re_enter_password').removeClass('is-invalid')
                    $('#user_re_enter_password').removeClass('is-valid')
                    $('#message').html(data)
                }
            })
        })

        // Password Validation 
        $('#user_re_enter_password').on('keyup', function() {
            if ($('#user_new_password').val() == $('#user_re_enter_password').val()) {
                $('#user_re_enter_password').addClass('is-valid')
                $('#user_re_enter_password').removeClass('is-invalid')
                $('#error_password').html(`<label class="text-success"><small>Password Match</small></label>`)
            } else if ($('#user_re_enter_password').val() == '') {
                $('#user_re_enter_password').removeClass('is-invalid')
                $('#user_re_enter_password').removeClass('is-valid')
                $('#error_password').html('')
            } else {
                $('#user_re_enter_password').addClass('is-invalid')
                $('#error_password').html(`<label class="text-danger"><small>Password Not Match</small></label>`)
            }
        })
    })
</script>

<?php
include('footer.php');
?>