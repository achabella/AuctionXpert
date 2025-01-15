<?php require "includes/header.php"; ?>
<?php require "config/config.php"; ?>
<?php

$select = $conn->query("SELECT * FROM props ORDER BY name DESC");
$select->execute();

$props = $select->fetchAll(PDO::FETCH_OBJ);

if(isset($_GET['type'])){
    $type = $_GET['type'];
    $rent = $conn->query("SELECT * FROM props WHERE type='$type'");
    $rent->execute();
    $allListings = $rent->fetchAll(PDO::FETCH_OBJ);
}

if(isset($_GET['price'])){
    $price = $_GET['price'];
    $price_query = $conn->query("SELECT * FROM props WHERE end_datetime > NOW() ORDER BY starting_price $price");
    $price_query->execute();

    $allListingsPrice = $price_query->fetchAll(PDO::FETCH_OBJ);
}

?>

    <div class="slide-one-item home-slider owl-carousel">
    <?php foreach($props as $prop):?>
      <div class="site-blocks-cover overlay" style="background-image: url(<?php echo THUMBNAILURL; ?>/<?php echo $prop->image; ?>);" data-aos="fade" data-stellar-background-ratio="0.5">
        <div class="container">
          <div class="row align-items-center justify-content-center text-center">
          <div class="col-md-10">
              <span class="d-inline-block bg-
              <?php 
                if($prop->type == "rent") { 
                  echo "bg-success text-white"; 
                } else { 
                  echo "bg-danger text-white"; 
                }
              ?>
              
               text-white px-3 mb-3 property-offer-type rounded"><?php echo $prop->type; ?></span>
              <h1 class="mb-2"><?php echo $prop->name; ?></h1>
              <p class="mb-5"><strong class="h2 text-success font-weight-bold">RM<?php echo $prop->price; ?></strong></p>
              <p><a href="property-details.php?id=<?php echo $prop->id; ?>" class="btn btn-white btn-outline-white py-3 px-5 rounded-0 btn-2">See Details</a></p>
            </div>
          </div>
        </div>
      </div>  
      <?php endforeach; ?>

    </div>


    <div class="site-section site-section-sm pb-0">
      <div class="container">
        <div class="row">
          <form class="form-search col-md-12" method="POST" action="search.php" style="margin-top: -100px;">
            <div class="row  align-items-end">
              <div class="col-md-3">
                <label for="list-types">Listing Types</label>
                <div class="select-wrap">
                  <span class="icon icon-arrow_drop_down"></span>
                  <select name="types" id="list-types" class="form-control d-block rounded-0">
                    <?php foreach($allCategories as $category):?>
                      <option value="<?php echo $category->name; ?>"><?php echo str_replace('-', ' ', $category->name);?></option>
                    <?php endforeach; ?>

                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <label for="offer-types">Offer Type</label>
                <div class="select-wrap">
                  <span class="icon icon-arrow_drop_down"></span>
                  <select name="offers" id="offer-types" class="form-control d-block rounded-0">
                    <option value="sale">Sale</option>
                    <option value="rent">Rent</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <label for="select-city">Select City</label>
                <div class="select-wrap">
                  <span class="icon icon-arrow_drop_down"></span>
                  <select name="cities" id="select-city" class="form-control d-block rounded-0">
                    <option value="" disabled selected>Select a city</option>

                    <optgroup label="Selangor">
                      <option value="Petaling Jaya">Petaling Jaya</option>
                      <option value="Shah Alam">Shah Alam</option>
                      <!-- Add more cities in Selangor as needed -->
                    </optgroup>

                    <optgroup label="Pahang">
                      <option value="Kuantan">Kuantan</option>
                      <option value="Temerloh">Temerloh</option>
                      <!-- Add more cities in Pahang as needed -->
                    </optgroup>

                    <optgroup label="Kuala Lumpur">
                      <option value="Kuala Lumpur City Center">Kuala Lumpur City Center</option>
                      <option value="Bukit Bintang">Bukit Bintang</option>
                      <!-- Add more cities in Kuala Lumpur as needed -->
                    </optgroup>


                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <input type="submit" name="submit" class="btn btn-success text-white btn-block rounded-0" value="Search">
              </div>
            </div>
          </form>
        </div>  

        <div class="row">
          <div class="col-md-12">
            <div class="view-options bg-white py-3 px-3 d-md-flex align-items-center">
              <div class="mr-auto">
                <a href="index.php" class="icon-view view-module active"><span class="icon-view_module"></span></a>
              </div>
              <div class="ml-auto d-flex align-items-center">
                <div>
                  <a href="<?php echo APPURL; ?>" class="view-list px-3 border-right active">All</a>
                  <a href="rent.php?type=rent" class="view-list px-3 border-right">Rent</a>
                  <a href="sale.php?type=sale" class="view-list px-3">Sale</a>
                  <a href="price.php?price=ASC" class="view-list px-3">Price Ascending</a>
                  <a href="price.php?price=DESC" class="view-list px-3">Price Descending</a>
                </div>
              </div>
            </div>
          </div>
        </div>
       
      </div>
    </div>

    <div class="site-section site-section-sm bg-light">
      <div class="container">
        <div class="row mb-5">
        <?php foreach($allListingsPrice as $allListing):?>
          <div class="col-md-6 col-lg-4 mb-4">
            <div class="property-entry h-100">
              <a href="property-details.php?id=<?php echo $allListing->id; ?>" class="property-thumbnail">
                <div class="offer-type-wrap">
                  <span class="offer-type bg-
                  <?php 
                    if($allListing->type == "rent") { 
                      echo "bg-success text-white"; 
                    } else { 
                      echo "bg-danger text-white"; 
                    }
                  ?>"><?php echo $allListing->type; ?></span>
                </div>
                <img src="<?php echo THUMBNAILURL; ?>/<?php echo $allListing->image; ?>" alt="Image" class="img-fluid">
              </a>
              <div class="p-4 property-body">
                <!-- <a href="#" class="property-favorite"><span class="icon-heart-o"></span></a> -->
                <h2 class="property-title"><a href="property-details.php?id=<?php echo $allListing->id; ?>"><?php echo $allListing->name; ?></a></h2>
                <span class="property-location d-block mb-3"><span class="property-icon icon-room"></span> <?php echo $allListing->location; ?></span>
                <strong class="property-price text-primary mb-3 d-block text-success">RM<?php echo $allListing->price; ?></strong>
                <ul class="property-specs-wrap mb-3 mb-lg-0">
                  <li>
                    <span class="property-specs">Beds</span>
                    <span class="property-specs-number"><?php echo $allListing->beds; ?></span>
                    
                  </li>
                  <li>
                    <span class="property-specs">Baths</span>
                    <span class="property-specs-number"><?php echo $allListing->baths; ?></span>
                    
                  </li>
                  <li>
                    <span class="property-specs">SQ FT</span>
                    <span class="property-specs-number"><?php echo $allListing->sqft; ?></span>
                    
                  </li>
                </ul>

              </div>
            </div>
          </div>
          <?php endforeach; ?>

        </div>
     
        
      </div>
    </div>
    <?php require "includes/footer.php"; ?>