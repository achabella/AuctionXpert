<?php require "../includes/header.php"; ?>
<?php require "../config/config.php"; ?>
<?php

if (isset($_SESSION['username'])) {
    echo "<script>window.location.href='" . APPURL . "'</script>";
}

if (isset($_POST['submit'])) {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        echo "<script>alert('Some inputs are empty');</script>";
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $login = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $login->bindParam(':email', $email);
        $login->execute();
        $fetch = $login->fetch(PDO::FETCH_ASSOC);

        if ($login->rowCount() > 0) {
          // Check if the user is currently locked out
          if ($fetch['wrong_attempts'] >= 3) {
              echo "<script>alert('Login disabled due to multiple incorrect attempts. Please contact the admin at contact page to reset your account.');</script>";
              echo "<script>window.location.href='" . APPURL . "/contact.php'</script>";

          } else {
              if (password_verify($password, $fetch['mypassword'])) {
                  // Reset wrong attempts on successful login
                  $conn->query("UPDATE users SET wrong_attempts = 0 WHERE id = " . $fetch['id']);

                  $_SESSION['username'] = $fetch['username'];
                  $_SESSION['email'] = $fetch['email'];
                  $_SESSION['user_id'] = $fetch['id'];

                  echo "<script>window.location.href='" . APPURL . "'</script>";
              } else {
                  // Increment wrong attempts on failed login
                  $conn->query("UPDATE users SET wrong_attempts = wrong_attempts + 1 WHERE id = " . $fetch['id']);
                  echo "<script>alert('Email or password is wrong');</script>";

                  // Check for lockout condition (3 consecutive wrong attempts)
                  if ($fetch['wrong_attempts'] >= 2) {
                    echo "<script>alert('Login disabled due to multiple incorrect attempts. Please contact the admin at contact page to reset your account.');</script>";
                      echo "<script>window.location.href='" . APPURL . "/contact.php'</script>";
                  }
              }
          }
      } else {
          echo "<script>alert('Email or password is wrong');</script>";
      }
  }
}
?>
  
  <div class="site-wrap">
    <div class="site-blocks-cover inner-page-cover overlay" style="background-image: url(<?php echo APPURL; ?>/images/hero_bg_1.jpg);" data-aos="fade" data-stellar-background-ratio="0.5">
      <div class="container">
        <div class="row align-items-center justify-content-center text-center">
          <div class="col-md-10">
            <h1 class="mb-2">Log In</h1>
          </div>
        </div>
      </div>
    </div>
  <div class="site-section">
      <div class="container">
        <div class="row">
          <div class="col-md-12" data-aos="fade-up" data-aos-delay="100">
            <h3 class="h4 text-black widget-title mb-3">Login</h3>
            <form action="login.php" method="POST" class="form-contact-agent">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" name="submit" id="phone" class="btn btn-primary" value="Login">
            </div>
            </form>
          </div>
         
        </div>
      </div>
    </div>
    <?php
?>
<?php require "../includes/footer.php"; ?>
