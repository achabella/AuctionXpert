<?php require "layouts/header.php"; ?>  
<?php require "../config/config.php"; ?>
<?php 

if(!isset($_SESSION['adminname'])){
    echo "<script>window.location.href='".ADMINURL."/admins/login-admins.php'</script>";
}

$props = $conn->query("SELECT COUNT(*) AS num_props FROM props");
$props->execute();
$allProps = $props->fetch(PDO::FETCH_OBJ);

$categories = $conn->query("SELECT COUNT(*) AS num_categories FROM categories");
$categories->execute();
$allCategories = $categories->fetch(PDO::FETCH_OBJ);

$admins = $conn->query("SELECT COUNT(*) AS num_admins FROM admins");
$admins->execute();
$allAdmins = $admins->fetch(PDO::FETCH_OBJ);

$users = $conn->query("SELECT COUNT(*) AS num_users FROM users");
$users->execute();
$allUsers = $users->fetch(PDO::FETCH_OBJ);

$bids = $conn->query("SELECT COUNT(*) AS num_bids FROM bids");
$bids->execute();
$allBids = $bids->fetch(PDO::FETCH_OBJ);

?>

<div class="row">
<div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Admins</h5>
                <p class="card-text">Number of admins: <?php echo $allAdmins->num_admins; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Users</h5>
                <p class="card-text">Number of users: <?php echo $allUsers->num_users; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Properties</h5>
                <p class="card-text">Number of properties: <?php echo $allProps->num_props; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Categories</h5>
                <p class="card-text">Number of categories: <?php echo $allCategories->num_categories; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Bids</h5>
                <p class="card-text">Number of bids: <?php echo $allBids->num_bids; ?></p>
            </div>
        </div>
    </div>
</div>

<?php require "layouts/footer.php"; ?>
