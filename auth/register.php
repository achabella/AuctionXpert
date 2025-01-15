<?php
session_start();
require "../includes/header.php";
require "../config/config.php";

if(isset($_SESSION['username'])){
    header("location:".APPURL."");
}

if (isset($_POST['submit'])){
    if(empty($_POST['username']) || empty($_POST['email']) || empty($_POST['phone_no']) || empty($_POST['address']) || empty($_POST['password']) || empty($_POST['confirm_password'])){
        echo "<script>alert('Some inputs are empty');</script>";
    } else {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $phone_no = $_POST['phone_no'];
        $address = $_POST['address'];

        if ($password !== $confirm_password) {
            echo "<script>alert('Passwords do not match');</script>";
        } else {
            $insert = $conn->prepare("INSERT INTO users (username, email, phone_no, address, mypassword) VALUES 
            (:username, :email, :phone_no, :myaddress, :mypassword)");

            $insert->execute([
                ':username'=> $username,
                ':email'=> $email,
                ':phone_no'=> $phone_no,
                ':myaddress'=> $address,
                ':mypassword'=> password_hash($password, PASSWORD_DEFAULT)
            ]);

            echo "<script>window.location.href='".APPURL."/auth/login.php'</script>";
        }
    }
}
?>

<div class="site-wrap">
    <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url(<?php echo APPURL;?>/images/hero_bg_1.jpg);" data-aos="fade" data-stellar-background-ratio="0.5">
        <div class="container">
            <div class="row align-items-center justify-content-center text-center">
                <div class="col-md-10">
                    <h1 class="mb-2">Register</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12" data-aos="fade-up" data-aos-delay="100">
                    <h3 class="h4 text-black widget-title mb-3">Register</h3>
                    <form action="register.php" method="POST" class="form-contact-agent">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="phone_no">Phone Number</label>
                            <input type="text" name="phone_no" id="phone_no" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input type="text" name="address" rows="5" style="height: 150px;" id="address" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Register">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require "../includes/footer.php"; ?>
