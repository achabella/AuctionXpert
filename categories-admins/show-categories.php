<?php require "../layouts/header.php"; ?>
<?php require "../../config/config.php"; ?>

<?php
if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='" . ADMINURL . "/admins/login-admins.php'</script>";
}

$categories = $conn->query("SELECT * FROM categories");
$categories->execute();
$allCategories = $categories->fetchAll(PDO::FETCH_OBJ);
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4 d-inline">Categories</h5>
                <a href="create-category.php" class="btn btn-primary mb-4 text-center float-right">Create Categories</a>
                <table class="table mt-4 table-condensed table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">#</th>
                            <th scope="col">Name</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 1;
                        foreach ($allCategories as $category) :
                        ?>
                            <tr>
                                <th scope="row" class="text-center unbold"><?php echo $counter++; ?></th>
                                <td><?php echo $category->name; ?></td>
                                <td class="text-center">
                                    <a href="update-category.php?id=<?php echo $category->id; ?>" class="btn btn-warning text-white text-center">Update</a>
                                    <button class="btn btn-danger text-center" onclick="confirmDelete(<?php echo $category->id; ?>)">Delete</button>
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
    function confirmDelete(categoryId) {
        var confirmDelete = confirm("Are you sure you want to delete this category?");
        if (confirmDelete) {
            window.location.href = "delete-category.php?id=" + categoryId;
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
