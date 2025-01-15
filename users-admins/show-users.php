<?php require "../layouts/header.php"; ?>
<?php require "../../config/config.php"; ?>

<?php
if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='" . ADMINURL . "/admins/login-admins.php' </script>";
}

$users = $conn->query("SELECT * FROM users");
$users->execute();
$allUsers = $users->fetchAll(PDO::FETCH_OBJ);
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4 d-inline">Users</h5>
                <table class="table mt-4 table-condensed table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">#</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone No.</th>
                            <th scope="col">Address</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 1;
                        foreach ($allUsers as $user) : ?>
                            <tr>
                                <th scope="row" class="text-center unbold"><?php echo $counter++; ?></th>
                                <td><?php echo $user->username; ?></td>
                                <td><?php echo $user->email; ?></td>
                                <td><?php echo $user->phone_no; ?></td>
                                <td><?php echo $user->address; ?></td>
                                <td class="text-center">
                                    <?php
                                    if ($user->wrong_attempts > 2) {
                                        echo '<span class="badge badge-danger">Blocked</span>';
                                    } else {
                                        echo '<span class="badge badge-success">Active</span>';
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($user->wrong_attempts > 2) : ?>
                                        <a href="unblock-users.php?id=<?php echo $user->id; ?>" class="btn btn-warning text-white" title="Unblock User">Unblock</a>
                                    <?php else : ?>
                                        <a href="block-users.php?id=<?php echo $user->id; ?>" class="btn btn-warning text-white" title="Block User"> Block </a>
                                    <?php endif; ?>
                                    <button class="btn btn-danger" onclick="confirmDelete(<?php echo $user->id; ?>)" title="Delete User">Delete </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(userId) {
        var confirmDelete = confirm("Are you sure you want to delete this user?");
        if (confirmDelete) {
            window.location.href = "delete-users.php?id=" + userId;
        }
    }
</script>

<style>
    .unbold {
        font-weight: normal !important;
    }
</style>

<?php require "../layouts/footer.php"; ?>
