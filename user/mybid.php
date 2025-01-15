<?php require "../includes/header.php"; ?>
<?php require "../config/config.php"; ?>

<?php
if (!isset($_SESSION['username'])) {
    echo "<script>window.location.href='" . APPURL . "'</script>";
}

// Check if the auction has ended
$currentDateTime = date('Y-m-d H:i:s');
$checkAuctionEnd = $conn->query("SELECT * FROM props WHERE end_datetime < '{$currentDateTime}'");
$auctionEnded = $checkAuctionEnd->rowCount() > 0;

$favs = $conn->query("SELECT props.id AS id, props.name AS name, props.location AS location, props.image AS image, 
props.price AS price, props.beds AS beds, props.baths AS baths, props.sqft AS sqft, props.type AS type,
props.end_datetime AS end_datetime
FROM props 
JOIN (
    SELECT prop_id, MAX(bid_amount) AS max_bid
    FROM bids
    WHERE user_id = '$_SESSION[user_id]'
    GROUP BY prop_id
) AS user_bids ON props.id = user_bids.prop_id
JOIN bids ON user_bids.prop_id = bids.prop_id AND user_bids.max_bid = bids.bid_amount
");

$favs->execute();
$props = $favs->fetchAll(PDO::FETCH_OBJ);


?>

<div class="site-wrap">    

    <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url(<?php echo APPURL; ?>/images/hero_bg_1.jpg);" data-aos="fade" data-stellar-background-ratio="0.5">
        <div class="container">
            <div class="row align-items-center justify-content-center text-center">
                <div class="col-md-10">
                    <h1 class="mb-2">My Bid</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row mb-5">
            <?php if (count($props) > 0): ?>
                <?php foreach ($props as $prop): ?>
                    <div class="col-md-6 col-lg-4 mb-4 mt-5">
                        <div class="property-entry h-100">
                            <a href="../property-details.php?id=<?php echo $prop->id; ?>" class="property-thumbnail">
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
                                <img src="<?php echo APPURL; ?>/images/<?php echo $prop->image; ?>" alt="Image" class="img-fluid">
                            </a>
                            <div class="p-4 property-body">
                                <h2 class="property-title"><a href="../property-details.php?id=<?php echo $prop->id; ?>"><?php echo $prop->name; ?></a></h2>
                                <span class="property-location d-block mb-4"><span class="property-icon icon-room"></span>End Date Time: <?php echo $prop->end_datetime; ?></span>
                                <div class="container-fluid">
                                    <?php
                                    // Fetch the current highest bid for the property
                                    $fetch_highest_bid = $conn->prepare("SELECT MAX(bid_amount) AS highest_bid FROM bids WHERE prop_id = :prop_id");
                                    $fetch_highest_bid->execute([':prop_id' => $prop->id]);
                                    $highest_bid = $fetch_highest_bid->fetch(PDO::FETCH_ASSOC);
                                    $current_highest_bid = ($highest_bid['highest_bid']) ? $highest_bid['highest_bid'] : 0;

                                    // Fetch the user's bid for the property
                                    $fetch_user_bid = $conn->prepare("SELECT bid_amount, status FROM bids WHERE prop_id = :prop_id AND user_id = :user_id ORDER BY date_created DESC LIMIT 1");
                                    $fetch_user_bid->execute([':prop_id' => $prop->id, ':user_id' => $_SESSION['user_id']]);
                                    $user_bid = $fetch_user_bid->fetch(PDO::FETCH_ASSOC);
                                    $user_bid_amount = ($user_bid['bid_amount']) ? $user_bid['bid_amount'] : 0;
                                    $user_bid_status = ($user_bid['status']) ? $user_bid['status'] : 0;
                                    ?>
                                    <p>Highest Bid: <large><b id="current-highest-bid">RM <?php echo number_format($current_highest_bid, 2); ?></b></large></p>
                                    <p>Your Bid: <large><b id="user-bid">RM <?php echo number_format($user_bid_amount, 2); ?></b></large></p>
                                    <p>Status: <large><b id="user-bid-status">
                                        <?php
                                            if ($auctionEnded) {
                                                if ($user_bid_status == 2) {
                                                    echo '<span class="badge badge-success text-white">Wins in Bidding</span>';
                                                } elseif ($user_bid_status == 3) {
                                                    echo '<span class="badge badge-danger">Lose in Bidding</span>';
                                                } else {
                                                    echo '<span class="badge badge-secondary">Bidding Stage</span>';
                                                }
                                            } else {
                                                echo '<span class="badge badge-secondary">Bidding Stage</span>';
                                            }
                                        ?>
                                    </b></large></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="bg-success text-white">You did not bid any properties yet</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require "../includes/footer.php"; ?>
