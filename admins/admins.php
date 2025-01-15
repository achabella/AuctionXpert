<?php require "../layouts/header.php"; ?>
<?php require "../../config/config.php"; ?>
<?php

if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='" . ADMINURL . "/admins/login-admins.php'</script>";
}

$admins = $conn->query("SELECT * FROM admins");

$admins->execute();

$allAdmins = $admins->fetchAll(PDO::FETCH_OBJ);
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4 d-inline">Admins</h5>
                <a href="<?php echo ADMINURL; ?>/admins/create-admins.php" class="btn btn-primary mb-4 text-center float-right">Create Admins</a>
                <table class="table mt-4 table-condensed table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">#</th>
                            <th scope="col">Admin Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone Number</th>
                            <th scope="col">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allAdmins as $admin) : ?>
                            <tr>
                                <th scope="row" class="text-center unbold"><?php echo $admin->id; ?></th>
                                <td><?php echo $admin->adminname; ?></td>
                                <td><?php echo $admin->email; ?></td>
                                <td><?php echo $admin->phone_no; ?></td>
                                <td><?php echo $admin->created_at; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
    /* Add this style to make the first column text unbold */
    .unbold {
        font-weight: normal !important;
    }
</style>
<?php require "../layouts/footer.php"; ?>
