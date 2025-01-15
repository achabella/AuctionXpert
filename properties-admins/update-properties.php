<?php
require "../layouts/header.php";
require "../../config/config.php";

if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='".ADMINURL."/admins/login-admins.php'</script>";
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $prop = $conn->query("SELECT * FROM props WHERE id='$id'");
    $prop->execute();
    $allProp = $prop->fetch(PDO::FETCH_OBJ);
}

if (isset($_POST['submit'])) {
    if (empty($_POST['starting_price']) || empty($_POST['end_datetime']) || empty($_POST['start_datetime'])) {
        echo "<script>alert('Some inputs are empty');</script>";
    } else {
        $starting_price = $_POST['starting_price'];
        $end_datetime = date('Y-m-d H:i:s', strtotime($_POST['end_datetime']));
        $start_datetime = date('Y-m-d H:i:s', strtotime($_POST['start_datetime']));

        $update = $conn->prepare("UPDATE props SET starting_price = :starting_price, end_datetime = :end_datetime, start_datetime = :start_datetime WHERE id = :id");

        $update->bindParam(':starting_price', $starting_price, PDO::PARAM_STR);
        $update->bindParam(':end_datetime', $end_datetime, PDO::PARAM_STR);
        $update->bindParam(':start_datetime', $start_datetime, PDO::PARAM_STR);
        $update->bindParam(':id', $id, PDO::PARAM_INT);

        $update->execute();

        echo "<script>window.location.href='".ADMINURL."/properties-admins/show-properties.php'</script>";
    }
}
?>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
            <h5 class="card-title mb-4 mt-5 d-inline" style="margin-bottom: 10px;">Update Property Details</h5>

                <form method="POST" action="update-properties.php?id=<?php echo $allProp->id; ?>">
                    <!-- Other form fields -->

                    <div class="form-group row mb-4" style="margin-top: 10px;">
                    <div class="col-md-4">
                            <label for="" class="control-label">Bidding Start Date & Time</label>
                            <input type="datetime-local" class="form-control" name="start_datetime" value="<?php echo isset($allProp->start_datetime) ? date('Y-m-d\TH:i', strtotime($allProp->start_datetime)) : ''; ?>">
                        </div>

                        <div class="col-md-4">
                            <label for="" class="control-label">Bidding End Date & Time</label>
                            <input type="datetime-local" class="form-control" name="end_datetime" value="<?php echo isset($allProp->end_datetime) ? date('Y-m-d\TH:i', strtotime($allProp->end_datetime)) : ''; ?>">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="" class="control-label">Starting Price from Owner (RM)</label>
                            <input type="text" class="form-control text-left" name="price" value="<?php echo $allProp->price; ?>" readonly>
                        </div>
						<div class="col-md-4">
							<label for="" class="control-label">Starting Bidding Amount (RM)</label>
							<input type="number" class="form-control text-left" name="starting_price" value="<?php echo $allProp->starting_price; ?>">
						</div>
					</div>
                    </div>
                    <div class="col-md-4 text-left">
                        <button type="submit" name="submit" class="btn btn-primary mb-4">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
