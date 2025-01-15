<?php require "../includes/header.php"; ?>
<?php require "../config/config.php"; ?>

<?php

if (!isset($_SESSION['username'])) {
    echo "<script>window.location.href='" . APPURL . "'</script>";
}

// Fetch deposit status for the logged-in user
$user_id = $_SESSION['user_id'];
$fetch_deposit_status = $conn->prepare("SELECT deposit FROM users WHERE id = :user_id");
$fetch_deposit_status->execute([':user_id' => $user_id]);
$deposit_status = $fetch_deposit_status->fetch(PDO::FETCH_ASSOC);

// Check if the user has already deposited
$user_has_deposited = ($deposit_status['deposit'] == 1);

// Process form submission if the form is submitted
if (isset($_POST['submit_deposit'])) {
    $deposit_amount = $_POST['deposit_amount'];

    // Validate deposit amount
    if (!is_numeric($deposit_amount) || $deposit_amount != 500) {
        echo "<script>alert('Invalid deposit amount. Please enter RM500.');</script>";
    } else {
        // Process the deposit
        $user_id = $_SESSION['user_id'];

        // Update the 'deposit' column in the 'users' table to 1
        $update_deposit = $conn->prepare("UPDATE users SET deposit = 1 WHERE id = :user_id");

        try {
            $update_deposit->execute([':user_id' => $user_id]);

            echo "<script>alert('Deposit successful!');</script>";
        } catch (PDOException $e) {
            echo "Error updating deposit: " . $e->getMessage();
        }
    }
}

?>

<div class="site-wrap">    

<div class="site-blocks-cover inner-page-cover overlay" style="background-image: url(<?php echo APPURL; ?>/images/hero_bg_1.jpg);" data-aos="fade" data-stellar-background-ratio="0.5">
  <div class="container">
    <div class="row align-items-center justify-content-center text-center">
      <div class="col-md-10">
        <h1 class="mb-2">Deposit</h1>
      </div>
    </div>
  </div>
</div>
    <div class="site-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12" data-aos="fade-up" data-aos-delay="100">
                <h3 class="h4 text-black widget-title mb-3">Deposit</h3>

                <?php if ($user_has_deposited) : ?>
                    <p>You have already made a deposit. Thank you!</p>
                <?php else : ?>
                    <form method="POST" action="deposit.php">
                        <div class="form-outline mb-4 mt-4">
                        <label for="deposit_amount"><strong>Deposit Amount (RM)</strong></label>
                            <input type="text" name="deposit_amount" class="form-control" value="500" readonly />
                        </div>
                        <div class="form-outline mb-4 mt-4">
                        <label><strong>Payment Type</strong></label>
                            <div class="icon-container">
                                            <div class="round-radio">
                                                <input type="radio" id="fpxRadio" name="payment_type" value="fpx" required>
                                                <label for="fpxRadio"><i class="fa fa-credit-card"></i> FPX</label>
                                            </div>
                                            <div class="col-50">
                                                    <div class="form-group">
                                                        <select name="bank_selection" class="form-control">
                                                            <option value="Affin Bank">Affin Bank</option>
                                                            <option value="Agrobank">Agrobank</option>
                                                            <option value="Alliance Bank">Alliance Bank</option>
                                                            <option value="AmBank">AmBank</option>
                                                            <option value="Bank Islam">Bank Islam</option>
                                                            <option value="Bank Muamalat">Bank Muamalat</option>
                                                            <option value="CIMB">CIMB</option>
                                                            <option value="Maybank (M2U & M2e)">Maybank (M2U & M2e)</option>
                                                            <option value="OCBC Bank">OCBC Bank</option>
                                                            <option value="Public Bank">Public Bank</option>
                                                            <option value="RHB Bank">RHB Bank</option>
                                                        </select>
                                                    </div>
                                            </div>
                                            <div class="round-radio">
                                                <input type="radio" id="cardRadio" name="payment_type" value="card" required>
                                                <label for="cardRadio"><i class="fa fa-credit-card"></i> Credit/Debit Card</label>
                                            </div>
                                            <div class="col-50">
                                    <div id="card-details" >
                                        <div class="form-group">
                                            <label for="card_holder_name">Card Holder's Name</label>
                                            <input type="text" name="card_holder_name" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label for="card_number">Card Number</label>
                                            <input type="text" name="card_number" class="form-control" />
                                        </div>
                                        <div class="form-outline mb-4 mt-4" style="display: flex; justify-content: space-between;">
                                            <div style="flex-grow: 1; margin-right: 10px;">
                                                <label for="cvv_number">CVV</label>
                                                <input type="text" name="cvv_number" class="form-control" maxlength="3" />
                                            </div>
                                            <div style="flex-grow: 1;">
                                                <label for="expiry_date">Expiry Date</label>
                                                <input type="date" name="expiry_date" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>            
                        </div>
                    <div class="form-group">
                            <input type="submit" name="submit_deposit" class="btn btn-primary" value="Submit Deposit">
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<style>
    .round-radio {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .round-radio input {
        margin-right: 10px;
    }

    .icon-container {
        display: flex;
        flex-direction: column;
    }

    .round-radio label {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .round-radio i {
        margin-right: 5px;
    }

    #card-details {
        margin-top: 20px;
    }
</style>

<?php require "../includes/footer.php"; ?>