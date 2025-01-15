<?php require "../layouts/header.php"; ?>  
<?php require "../../config/config.php"; 

if (!isset($_SESSION['adminname'])) {
    echo "<script>window.location.href='".ADMINURL."/admins/login-admins.php' </script>";
}

// Check if the auction has ended
$currentDateTime = date('Y-m-d H:i:s');
$checkAuctionEnd = $conn->query("SELECT * FROM props WHERE end_datetime < '{$currentDateTime}'");
$auctionEnded = $checkAuctionEnd->rowCount() > 0;

// Update the status of users if the auction has ended
if ($auctionEnded) {
    $updateStatusQuery = "UPDATE bids b
        SET status = 
            CASE
                WHEN b.user_id = (
                        SELECT user_id 
                        FROM bids 
                        WHERE prop_id = b.prop_id 
                        ORDER BY bid_amount DESC 
                        LIMIT 1
                    ) 
                    AND b.bid_amount = (
                        SELECT MAX(bid_amount) 
                        FROM bids 
                        WHERE prop_id = b.prop_id
                    ) 
                    THEN 2
                ELSE 3
            END
        WHERE b.status = 1 
          AND EXISTS (
                SELECT 1 
                FROM props p 
                WHERE p.id = b.prop_id 
                  AND p.end_datetime < '{$currentDateTime}'
            )";

    $conn->query($updateStatusQuery);

    // Update deposit for users with status 2
    $updateDepositQuery = "UPDATE users u
        SET deposit = 0
        WHERE u.id IN (
            SELECT user_id
            FROM bids
            WHERE status = 2
        )";

    $conn->query($updateDepositQuery);
}

?>
<div class="row">
    <!-- Table Panel -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4 d-inline">List of Bids</h5>
            </div>
            <div class="card-body">
                <table class="table table-condensed table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="">Name</th>
                            <th class="">Product</th>
                            <th class="">Amount (RM)</th>
                            <th class="text-center">Status</th>
                            <th class=""></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $users = array();
                        $users[] = '';
                        $qryUsers = $conn->query("SELECT * FROM users");
                        while ($rowUser = $qryUsers->fetch(PDO::FETCH_ASSOC)) {
                            $users[$rowUser['id']] = $rowUser['username'];
                        }

                        $props = array();
                        $props[] = '';
                        $qryProps = $conn->query("SELECT * FROM props");
                        while ($rowProp = $qryProps->fetch(PDO::FETCH_ASSOC)) {
                            $props[$rowProp['id']] = $rowProp['name'];
                        }

                        $bids = $conn->query("SELECT b.*, u.username as username, p.name as property_name, u.email, u.phone_no, u.address FROM bids b 
                            INNER JOIN users u ON u.id = b.user_id 
                            INNER JOIN props p ON p.id = b.prop_id");
                        while ($rowBid = $bids->fetch(PDO::FETCH_ASSOC)) :
                        ?>
                            <tr>
                                <td class="text-center">
                                    <?php echo $i++ ?></td>
                                <td class="">
                                    <p><?php echo ucwords($rowBid['username']) ?></b></p>
                                </td>
                                <td class="">
                                    <p><?php echo ucwords($rowBid['property_name']) ?></b></p>
                                </td>
                                <td class="text-left">
                                    <p><?php echo number_format($rowBid['bid_amount'], 2) ?></b></p>
                                </td>
                                <td class="text-center">
                                    <?php
                                    if ($auctionEnded) {
                                        if ($rowBid['status'] == 2) {
                                            echo '<span class="badge badge-success">Wins in Bidding</span>';

                                            // Check if a congratulatory message already exists for the user and property
                                            if (!congratulatoryMessageExists($conn, $rowBid['user_id'], $rowBid['prop_id'])) {
                                                // Add congratulatory message to the winner table
                                                $congratulatoryMessage = "Congratulations! You are the winner of the auction for the property '{$rowBid['property_name']}'.\nOur team will contact you shortly to provide further details and instructions on the next steps.";
                                                $insertWinnerQuery = "INSERT INTO winner (user_id, prop_id, mymessage) VALUES (:user_id, :prop_id, :mymessage)";
                                                $insertWinnerStmt = $conn->prepare($insertWinnerQuery);
                                                $insertWinnerStmt->bindParam(':user_id', $rowBid['user_id'], PDO::PARAM_INT);
                                                $insertWinnerStmt->bindParam(':prop_id', $rowBid['prop_id'], PDO::PARAM_INT);
                                                $insertWinnerStmt->bindParam(':mymessage', $congratulatoryMessage, PDO::PARAM_STR);
                                                $insertWinnerStmt->execute();

                                                // Save the congratulatory message into winner.sql
                                                $congratulatoryMessageSql = "-- Congratulatory Message for User ID: {$rowBid['user_id']}, Prop ID: {$rowBid['prop_id']}\n";
                                                $congratulatoryMessageSql .= "INSERT INTO winner (user_id, prop_id, mymessage) VALUES ({$rowBid['user_id']}, {$rowBid['prop_id']}, '\n{$congratulatoryMessage}');\n";
                                                file_put_contents('winner.sql', $congratulatoryMessageSql, FILE_APPEND);
                                            }
                                        } elseif ($rowBid['status'] == 3) {
                                            echo '<span class="badge badge-danger">Lose in Bidding</span>';
                                        } else {
                                            echo '<span class="badge badge-secondary">Bidding Stage</span>';
                                        }
                                    } else {
                                        echo '<span class="badge badge-secondary">Bidding Stage</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <!-- Add hidden elements with user details -->
                                    <div class="user-details" data-id="<?php echo $rowBid['user_id']; ?>" style="display: none;">
                                        <p>Username: <?php echo $rowBid['username']; ?></p>
                                        <p>Email: <?php echo $rowBid['email']; ?></p>
                                        <p>Phone Number: <?php echo $rowBid['phone_no']; ?></p>
                                        <p>Address: <?php echo $rowBid['address']; ?></p>
                                    </div>
                                    <button class="btn btn-primary btn-sm view_user" type="button">View Bidder Details</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Table Panel -->
</div>

<!-- Modal for Buyer Details -->
<div class="modal fade" id="buyerDetailsModal" tabindex="-1" role="dialog" aria-labelledby="buyerDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buyerDetailsModalLabel">Bidder Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="buyerDetailsContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Add click event listener to all "View Buyer Details" buttons
        document.querySelectorAll('.view_user').forEach(function (button) {
            button.addEventListener('click', function () {
                // Find the closest user-details div relative to the clicked button
                var userDetailsDiv = this.closest('td').querySelector('.user-details');

                // Get the content of the user details
                var userDetailsContent = userDetailsDiv.innerHTML;

                // Populate the modal content with user details
                document.getElementById('buyerDetailsContent').innerHTML = userDetailsContent;

                // Show the modal
                $('#buyerDetailsModal').modal('show');
            });
        });
    });
</script>

<style>
    td {
        vertical-align: middle !important;
    }

    td p {
        margin: unset
    }

    img {
        max-width: 100px;
        max-height: :150px;
    }
</style>
<?php
// Function to check if a congratulatory message already exists for the user and property
function congratulatoryMessageExists($conn, $user_id, $prop_id) {
    $checkExistsQuery = "SELECT COUNT(*) FROM winner WHERE user_id = :user_id AND prop_id = :prop_id";
    $checkExistsStmt = $conn->prepare($checkExistsQuery);
    $checkExistsStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $checkExistsStmt->bindParam(':prop_id', $prop_id, PDO::PARAM_INT);
    $checkExistsStmt->execute();
    return $checkExistsStmt->fetchColumn() > 0;
}

?>

<?php require "../layouts/footer.php"; ?>