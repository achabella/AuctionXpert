<?php require "includes/header.php"; ?>
<?php require "config/config.php"; ?>
<?php


  if(isset($_GET['id'])){

    $id = $_GET['id'];

    $single = $conn->query("SELECT * FROM props WHERE id='$id'");

    $single->execute();

    $allDetails = $single->fetch(PDO::FETCH_OBJ);

    //display related prop
    $relatedProps = $conn->query("SELECT * FROM props WHERE home_type = '$allDetails->home_type' 
    AND id != '$id'");

    $relatedProps->execute();

    $allRelatedProps = $relatedProps->fetchAll(PDO::FETCH_OBJ);

  } else {

    echo "<script>window.location.href='".APPURL."/404.php'</script>";

  }

  $images = $conn->query("SELECT * FROM related_images WHERE prop_id = '$id' ");
  $images->execute();

  $allImages = $images->fetchAll(PDO::FETCH_OBJ);

  //check user if add current to fav
  if(isset($_SESSION['user_id'])){

    $check = $conn->query("SELECT * FROM favs WHERE prop_id= '$id' AND user_id= '$_SESSION[user_id]'");
    $check->execute();

  }

// Fetch deposit status for the logged-in user
$user_id = $_SESSION['user_id'];
$fetch_deposit_status = $conn->prepare("SELECT deposit FROM users WHERE id = :user_id");
$fetch_deposit_status->execute([':user_id' => $user_id]);
$deposit_status = $fetch_deposit_status->fetch(PDO::FETCH_ASSOC);

// Check if the user has already deposited
$user_has_deposited = ($deposit_status['deposit'] == 1);

// Fetch the current highest bid
$fetch_highest_bid = $conn->prepare("SELECT MAX(bid_amount) AS highest_bid FROM bids WHERE prop_id = :prop_id");
$fetch_highest_bid->execute([':prop_id' => $id]);
$highest_bid = $fetch_highest_bid->fetch(PDO::FETCH_ASSOC);
$current_highest_bid = ($highest_bid['highest_bid']) ? $highest_bid['highest_bid'] : 0;

// Fetch the owner details for the current property
$fetch_owner = $conn->prepare("SELECT * FROM owners WHERE user_id = :user_id AND prop_id = :prop_id");
$fetch_owner->execute([':user_id' => $_SESSION['user_id'], ':prop_id' => $id]);
$is_owner = $fetch_owner->fetch(PDO::FETCH_ASSOC);

// Check if the user is the owner of the property
$user_is_owner = !empty($is_owner);

?>
    <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url(<?php echo THUMBNAILURL; ?>/<?php echo $allDetails->image;?>);" data-aos="fade" data-stellar-background-ratio="0.5">
      <div class="container">
        <div class="row align-items-center justify-content-center text-center">
          <div class="col-md-10">
            <span class="d-inline-block text-white px-3 mb-3 property-offer-type rounded">Property Details of</span>
            <h1 class="mb-2"><?php echo $allDetails->name;?></h1>
            <p class="mb-5"><strong class="h2 text-success font-weight-bold">RM<?php echo $allDetails->price;?></strong></p>
          </div>
        </div>
      </div>
    </div>

    <div class="site-section site-section-sm">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <div>
              <div class="slide-one-item home-slider owl-carousel">
                <?php foreach($allImages as $image) : ?>
                <div><img src="<?php echo GALLERYURL; ?>/<?php echo $image->image; ?>" alt="Image" class="img-fluid"></div>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="bg-white property-body border-bottom border-left border-right">
              <div class="row mb-5">
                <div class="col-md-6">
                  <strong class="text-success h1 mb-3">RM<?php echo $allDetails->price;?></strong>
                </div>
                <div class="col-md-6">
                  <ul class="property-specs-wrap mb-3 mb-lg-0  float-lg-right">
                  <li>
                    <span class="property-specs">Beds</span>
                    <span class="property-specs-number"><?php echo $allDetails->beds;?></span>
                    
                  </li>
                  <li>
                    <span class="property-specs">Baths</span>
                    <span class="property-specs-number"><?php echo $allDetails->baths;?></span>
                    
                  </li>
                  <li>
                    <span class="property-specs">SQ FT</span>
                    <span class="property-specs-number"><?php echo $allDetails->sqft;?></span>
                    
                  </li>
                </ul>
                </div>
              </div>
              <div class="row mb-5">
                <div class="col-md-6 col-lg-4 text-center border-bottom border-top py-3">
                  <span class="d-inline-block text-black mb-0 caption-text">Home Type</span>
                  <strong class="d-block"><?php echo str_replace('-', ' ', $allDetails->home_type);?></strong>
                </div>
                <div class="col-md-6 col-lg-4 text-center border-bottom border-top py-3">
                  <span class="d-inline-block text-black mb-0 caption-text">Year Built</span>
                  <strong class="d-block"><?php echo $allDetails->year_built;?></strong>
                </div>
                <div class="col-md-6 col-lg-4 text-center border-bottom border-top py-3">
                  <span class="d-inline-block text-black mb-0 caption-text">Price/Sqft</span>
                  <strong class="d-block">RM<?php echo $allDetails->price_sqft;?></strong>
                </div>
              </div>
              <h2 class="h4 text-black">More Info</h2>
              <p><?php echo $allDetails->description;?></p>
              <div class="row no-gutters mt-5">
                <div class="col-12">
                  <h2 class="h4 text-black mb-3">Gallery</h2>
                </div>

                <?php foreach($allImages as $image) : ?>
                  <div class="col-sm-6 col-md-4 col-lg-3">
                   <a href="<?php echo GALLERYURL; ?>/<?php echo $image->image; ?>" class="image-popup gal-item"><img src="<?php echo GALLERYURL; ?>/<?php echo $image->image; ?>" alt="Image" class="img-fluid"></a>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
          <div class="bg-white widget border rounded">
    <h3 class="h4 text-black widget-title mb-3 ml-0">Bid</h3>

    <?php if (isset($_SESSION['user_id'])) : ?>
        <?php if ($user_has_deposited) : ?>
            <?php if (!$user_is_owner) : ?>
                    <div class="container-fluid">
                        <label for="" class="control-label">Bidding Date & Time</label>
                        <p>Start: <b><?php echo date("M d, Y h:i A", strtotime($allDetails->start_datetime)) ?></b></p>
                    </div>
                    <div class="container-fluid">
                        <p>Until: <b><?php echo date("M d, Y h:i A", strtotime($allDetails->end_datetime)) ?></b></p>
                    </div>
                    <div class="container-fluid">
                        <p>Starting Amount: <large><b id="starting-bid">RM <?php echo number_format($allDetails->starting_price, 2); ?></b></large></p>
                    </div>

                    <?php
                    $current_datetime = new DateTime();
                    $auction_start_datetime = new DateTime($allDetails->start_datetime);
                    $auction_end_datetime = new DateTime($allDetails->end_datetime);


                    // Check if the current date and time are after the start_datetime
                    if ($current_datetime > $auction_start_datetime && $current_datetime < $auction_end_datetime) :
                    ?>

                    <div class="container-fluid">
                        <p>Current Bid: <large><b id="current-highest-bid">RM <?php echo number_format($current_highest_bid, 2); ?></b></large></p>
                    </div>
                    <div class="col-md-12" id="bid-container">
                        <button class="btn btn-primary" type="submit" id="bid" onclick="showBidForm()">Bid</button>
                    </div>
                    <div id="bid-frm" style="display: none;">
                        <div class="col-md-12">
                            <form id="manage-bid" action="save-bid.php" method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $allDetails->id; ?>">
                                <div class="form-group">
                                    <label for="" class="control-label">Bid Amount</label>
                                    <input type="number" class="form-control text-left" name="bid_amount">
                                </div>
                                <div class="row justify-content-between">
                                    <button class="btn col-sm-5 btn-primary btn-block btn-sm mr-2">Submit</button>
                                    <button class="btn col-sm-5 btn-secondary mt-0 btn-block btn-sm" type="button" id="cancel_bid" onclick="cancelBid()">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                        function showBidForm() {
                            var bidContainer = document.getElementById('bid-container');
                            bidContainer.style.display = 'none'; // Hide the bid button

                            var bidForm = document.getElementById('bid-frm');
                            bidForm.style.display = 'block'; // Show the bid form
                        }

                        function cancelBid() {
                            var bidContainer = document.getElementById('bid-container');
                            bidContainer.style.display = 'block'; // Show the bid button

                            var bidForm = document.getElementById('bid-frm');
                            bidForm.style.display = 'none'; // Hide the bid form
                        }

                        // Additional function to validate bid amount
                        function validateBidAmount() {
                            var latestBid = parseFloat(document.getElementById('current-highest-bid').innerText.replace('RM ', '').replace(',', ''));
                            var startingPrice = parseFloat(document.getElementById('starting-bid').innerText.replace('RM ', '').replace(',', ''));
                            var bidAmount = parseFloat(document.getElementsByName('bid_amount')[0].value);

                            if (bidAmount <= latestBid || bidAmount <= startingPrice) {
                                alert('Bid amount must be higher than the current highest bid and the starting price.');
                                return false;
                            }

                            return true;
                        }

                        // Add an event listener to the form for bid amount validation
                        document.getElementById('manage-bid').addEventListener('submit', function (event) {
                            if (!validateBidAmount()) {
                                event.preventDefault(); // Prevent form submission if validation fails
                            }
                        });
                    </script>

                <?php else : ?>
                    <!-- Auction has not started yet, show a message -->
                    <!-- <p>The auction for this property has not started yet.</p> -->
                <?php endif; ?>

            <?php else : ?>
                <!-- User is the owner, show a message -->
                <p>You cannot bid on your own property.</p>
            <?php endif; ?>
        <?php else : ?>
            <!-- User has not deposited, show message -->
            <p>You need to make a deposit to place a bid. <a href="user/deposit.php">Make a deposit</a></p>
        <?php endif; ?>

    <?php else : ?>
        <p>Login in order to join the auction for this property</p>
    <?php endif; ?>
</div>



            <div class="bg-white widget border rounded">
              <h3 class="h4 text-black widget-title mb-3 ml-0">Add this to Fav</h3>
                  <div class="px-3" style="margin-left: -15px;">

                  <?php if(isset($_SESSION['user_id'])) : ?>
                  <form action="favs/add-fav.php" class="form-contact-agent" method="POST">
                    <div class="form-group">
                      <input type="hidden" id="name" name="prop_id" value="<?php echo $id; ?>" class="form-control">
                    </div>
                    <div class="form-group">
                      <input type="hidden" id="email" name="user_id" value="<?php echo $_SESSION['user_id']; ?>" class="form-control">
                    </div>

                    <?php if($check->rowCount() > 0) : ?>
                    <div class="form-group">
                      <a href="favs/delete-fav.php?prop_id=<?php echo $id ; ?>&user_id=<?php echo $_SESSION["user_id"] ?>" class="btn btn-primary text-white">Added to fav</a>
                    </div>

                    <?php else :?>

                      <div class="form-group">
                      <input type="submit" name="submit" id="phone" class="btn btn-primary" value="Add to fav">
                    </div>

                    <?php endif ; ?>


              </form>
              <?php else : ?>
                <p>Login in order to add this property to fav</p>
              <?php endif ; ?>
                </div>            
            </div>
          </div>
        </div> 
      </div>
    </div>

    <div class="site-section site-section-sm bg-light">
      <div class="container">
        
        <div class="row">
          <div class="col-12">
            <div class="site-section-title mb-5">
              <h2>Related Properties</h2>
            </div>
          </div>
        </div>
      
        <div class="row mb-5">
        <?php foreach($allRelatedProps as $allRelatedProp): ?>
          <div class="col-md-6 col-lg-4 mb-4">
            <div class="property-entry h-100">
              <a href="property-details.php?id=<?php echo $allRelatedProp->id; ?>" class="property-thumbnail">
                <div class="offer-type-wrap">
                  <span class="offer-type bg-
                  <?php 
                    if($allRelatedProp->type == "rent") { 
                      echo "bg-success text-white"; 
                    } else { 
                      echo "bg-danger text-white"; 
                    }
                  ?>"><?php echo $allRelatedProp->type; ?></span>
                </div>
                <img src="<?php echo THUMBNAILURL; ?>/<?php echo $allRelatedProp->image; ?>" alt="Image" class="img-fluid">
              </a>
              <div class="p-4 property-body">
                <h2 class="property-title"><a href="property-details.php?id=<?php echo $allRelatedProp->id; ?>"><?php echo $allRelatedProp->name; ?></a></h2>
                <span class="property-location d-block mb-3"><span class="property-icon icon-room"></span><?php echo $allRelatedProp->location; ?></span>
                <strong class="property-price text-primary mb-3 d-block text-success">RM<?php echo $allRelatedProp->price; ?></strong>
                <ul class="property-specs-wrap mb-3 mb-lg-0">
                  <li>
                    <span class="property-specs">Beds</span>
                    <span class="property-specs-number"><?php echo $allRelatedProp->beds; ?></span>
                    
                  </li>
                  <li>
                    <span class="property-specs">Baths</span>
                    <span class="property-specs-number"><?php echo $allRelatedProp->baths; ?></span>
                    
                  </li>
                  <li>
                    <span class="property-specs">SQ FT</span>
                    <span class="property-specs-number"><?php echo $allRelatedProp->sqft; ?></span>
                    
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