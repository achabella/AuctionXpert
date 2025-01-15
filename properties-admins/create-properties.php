<?php
require "../layouts/header.php";
require "../../config/config.php";

if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='" . ADMINURL . "/admins/login-admins.php' </script>";
}

$categories = $conn->query("SELECT * FROM categories");
$categories->execute();
$allCategories = $categories->fetchAll(PDO::FETCH_OBJ);

$id = '';

if (isset($_POST['submit'])) {
    if (empty($_POST['name']) || empty($_POST['location']) || empty($_POST['price']) ||
        empty($_POST['beds']) || empty($_POST['baths']) || empty($_POST['sqft']) ||
        empty($_POST['home_type']) || empty($_POST['year_built']) || empty($_POST['type']) ||
        empty($_POST['description']) || empty($_POST['price_sqft'])) {

        echo "<script>alert('Some inputs are empty');</script>";
    } else {
        $name = $_POST['name'];
        $location = $_POST['location'];
        $price = $_POST['price'];
        $beds = $_POST['beds'];
        $baths = $_POST['baths'];
        $sqft = $_POST['sqft'];
        $home_type = $_POST['home_type'];
        $year_built = $_POST['year_built'];
        $type = $_POST['type'];
        $description = $_POST['description'];
        $price_sqft = $_POST['price_sqft'];
        $adminname = $_SESSION['adminname'];
        $thumbnailImage = $_FILES['thumbnail']['name'];

        // $thumbnailDir = "thumbnails/" . basename($thumbnailImage);
        $thumbnailDir = "../../thumbnails/" . basename($thumbnailImage);


        $insert = $conn->prepare("INSERT INTO props(name, location, price, beds, baths,
        sqft, home_type, year_built, type, description, price_sqft, admin_name, image) 
        VALUES (:name, :location, :price, :beds, :baths, :sqft, :home_type, 
        :year_built, :type, :description, :price_sqft, :adminname, :image)");

        $insert->execute([
            ':name' => $name,
            ':location' => $location,
            ':price' => $price,
            ':beds' => $beds,
            ':baths' => $baths,
            ':sqft' => $sqft,
            ':home_type' => $home_type,
            ':year_built' => $year_built,
            ':type' => $type,
            ':description' => $description,
            ':price_sqft' => $price_sqft,
            ':adminname' => $adminname,
            ':image' => $thumbnailImage,
        ]);

        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnailDir)) {
            $id = $conn->lastInsertId();

            foreach ($_FILES['image']['tmp_name'] as $key => $value) {
                $filename = $_FILES['image']['name'][$key];
                $filename_tmp = $_FILES['image']['tmp_name'][$key];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);

                $finalimg = '';

                $filename = str_replace('.', '-', basename($filename, $ext));
                $newfilename = $filename . time() . "." . $ext;
                // move_uploaded_file($filename_tmp, 'images/' . $newfilename);
                move_uploaded_file($filename_tmp, '../../images/' . $newfilename);
                $finalimg = $newfilename;

                $insertqry = $conn->prepare("INSERT INTO `related_images`( `image`, prop_id) VALUES ('$finalimg','$id')");
                $insertqry->execute();
            }

            echo "<script>window.location.href='" . ADMINURL . "/properties-admins/show-properties.php' </script>";
        }
    }
}
?>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-5 d-inline">Create Properties</h5>
                <form method="POST" action="create-properties.php" enctype="multipart/form-data">
                    <!-- Email input -->
                    <div class="form-outline mb-4 mt-4">
                            <input type="text" name="name" id="form2Example1" class="form-control" placeholder="Name" />
                        </div>    
                        <div class="form-outline mb-4 mt-4">
                            <input type="text" name="location" id="form2Example1" class="form-control" placeholder="Location" />
                        </div> 
                        <div class="form-outline mb-4 mt-4">
                            <input type="text" name="price" id="form2Example1" class="form-control" placeholder="Price (RM)" />
                        </div> 
                        <div class="form-outline mb-4 mt-4">
                            <input type="text" name="beds" id="form2Example1" class="form-control" placeholder="Beds" />
                        </div>
                        <div class="form-outline mb-4 mt-4">
                            <input type="text" name="baths" id="form2Example1" class="form-control" placeholder="Baths" />
                        </div>
                        <div class="form-outline mb-4 mt-4">
                            <input type="text" name="sqft" id="form2Example1" class="form-control" placeholder="SQ/FT" />
                        </div>   
                        <div class="form-outline mb-4 mt-4">
                            <input type="text" name="year_built" id="form2Example1" class="form-control" placeholder="Year Build" />
                        </div> 
                        <div class="form-outline mb-4 mt-4">
                            <input type="text" name="price_sqft" id="form2Example1" class="form-control" placeholder="Price Per SQ FT" />
                        </div> 
                        
                        <select name="home_type" class="form-control form-select" aria-label="Default select example">
                            <option selected>Select Home Type</option>
                            <?php foreach( $allCategories as $category) : ?>
                            <option value="<?php echo $category->name; ?>"><?php echo $category->name; ?></option>
                            <?php endforeach; ?>
                           
                        </select>   
                        <select name="type" class="form-control mt-3 mb-4 form-select" aria-label="Default select example">
                            <option selected>Select Type</option>
                            <option value="rent">Rent</option>
                            <option value="sent">Sale</option>
                        </select>  
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Description</label>
                            <textarea placeholder="Description" name="description" class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Property Thumbnail</label>
                        <input name="thumbnail" class="form-control" type="file" id="formFile">
                    </div>
                    <div class="mb-3">
                        <label for="formFileMultiple" class="form-label">Gallery Images</label>
                        <input name="image[]" class="form-control" type="file" id="formFileMultiple" multiple>
                    </div>
                    <!-- Submit button -->
                    <button type="submit" name="submit" class="btn btn-primary mb-4 text-center">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require "../layouts/footer.php"; ?>
