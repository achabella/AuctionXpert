<?php require "includes/header.php"; ?>
<?php require "config/config.php"; ?>
<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['bid_amount'])) {
    // Validate other form inputs as needed

    // Get form data
    $user_id = $_SESSION['user_id']; // Assuming user is logged in
    $prop_id = $_POST['product_id'];
    $bid_amount = $_POST['bid_amount'];

    // Your database connection code goes here (use PDO or mysqli)

    try {
        // Prepare and execute the SQL query to insert the bid
        $stmt = $conn->prepare("INSERT INTO bids (user_id, prop_id, bid_amount) VALUES (:user_id, :prop_id, :bid_amount)");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':prop_id', $prop_id, PDO::PARAM_INT);
        $stmt->bindParam(':bid_amount', $bid_amount, PDO::PARAM_STR);
        $stmt->execute();

        // Add any additional logic or response handling as needed
        echo "Bid saved successfully!";
        
        echo "<script>window.location.href='".APPURL."/property-details.php?id=$prop_id'</script>";

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Handle invalid requests
    echo "Invalid request";
}
?>


