<?php require "../layouts/header.php"; ?>  
<?php require "../../config/config.php"; ?> 

<form method="POST" action="test_upload.php" enctype="multipart/form-data">
   <input type="file" name="test_file">
   <input type="submit" name="submit" value="Upload">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   var_dump($_FILES['test_file']);
   move_uploaded_file($_FILES['test_file']['tmp_name'], 'uploads/' . $_FILES['test_file']['name']);
}
?>

<?php require "../layouts/footer.php"; ?>  
