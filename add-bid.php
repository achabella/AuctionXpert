<div class="col-lg-4">
          <div class="bg-white widget border rounded">
      <h3 class="h4 text-black widget-title mb-3">Bid</h3>
      <?php if(isset($_SESSION['user_id'])) : ?>
        <div class="container-fluid">
            <label for="" class="control-label">Bidding Date & Time</label>
            <p>Start: <b><?php echo date("M d, Y h:i A", strtotime($allDetails->start_datetime)) ?></b></p>
        </div> 
        <div class="container-fluid">
            <p>Until: <b><?php echo date("M d, Y h:i A", strtotime($allDetails->end_datetime)) ?></b></p>
        </div>
        <div class="container-fluid">
            <p>Starting Amount: <large><b>RM<?php echo $allDetails->price;?></b></large></p>
        </div>
        <div class="container-fluid">
            <p>Highest Bid: <large><b></b></large></p>
        </div>
        <div class="col-md-12" id="bid-container">
            <button class="btn btn-primary" type="submit" id="bid" onclick="showBidForm()">Bid</button>
        </div>
        <div id="bid-frm" style="display: none;">
            <div class="col-md-12">
                <form id="manage-bid">
                    <input type="hidden" name="product_id" value="">
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
        </script>

    <?php else : ?>
        <p>Login in order to join the auction for this property</p>
    <?php endif ; ?>
</div>
