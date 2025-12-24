<?php

$conn = mysqli_connect("localhost","asad_etippers","cNjxyIJaAE9U","etippers");
// $conn = mysqli_connect("localhost","root","","etippers");
?>

<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="images/favicon.png" type="">

  <title> KashKash </title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />

</head>

<body class="sub_page">

  <div class="hero_area">

    <div class="hero_bg_box">
      <div class="bg_img_box">
        <img src="images/hero-bg.png" alt="">
      </div>
    </div>

    <!-- header section strats -->
    <header class="header_section">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="index.html">
            <span>
              KashKash
            </span>
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""> </span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav  ">
              <li class="nav-item active">
                <a class="nav-link" href="index.html">Home <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="about.html"> About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="contact_us.html"> Contact Us</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="privacy_policy.html">Privacy Policy</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="terms_and_conditions.html">Terms And Conditions</a>
              </li>
              <!-- <li class="nav-item">
                <a class="nav-link" href="team.html">Team</a>
              </li> -->
              <li class="nav-item">
                <a class="nav-link" href="https://kashkash.net/admin"> <i class="fa fa-user" aria-hidden="true"></i> Login</a>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </header>
    <!-- end header section -->
  </div>


  <section class="my-5">
      <div class="container">
          <div class="row d-flex justify-content-center align-items-center">
              <div class="col-6">
                  <div class="card">
                      <div class="card-header">
                          Delete Account
                      </div>
                      <div class="card-body">
                          <form action="account-delete.php" method="POST" autocomplete="foo">
                              <label for="email">Email</label>
                              <input type="text" class="form-control" name="email" id="email" autocomplete="none"placeholder="Enter your email">
                              <label for="password">Password</label>
                              <input type="password" class="form-control" name="password" id="password" autocomplete="foo"placeholder="Enter your password">
                              <button name="delete_account" class="btn btn-info mt-3">Submit Request</button>
                          </form>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </section>



  <?php
    function encrypt_password($password) {
      $key = 'hdhxVNkGrbNc1o0L';
      $salt1 = hash('sha512', $key . $password);
      $salt2 = hash('sha512', $password . $key);
      $hashed_password = hash('sha512', $salt1 . $password . $salt2);
      return $hashed_password;
    }

    $conn = mysqli_connect("localhost","asad_etippers","cNjxyIJaAE9U","etippers");
    // $conn = mysqli_connect("localhost", "root", "", "etippers");

    if (isset($_POST['delete_account'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = "SELECT * FROM users WHERE Email = '$email'";
        $run = mysqli_query($conn, $query);

        if (mysqli_num_rows($run) > 0) {
            $row = mysqli_fetch_assoc($run);
            $id = $row['Id'];

            $hashed_password = encrypt_password($password);

            if ($hashed_password === $row['Password']) {
                $query = "UPDATE users SET delete_request = '1' WHERE Id='$id'";
                if (mysqli_query($conn, $query)) {
                    echo '<div class="d-flex justify-content-center align-items-center"><div class="alert alert-success col-8" role="alert">Your account deletion request has been initiated and our support team will update you.</div></div>';
                }
            } else {
                echo '<div class="d-flex justify-content-center align-items-center"><div class="alert alert-danger col-6" role="alert">Email or Password is incorrect.</div></div>';
            }
        } else {
            echo '<div class="d-flex justify-content-center align-items-center"><div class="alert alert-danger col-6" role="alert">Email or Password is incorrect.</div></div>';
        }
    }
?>








  

 <!-- info section -->
 <section class="info_section layout_padding2">
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-lg-4 info_col">
          <div class="info_contact">
            <h4>
              Address
            </h4>
            <div class="contact_link_box">
              <a href="">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <span>
                  Haiti: 20 Rue Camille Leon, P-A-P, Haiti
                </span>
              </a>
              <a href="">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>
                  509-373-54385
                </span>
              </a>
              <a href="">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <span>
                  customerservice@kash-kashtransfer.com
                </span>
              </a>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-5 info_col">
          <div class="info_detail">
            <h4>
              Info
            </h4>
            <p>
              At Kash-Kash Money Transfer LLC (Kash-Kash) we're dedicated to providing fast, secure, and 
              reliable money transmission services to individuals and businesses worldwide.
            </p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 mx-auto info_col">
          <div class="info_link_box">
            <h4>
              Links
            </h4>
            <div class="info_links">
              <a class="active" href="index.html">
                Home
              </a>
              <a class="" href="about.html">
                About
              </a>
              <a class="" href="contact_us.html">
                Contact Us
              </a>
              <a class="" href="privacy_policy.html">
                Privacy Policy
              </a>
              <a class="" href="terms_and_conditions.html">
                Terms And Conditions
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end info section -->

  <!-- footer section -->
  <section class="footer_section">
    <div class="container">
      <p>
        &copy; <span id="displayYear"></span> All Rights Reserved By
        <a href="#">SilentSOl</a>
      </p>
    </div>
  </section>
  <!-- footer section -->

  <!-- jQery -->
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <!-- popper js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <!-- bootstrap js -->
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <!-- owl slider -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <!-- custom js -->
  <script type="text/javascript" src="js/custom.js"></script>
  <!-- Google Map -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap"></script>
  <!-- End Google Map -->






</body>

</html>
