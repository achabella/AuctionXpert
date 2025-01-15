<?php require "../includes/header.php"; ?>
<?php require "../config/config.php"; ?>

<?php
if (!isset($_SESSION['username'])) {
    echo "<script>window.location.href='" . APPURL . "'</script>";
}

// Fetch notifications from the 'winner' table
$notificationsQuery = "SELECT * FROM winner WHERE user_id = :user_id";
$notificationsStmt = $conn->prepare($notificationsQuery);
$notificationsStmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$notificationsStmt->execute();
$notifications = $notificationsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="site-wrap">    
    <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url(<?php echo APPURL; ?>/images/hero_bg_1.jpg);" data-aos="fade" data-stellar-background-ratio="0.5">
        <div class="container">
            <div class="row align-items-center justify-content-center text-center">
                <div class="col-md-10">
                    <h1 class="mb-2">Notification</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="site-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12" data-aos="fade-up" data-aos-delay="100">
                    <!-- Display notifications from admin here -->
                    <?php if ($notifications) : ?>
                        <ul class="list-group">
                            <?php foreach ($notifications as $notification) : ?>
                                <li class="list-group-item"><?php echo $notification['mymessage']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p>No notifications at the moment.</p>
                    <?php endif; ?>
                </div>
            </div>   
        </div>
    </div>   
</div>

<?php require "../includes/footer.php"; ?>
