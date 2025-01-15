<?php
require "includes/header.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include your database connection here
    require "config/config.php";

    // Collect form data
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Insert data into the 'requests' table
    $insertQuery = $conn->prepare("INSERT INTO requests (user_id, name, email, subject, message) VALUES (:user_id, :name, :email, :subject, :message)");

    // Assuming user_id is stored in the session
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Bind parameters
    $insertQuery->bindParam(':user_id', $user_id);
    $insertQuery->bindParam(':name', $name);
    $insertQuery->bindParam(':email', $email);
    $insertQuery->bindParam(':subject', $subject);
    $insertQuery->bindParam(':message', $message);

    // Execute the query
    $success = $insertQuery->execute();

        // Display alert only if the query was successful
        if ($success) {
          echo '<script>
                  document.addEventListener("DOMContentLoaded", function () {
                      alert("Your message was sent.");
                  });
                </script>';
      }
  }
  
?>


<div class="site-blocks-cover inner-page-cover overlay" style="background-image: url(images/hero_bg_1.jpg);" data-aos="fade" data-stellar-background-ratio="0.5">
    <div class="container">
        <div class="row align-items-center justify-content-center text-center">
            <div class="col-md-10">
                <h1 class="mb-2">Contact Us</h1>
            </div>
        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-8 mb-5">
                <form method="POST" action="contact.php" class="p-5 bg-white border">
                    <div class="row form-group">
                        <div class="col-md-12 mb-3 mb-md-0">
                            <label class="font-weight-bold" for="fullname">Full Name</label>
                            <input type="text" name="fullname" id="fullname" class="form-control" placeholder="Full Name">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label class="font-weight-bold" for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email Address">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label class="font-weight-bold" for="subject">Subject</label>
                            <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter Subject">
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <label class="font-weight-bold" for="message">Message</label>
                            <textarea name="message" id="message" cols="30" rows="5" class="form-control" placeholder="Say hello to us"></textarea>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-12">
                            <input type="submit" value="Send Message" class="btn btn-primary py-2 px-4 rounded-0">
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-4">
                <div class="p-4 mb-3 bg-white">
                    <h3 class="h6 text-black mb-3 text-uppercase">Contact Info</h3>
                    <p class="mb-0 font-weight-bold">Address</p>
                    <p class="mb-4">10Th Floor Wisma Xpert, Jln Tun Razak, Kuala Lumpur</p>
                    <p class="mb-0 font-weight-bold">Phone</p>
                    <p class="mb-4"><a href="#">+60 32163-7960</a></p>
                    <p class="mb-0 font-weight-bold">Email Address</p>
                    <p class="mb-0"><a href="#">auctionxpert@gmail.com</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="site-section bg-light">
    <div class="container">
      <div class="row mb-5 justify-content-center">
        <div class="col-md-7">
          <div class="site-section-title text-center">
            <h2>Our Agents</h2>
            <p>
            Explore a world of real estate expertise with our dedicated agents. Each professional in our team is committed to providing top-notch service and guidance, ensuring your real estate journey is seamless and successful. Meet our agents below and discover the difference experience makes in property transactions.
</p>
          </div>
        </div>
      </div>
      <div class="row">
          <div class="col-md-6 col-lg-4 mb-5 mb-lg-5">
            <div class="team-member">

              <img src="images/person_1.jpg" alt="Image" class="img-fluid rounded mb-4">

              <div class="text">

                <h2 class="mb-2 font-weight-light text-black h4">Muhd Shafiee</h2>
                <span class="d-block mb-3 text-white-opacity-05">Real Estate Agent</span>
                <p>
                Experience real estate excellence with Muhd Shafiee. With a wealth of industry knowledge, he guides you through seamless property transactions.                <p>
                  <a href="#" class="text-black p-2"><span class="icon-facebook"></span></a>
                  <a href="#" class="text-black p-2"><span class="icon-twitter"></span></a>
                  <a href="#" class="text-black p-2"><span class="icon-linkedin"></span></a>
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 mb-5 mb-lg-5">
            <div class="team-member">

              <img src="images/person_2.jpg" alt="Image" class="img-fluid rounded mb-4">

              <div class="text">

                <h2 class="mb-2 font-weight-light text-black h4">Trixie</h2>
                <span class="d-block mb-3 text-white-opacity-05">Real Estate Agent</span>
                <p>
                Partner with Trixie for a personalized and rewarding real estate journey. Whether you're buying or selling, Trixie's expertise ensures a smooth process.                <p>
                  <a href="#" class="text-black p-2"><span class="icon-facebook"></span></a>
                  <a href="#" class="text-black p-2"><span class="icon-twitter"></span></a>
                  <a href="#" class="text-black p-2"><span class="icon-linkedin"></span></a>
                </p>
              </div>

            </div>
          </div>

          <div class="col-md-6 col-lg-4 mb-5 mb-lg-5">
            <div class="team-member">

              <img src="images/person_3.jpg" alt="Image" class="img-fluid rounded mb-4">

              <div class="text">

                <h2 class="mb-2 font-weight-light text-black h4">Adam Rayyan</h2>
                <span class="d-block mb-3 text-white-opacity-05">Real Estate Agent</span>
                <p>
                Trust Ahmad Kamal for a seamless real estate experience. His dedication to client satisfaction and market knowledge make him your ideal partner.                <p>
                  <a href="#" class="text-black p-2"><span class="icon-facebook"></span></a>
                  <a href="#" class="text-black p-2"><span class="icon-twitter"></span></a>
                  <a href="#" class="text-black p-2"><span class="icon-linkedin"></span></a>
                </p>
              </div>

            </div>
          </div>

          

        </div>
    </div>
<?php require "includes/footer.php"; ?>
    