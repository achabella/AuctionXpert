<?php require "../layouts/header.php"; ?>
<?php require "../../config/config.php"; ?>
<?php

  if(!isset($_SESSION['adminname'])){

    echo "<script>window.location.href='".ADMINURL."/admins/login-admins.php'</script>";

  }

  if(isset($_GET['id'])){

    $id = $_GET['id'];

    $category = $conn->query("DELETE FROM users WHERE id='$id'"); 

    $category->execute();

    echo "<script>window.location.href='".ADMINURL."/users-admins/show-users.php'</script>";

  }

?>