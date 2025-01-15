<?php

    session_start();
    define ("APPURL", "http://localhost/homeland");
    define ("THUMBNAILURL", "http://localhost/homeland/thumbnails");
    define ("GALLERYURL", "http://localhost/homeland/images");

    


    require dirname(dirname(__FILE__)) . "/config/config.php";

    //echo dirname(dirname(__FILE__));

    $categories = $conn->query("SELECT * FROM categories");
    $categories->execute();

    $allCategories = $categories->fetchAll(PDO::FETCH_OBJ);


?><!DOCTYPE html>
<html lang="en">
  <head>
    <title>AuctionXpert &mdash;Bid with Confidence</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:200,300,400,700,900|Roboto+Mono:300,400,500"> 
    <link rel="stylesheet" href="<?php echo APPURL;?>fonts/icomoon/style.css">
    <link rel="stylesheet" href="<?php echo APPURL;?>/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo APPURL;?>/css/magnific-popup.css">
    <link rel="stylesheet" href="<?php echo APPURL;?>/css/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo APPURL;?>/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo APPURL;?>/css/owl.theme.default.min.css">
    <link rel="stylesheet" href="<?php echo APPURL;?>/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="<?php echo APPURL;?>/css/mediaelementplayer.css">
    <link rel="stylesheet" href="<?php echo APPURL;?>/css/animate.css">
    <link rel="stylesheet" href="<?php echo APPURL;?>/fonts/flaticon/font/flaticon.css">
    <link rel="stylesheet" href="<?php echo APPURL;?>/css/fl-bigmug-line.css">
    <link rel="stylesheet" href="path/to/font-awesome/css/all.min.css">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-kJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
  
    <link rel="stylesheet" href="<?php echo APPURL;?>/css/aos.css">

    <link rel="stylesheet" href="<?php echo APPURL;?>/css/style.css">

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    
  </head>
  <body>

  <div class="site-loader"></div>
  
  <div class="site-wrap">

    <div class="site-mobile-menu">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div> <!-- .site-mobile-menu -->

    <div class="site-navbar mt-4">
        <div class="container py-1">
          <div class="row align-items-center">
            <div class="col-8 col-md-8 col-lg-4">
              <h1 class="mb-0"><a href="<?php echo APPURL; ?>" class="text-white h2 mb-0"><strong>AuctionXpert<span class="text-danger">.</span></strong></a></h1>
            </div>
            <div class="col-4 col-md-4 col-lg-8">
              <nav class="site-navigation text-right text-md-right" role="navigation">

                <div class="d-inline-block d-lg-none ml-md-0 mr-auto py-3"><a href="#" class="site-menu-toggle js-menu-toggle text-white"><span class="icon-menu h3"></span></a></div>

                <ul class="site-menu js-clone-nav d-none d-lg-block">
                  <li class="active">
                    <a href="<?php echo APPURL ; ?> ">Home</a>
                  </li>
                  <li><a href="<?php echo APPURL; ?>/sale.php?type=sale">Buy</a></li>
                  <li><a href="<?php echo APPURL; ?>/rent.php?type=rent">Rent</a></li>
                  <li class="has-children">
                    <a href="#">Properties</a>
                    <ul class="dropdown arrow-top">
                      <?php foreach($allCategories as $category) :?>
                      <li><a href="<?php echo APPURL;?>/categories/category.php?name=<?php echo $category->name;?>"><?php echo str_replace('-', ' ', $category->name);?></a></li>
                      <?php endforeach; ?>
                     
                    </ul>
                  </li>

                  <!-- <li class="has-children">
                    <a href="#">Listing</a>
                    <ul class="dropdown arrow-top">
                    <li><a href="<?php echo APPURL; ?>/upload.php">Upload</a></li>
                    <li><a href="<?php echo APPURL; ?>/mybid.php">My Bid</a></li>
                    </ul>
                  </li> -->
                  <li><a href="<?php echo APPURL; ?>/upload.php">Upload</a></li>

                  <!-- <li><a href="<?php echo APPURL; ?>/about.php">About</a></li> -->
                  <li><a href="<?php echo APPURL; ?>/contact.php">Contact</a></li>

                  <?php if(isset($_SESSION['username'])) : ?>

                  <li class="has-children">
                    <a href="#"><?php echo $_SESSION['username'] ?></a>
                    <ul class="dropdown arrow-top">
                    <li><a href="<?php echo APPURL; ?>/user/notification.php">Notification</a></li>
                      <li><a href="<?php echo APPURL; ?>/user/deposit.php">Deposit</a></li>
                      <li><a href="<?php echo APPURL; ?>/user/favourites.php">Favourites</a></li>
                      <li><a href="<?php echo APPURL; ?>/user/mybid.php">My Bid</a></li>
                      <li><a href="<?php echo APPURL; ?>/user/mylisting.php">My Listing</a></li>
                      <li><a href="<?php echo APPURL; ?>/auth/logout.php">Logout</a></li>
                     
                     
                    </ul>
                  </li>
                
                  <?php else : ?>

                  <li><a href="<?php echo APPURL; ?>/auth/login.php">Login</a></li>
                  <li><a href="<?php echo APPURL; ?>/auth/register.php">Register</a></li>

                  <?php endif; ?>
                </ul>
              </nav>
            </div>
           

          </div>
        </div>
      </div>
    </div>