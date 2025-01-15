<?php require "../layouts/header.php"; ?>
<?php require "../../config/config.php"; ?>

<form method="POST" action="test_upload.php" enctype="multipart/form-data">
   <input type="file" name="test_file">
   <input type="submit" name="submit" value="Upload">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   var_dump($_FILES['test_file']);

   // Get the current directory's absolute path
   $currentDir = __DIR__;

   // Construct the destination path
   $destinationPath = $currentDir . '/uploads/' . $_FILES['test_file']['name'];

   // Additional check: Ensure the 'uploads' directory exists
   if (!file_exists($currentDir . '/uploads')) {
       mkdir($currentDir . '/uploads', 0777, true);
   }

   // Move the uploaded file to the correct destination
   if (move_uploaded_file($_FILES['test_file']['tmp_name'], $destinationPath)) {
       echo 'File moved successfully.';
   } else {
       echo 'Failed to move file.';
   }
}
?>


<?php require "../layouts/footer.php"; ?>
