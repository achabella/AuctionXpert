<?php require "../layouts/header.php"; ?>
<?php require "../../config/config.php"; ?>

<?php
if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='" . ADMINURL . "/admins/login-admins.php' </script>";
}

$props = $conn->query("SELECT * FROM props");
$props->execute();
$allProps = $props->fetchAll(PDO::FETCH_OBJ);
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4 d-inline">Properties</h5>
                <a href="<?php echo ADMINURL; ?>/properties-admins/create-properties.php" class="btn btn-primary mb-4 text-center float-right">Create Properties</a>
                <table class="table mt-4 table-condensed table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price (RM)</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">End Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 1;
                        foreach ($allProps as $prop) :
                        ?>
                            <tr>
                                <th scope="row" class="text-center unbold"><?php echo $counter++; ?></th>
                                <td><?php echo $prop->name; ?></td>
                                <td><?php echo $prop->price; ?></td>
                                <td><?php echo $prop->start_datetime; ?></td>
                                <td><?php echo $prop->end_datetime; ?></td>
                                <td class="text-center">
                                    <a href="update-properties.php?id=<?php echo $prop->id; ?>" class="btn btn-warning text-white text-center">Update</a>
                                    <button class="btn btn-danger text-center" onclick="confirmDelete(<?php echo $prop->id; ?>)">Delete</button>
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
    function confirmDelete(propId) {
        var confirmDelete = confirm("Are you sure you want to delete this property?");
        if (confirmDelete) {
            window.location.href = "delete-properties.php?id=" + propId;
        }
    }
</script>

<style>
    /* Add this style to make the first column text unbold */
    .unbold {
        font-weight: normal !important;
    }
</style>

<?php require "../layouts/footer.php"; ?>
