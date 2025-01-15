<?php require "includes/header.php"; ?>
<?php require "config/config.php"; ?>
<?php

// Get the current date and time in the format of your database
$currentDateTime = date('Y-m-d H:i:s');

// Modify the query to exclude properties with end_datetime in the past
$select = $conn->prepare("SELECT * FROM props WHERE end_datetime > :currentDateTime ORDER BY name DESC");
$select->bindParam(':currentDateTime', $currentDateTime, PDO::PARAM_STR);
$select->execute();

$props = $select->fetchAll(PDO::FETCH_OBJ);

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
                    <?php foreach($allCategories as $category): ?>
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
                    </optgroup>

                    <optgroup label="Pahang">
                      <option value="Kuantan">Kuantan</option>
                      <option value="Temerloh">Temerloh</option>
                    </optgroup>

                    <optgroup label="Kuala Lumpur">
                      <option value="Kuala Lumpur City Center">Kuala Lumpur City Center</option>
                      <option value="Bukit Bintang">Bukit Bintang</option>
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
              <div class="ml-auto d-flex align-items-center">
                <div>
                  <a href="<?php echo APPURL; ?>" class="view-list px-3 border-right active">All</a>
                  <a href="rent.php?type=rent" class="view-list px-3 border-right">Rent</a>
                  <a href="sale.php?type=sale" class="view-list px-3">Sale</a>
                  <a href="price.php?price=ASC" class="view-list px-3">Price Ascending</a>
                  <a href="price.php?price=DECS" class="view-list px-3">Price Descending</a>
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
        
        <?php foreach($props as $prop):?>
          <div class="col-md-6 col-lg-4 mb-4">
            <div class="property-entry h-100">
              <a href="property-details.php?id=<?php echo $prop->id; ?>" class="property-thumbnail">
                <div class="offer-type-wrap">
                  <span class="offer-type bg-
                  <?php 
                    if($prop->type == "rent") { 
                      echo "bg-success text-white"; 
                    } else { 
                      echo "bg-danger text-white"; 
                    }
                  ?>"><?php echo $prop->type; ?></span>
                </div>
                <img src="<?php echo THUMBNAILURL; ?>/<?php echo $prop->image; ?>" alt="Image" class="img-fluid">
              </a>
              <div class="p-4 property-body">
                <h2 class="property-title"><a href="property-details.php?id=<?php echo $prop->id; ?>"><?php echo $prop->name; ?></a></h2>
                <span class="property-location d-block mb-3"><span class="property-icon icon-room"></span> <?php echo $prop->location; ?></span>
                <strong class="property-price text-primary mb-3 d-block text-success">RM<?php echo $prop->price; ?></strong>
                <ul class="property-specs-wrap mb-3 mb-lg-0">
                  <li>
                    <span class="property-specs">Beds</span>
                    <span class="property-specs-number"><?php echo $prop->beds; ?></span>
                    
                  </li>
                  <li>
                    <span class="property-specs">Baths</span>
                    <span class="property-specs-number"><?php echo $prop->baths; ?></span>
                    
                  </li>
                  <li>
                    <span class="property-specs">SQ FT</span>
                    <span class="property-specs-number"><?php echo $prop->sqft; ?></span>
                    
                  </li>
                </ul>

              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
     
        
      </div>
    </div>

    <div class="site-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-7 text-center">
            <div class="site-section-title">
              <h2>Why Choose Us?</h2>
            </div>
            <p>
            At AuctionXpert, we offer more than just a real estate service. Discover why choosing us means choosing excellence, transparency, and a partner in your property journey.          
                  </p>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 col-lg-4">
            <a href="#" class="service text-center">
              <span class="icon flaticon-house"></span>
              <h2 class="service-heading">Research Subburbs</h2>
              <p>
              Uncover the perfect neighborhood with our comprehensive suburb research at AuctionXpert. We provide you with in-depth insights into local communities, schools, amenities, and more, ensuring you make an informed decision that aligns with your lifestyle.              
            </p>
              <p><span class="read-more">Read More</span></p>
            </a>
          </div>
          <div class="col-md-6 col-lg-4">
            <a href="#" class="service text-center">
              <span class="icon flaticon-sold"></span>
              <h2 class="service-heading">Sold Houses</h2>
              <p>
              Join a community of satisfied homeowners at AuctionXpert. Our track record of successfully sold houses speaks volumes about our commitment to achieving the best results for our clients. Trust AuctionXpert to turn your property goals into reality.              </p>
              <p><span class="read-more">Read More</span></p>
            </a>
          </div>
          <div class="col-md-6 col-lg-4">
            <a href="#" class="service text-center">
              <span class="icon flaticon-camera"></span>
              <h2 class="service-heading">Security Priority</h2>
              <p>
              Your safety is our priority at AuctionXpert. We go the extra mile to ensure the security of your transactions. From encrypted communications to secure payment processes, rest assured that your real estate journey with AuctionXpert is not only successful but also secure.              </p>
              <p><span class="read-more">Read More</span></p>
            </a>
          </div>
        </div>
      </div>
    </div>

    <?php require "includes/footer.php"; ?>