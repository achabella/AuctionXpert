
<?php require "includes/header.php"; ?>
<?php require "config/config.php"; ?>
<?php 

    if(!isset($_SESSION['username'])){  

        echo "<script>window.location.href='".APPURL."/auth/login.php'</script>";
  
    }

    error_reporting(E_ALL);
    ini_set('display_errors', '1');


    $categories = $conn->query("SELECT * FROM categories");
    $categories->execute();
    $allCategories = $categories->fetchAll(PDO::FETCH_OBJ);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);


    $id = '';

if (isset($_POST['submit'])) {

    if (empty($_POST['name']) || empty($_POST['location']) || empty($_POST['price']) ||
        empty($_POST['beds']) || empty($_POST['baths']) || empty($_POST['sqft']) ||
        empty($_POST['home_type']) || empty($_POST['year_built']) || empty($_POST['type']) ||
        empty($_POST['description']) || empty($_POST['price_sqft'])) {

        echo "<script>alert('Some inputs are empty');</script>";

    } 
    
    else {

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
        $username = $_SESSION['username'];
        $thumbnailImage = $_FILES['thumbnail']['name'];

        $thumbnailDir = "thumbnails/" . basename($thumbnailImage);

        $insert = $conn->prepare("INSERT INTO props(name, location, price, beds, baths,
        sqft, home_type, year_built, type, description, price_sqft, admin_name, image) 
        VALUES (:name, :location, :price, :beds, :baths, :sqft, :home_type, 
        :year_built, :type, :description, :price_sqft, :username, :image)");

        try {
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
            ':username' => $username,
            ':image' => $thumbnailImage,
            ]);

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        // After successful props insertion
        $id = $conn->lastInsertId();

        $user_id = $_SESSION['user_id'];  // Assuming user_id is stored in the session

$insert_owner = $conn->prepare("INSERT INTO owners (user_id, prop_id) VALUES (:user_id, :prop_id)");

try {
    $insert_owner->execute([
        ':user_id' => $user_id,
        ':prop_id' => $id,
    ]);
} catch (PDOException $e) {
    echo "Error inserting into owners: " . $e->getMessage();
}



        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnailDir)) {
            // $id = $conn->lastInsertId();

            foreach ($_FILES['image']['tmp_name'] as $key => $value) {
                $filename = $_FILES['image']['name'][$key];
                $filename_tmp = $_FILES['image']['tmp_name'][$key];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);

                $finalimg = '';

                $filename = str_replace('.', '-', basename($filename, $ext));
                $newfilename = $filename . time() . "." . $ext;
                move_uploaded_file($filename_tmp, 'images/' . $newfilename);
                $finalimg = $newfilename;

                $insertqry = $conn->prepare("INSERT INTO `related_images`( `image`, prop_id) VALUES ('$finalimg','$id')");
                $insertqry->execute();
            }

            $errorInfo = $insert->errorInfo();
            if ($errorInfo[0] !== '00000') {
                echo "Error: " . $errorInfo[2];
            }
            
            // echo "<script>window.location.href='" . APPURL . "/upload.php' </script>";
        }
    }
}
?>

    <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url(images/hero_bg_1.jpg);" data-aos="fade" data-stellar-background-ratio="0.5">
      <div class="container">
        <div class="row align-items-center justify-content-center text-center">
          <div class="col-md-10">
            <h1 class="mb-2">Upload</h1>
          </div>
        </div>
      </div>
    </div>

    <div class="site-wrap">
    <div class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-md-12" data-aos="fade-up" data-aos-delay="100">
            <h3 class="h4 text-black widget-title mb-3">Upload Property for Rent</h3>
            <form method="POST" action="upload.php" enctype="multipart/form-data">
            <div class="form-outline mb-4 mt-4">                
              <label for="name">Name</label>
                <input type="text" name="name" id="form2Example1" class="form-control" />
            </div>
            <div class="form-outline mb-4 mt-4">                
              <label for="location">Location</label>
              <input type="text" name="location" id="form2Example1" class="form-control"  />
            </div>
            <div class="form-outline mb-4 mt-4">                
              <label for="price">Price</label>
              <input type="text" name="price" id="form2Example1" class="form-control" />
            </div>
            <div class="form-outline mb-4 mt-4">                
              <label for="beds">Beds</label>
              <input type="text" name="beds" id="form2Example1" class="form-control" />
            </div>
            <div class="form-outline mb-4 mt-4">                
              <label for="baths">Baths</label>
              <input type="text" name="baths" id="form2Example1" class="form-control" />
            </div>
            <div class="form-outline mb-4 mt-4">                
              <label for="sqft">SQFT</label>
              <input type="text" name="sqft" id="form2Example1" class="form-control"  />
            </div>
            <div class="form-outline mb-4 mt-4">                
              <label for="year_built">Year Built</label>
              <input type="text" name="year_built" id="form2Example1" class="form-control"  />
            </div>
            <div class="form-outline mb-4 mt-4">                
              <label for="price_sqft">Price Per SQFT</label>
              <input type="text" name="price_sqft" id="form2Example1" class="form-control"  />
            </div>
            <label for="home_type" class="form-label">Select Home Type</label>

            <select name="home_type" class="form-control mb-4 form-select" aria-label="Default select example">
              <option selected></option>
              <?php foreach( $allCategories as $category) : ?>
                <option value="<?php echo $category->name; ?>"><?php echo $category->name; ?></option>
              <?php endforeach; ?>
                                
            </select>   
            <label for="type" class="form-label">Select Type</label>
              <select name="type" class="form-control mb-4 form-select" aria-label="Default select example">
                  <option selected></option>
                  <option value="rent">Rent</option>
                  <!-- <option value="sale">Sale</option> -->
              </select>
              <!-- Add a note indicating that users should contact admin for sale -->
              <p>Note: If you want to upload a property for sale, please <a href="contact.php">contact the admin</a> for further instructions.</p>
            <div class="form-group">
                <label for="exampleFormControlTextarea1">Description</label>
                <textarea placeholder="" name="description" class="form-control" id="exampleFormControlTextarea1" rows="5" style="height: 150px;"></textarea>
            </div>
            <div class="mb-3">
              <label for="formFile" class="form-label">Property Thumbnail</label>
              <input name="thumbnail" class="form-control" type="file" id="formFile">
            </div>
            <div class="mb-3">
              <label for="formFileMultiple" class="form-label">Gallery Images</label>
              <input name="image[]" class="form-control" type="file" id="formFileMultiple" multiple>
            </div>
            <div class="form-group">
                <input type="submit" name="submit" id="phone" class="btn btn-primary" value="Upload">
            </div>
            </form>
          </div>   
        </div>
      </div>
    </div>
</div>

<?php require "includes/footer.php"; ?>
