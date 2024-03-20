<?php
session_start();
include('vendor/inc/config.php');

$errors = [];

if (!isset($_POST["submit"])) {
  unset($_SESSION["startLocation"]);
  unset($_SESSION["pickupDate"]);
  unset($_SESSION["endLocation"]);
  unset($_SESSION["dropOffDate"]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // Handle the form submission
    $startLocation = $_POST["start_loc"];
    $pickupDate = $_POST["start_date"];
    $endLocation = $_POST["end_loc"];
    $dropOffDate = $_POST["end_date"];

    $_SESSION["startLocation"] = $startLocation;
    $_SESSION["pickupDate"] = $pickupDate;
    $_SESSION["endLocation"] = $endLocation;
    $_SESSION["dropOffDate"] = $dropOffDate;   

    // Validate start location
    if (empty($startLocation)) {
        $errors['start_loc'] = 'Start Location is required.';
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $startLocation)) {
        $errors['start_loc'] = 'Start Location should only contain letters and spaces.';
    }
    if (empty($endLocation)) {
      $errors['end_loc'] = 'End Location is required.';
  } elseif (!preg_match("/^[a-zA-Z\s]+$/", $endLocation)) {
      $errors['end_loc'] = 'End Location should only contain letters and spaces.';
  }
  $currentTimestamp = time(); // Get the current timestamp

    // Validate pickup date
    if (empty($pickupDate)) {
        $errors['start_date'] = 'Start Date is required.';
    } elseif (strtotime($pickupDate) < $currentTimestamp) {
        $errors['start_date'] = 'Start Date should be a future or present date.';
    }

    // Validate drop-off date
    if (empty($dropOffDate)) {
        $errors['end_date'] = 'End Date is required.';
    } elseif (strtotime($dropOffDate) < $currentTimestamp) {
        $errors['end_date'] = 'End Date should be a future or present date.';
    }

    // Validate other form fields as needed

    if (empty($errors)) {
        // Check for an available vehicle in the vehicles table that is not booked on the specified dates
        $sql = "SELECT vehicle_id FROM vehicles 
                WHERE vehicle_id NOT IN (SELECT vehicle_id FROM booking 
                                         WHERE (start_date <= '$pickupDate' AND end_date >= '$pickupDate') 
                                         OR (start_date <= '$dropOffDate' AND end_date >= '$dropOffDate'))";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            // If available vehicles are found, use the first one
            $row = $result->fetch_assoc();
            $vehicleId = $row["vehicle_id"];

            // Redirect to the car-booking.php page with the selected vehicle ID
            header("Location: carbook.php?vehicle_id=$vehicleId");
            exit();
        } else {
            // Handle the case where no available vehicles are found
            // You can show a message or redirect to a different page
            echo "No available vehicles for the selected dates.";
        }
    } 
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include('vendor/inc/head.php'); ?>
<style>
         .error {color: #FF0000;}
    </style>
<body>
<?php include('vendor/inc/nav.php'); ?>
  <main>
    <article>

      <!-- 
        - #HERO
      -->

      <section class="section hero" id="home">
        <div class="container">

          <div class="hero-content">
            <h2 class="h1 hero-title">The easy way to takeover a lease</h2>

            <p class="hero-text">
            Lives in Tenkasi, Tamil Nadu, India.
            </p>
          </div>

          <div class="hero-banner"></div>

          <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="hero-form">

            <div class="input-wrapper">
              <label for="input-1" class="input-label">Pickup Location</label>

              <input type="text" name="start_loc" id="input-1" class="input-field"
                placeholder="Anywhere">
                <?php if (isset($errors['start_loc'])) echo "<span class='error'>* " . $errors['start_loc'] . "</span>"; ?>
                               
            </div>
            <div class="input-wrapper">
              <label for="input-1" class="input-label">Drop Location</label>

              <input type="text" name="end_loc" id="input-4" class="input-field"
                placeholder="Anywhere" >
                <?php if (isset($errors['end_loc'])) echo "<span class='error'>* " . $errors['end_loc'] . "</span>"; ?>
               
            </div>

            <div class="input-wrapper">
              <label for="input-2" class="input-label">Start Date</label>

              <input type="datetime-local" name="start_date" id="input-2" class="input-field">
              <?php if (isset($errors['start_date'])) echo "<span class='error'>* " . $errors['start_date'] . "</span>"; ?>
               
            </div>

            <div class="input-wrapper">
              <label for="input-3" class="input-label">End Date</label>

              <input type="datetime-local" name="end_date" id="input-3" class="input-field">
              <?php if (isset($errors['end_date'])) echo "<span class='error'>* " . $errors['end_date'] . "</span>"; ?>
               
            </div>

            <input type="submit" class="btn" id="btn1" value ="Search" name="submit">

          </form>

        </div>
      </section>

      <!-- 
        - #FEATURED CAR
      -->
    
  <section class="section featured-car" id="featured-car">
    <div class="container">
        <div class="title-wrapper">
            <h2 class="h2 section-title">Featured cars</h2>
            <a href="#" class="featured-car-link">
                <span>View more</span>
                <ion-icon name="arrow-forward-outline"></ion-icon>
            </a>
        </div>
        <ul class="featured-car-list">
            <?php

            // SQL query to select car data from your database
            $sql = "SELECT * FROM vehicles";
            $result = $mysqli->query($sql);

            while ($car = $result->fetch_assoc()) {
                echo '<li>
                    <div class="featured-car-card">
                        <figure class="card-banner">
                            <img src="' . $car["vehicle_img"] . '" alt="' . $car["vehicle_name"] . '" loading="lazy" width="440" height="300" class="w-100">
                        </figure>
                        <div class="card-content">
                            <div class="card-title-wrapper">
                                <h3 class="h3 card-title">
                                    <a href="#">' . $car["vehicle_name"] . '</a>
                                </h3>
                                <data class="year" value="' . $car["years"] . '">' . $car["years"] . '</data>
                            </div>
                            <ul class="card-list">
                                <li class="card-list-item">
                                    <ion-icon name="people-outline"></ion-icon>
                                    <span class="card-item-text">' . $car["seat"] . '</span>
                                </li>
                                <li class="card-list-item">
                                <ion-icon name="bag-check"></ion-icon></ion-icon>
                                    <span class="card-item-text">' . $car["bag"] . '</span>
                                </li>
                                <li class="card-list-item">
                                    <ion-icon name="speedometer-outline"></ion-icon>
                                    <span class="card-item-text">' . $car["vehicle_id"] . '</span>
                                </li>
                                <li class="card-list-item">
                                    <ion-icon name="hardware-chip-outline"></ion-icon>
                                    <span class="card-item-text">' . $car["company"] . '</span>
                                </li>
                            </ul>
                            <div class="card-price-wrapper">
                                <p class="card-price">
                                    <strong>' . $car["ac"] . '</strong> / A/c
                                </p>
                                <button class="btn fav-btn" aria-label="Add to favourite list">
                                    <ion-icon name="heart-outline"></ion-icon>
                                </button>
                                <button><a href="confirm-book.php?vehicle_id=' . $car["vehicle_id"] . '" class="btn rent-now-link">Book now</a></button>
                            </div>
                        </div>
                    </div>
                </li>';
            }

            // Close the database connection
            $mysqli->close();
            ?>
        </ul>
    </div>
</section>

<section class = "company-details">
   <div class = "company1">
   <ion-icon name="heart-half-outline"></ion-icon>
   <p>5432</p>
   <p>Happy Customers</p>
   </div>
   <div class = "company1">
   <ion-icon name="car-outline"></ion-icon>
   <p>432</p>
   <p>Total Car Count</p>
   </div>
   <div class = "company1">
   <ion-icon name="flag-outline"></ion-icon>
   <p>54,322,343</p>
   <p>Total Km/Mil</p>
   </div>
   <div class = "company1">
   <ion-icon name="mail-outline"></ion-icon>  
   <p>2345</p>
   <p>Call Center Solution</p>
   </div>
</section>

      <!-- 
        - #GET START
      -->

      <section class="section get-start">
        <div class="container">

          <h2 class="h2 section-title">Get started with 4 simple steps</h2>

          <ul class="get-start-list">

            <li>
              <div class="get-start-card">

                <div class="card-icon icon-1">
                  <ion-icon name="person-add-outline"></ion-icon>
                </div>

                <h3 class="card-title">Create a profile</h3>

                <p class="card-text">
                  If you are going to use a passage of Lorem Ipsum, you need to be sure.
                </p>

                <a href="#" class="card-link">Get started</a>

              </div>
            </li>

            <li>
              <div class="get-start-card">

                <div class="card-icon icon-2">
                  <ion-icon name="car-outline"></ion-icon>
                </div>

                <h3 class="card-title">Tell us what car you want</h3>

                <p class="card-text">
                  Various versions have evolved over the years, sometimes by accident, sometimes on purpose
                </p>

              </div>
            </li>

            <li>
              <div class="get-start-card">

                <div class="card-icon icon-3">
                  <ion-icon name="person-outline"></ion-icon>
                </div>

                <h3 class="card-title">Match with seller</h3>

                <p class="card-text">
                  It to make a type specimen book. It has survived not only five centuries, but also the leap into
                  electronic
                </p>

              </div>
            </li>

            <li>
              <div class="get-start-card">

                <div class="card-icon icon-4">
                  <ion-icon name="card-outline"></ion-icon>
                </div>

                <h3 class="card-title">Make a deal</h3>

                <p class="card-text">
                  There are many variations of passages of Lorem available, but the majority have suffered alteration
                </p>

              </div>
            </li>

          </ul>

        </div>
      </section>





      <!-- 
        - #BLOG
      -->

      <section class="section blog" id="blog">
        <div class="container">

          <h2 class="h2 section-title">Our Blog</h2>

          <ul class="blog-list has-scrollbar">

            <li>
              <div class="blog-card">

                <figure class="card-banner">

                  <a href="#">
                    <img src="./vendor/images/blog-1.jpg" alt="Opening of new offices of the company" loading="lazy"
                      class="w-100">
                  </a>

                  <a href="#" class="btn card-badge">Company</a>

                </figure>

                <div class="card-content">

                  <h3 class="h3 card-title">
                    <a href="#">Opening of new offices of the company</a>
                  </h3>

                  <div class="card-meta">

                    <div class="publish-date">
                      <ion-icon name="time-outline"></ion-icon>

                      <time datetime="2022-01-14">January 14, 2022</time>
                    </div>

                    <div class="comments">
                      <ion-icon name="chatbubble-ellipses-outline"></ion-icon>

                      <data value="114">114</data>
                    </div>

                  </div>

                </div>

              </div>
            </li>

            <li>
              <div class="blog-card">

                <figure class="card-banner">

                  <a href="#">
                    <img src="./vendor/images/blog-2.jpg" alt="What cars are most vulnerable" loading="lazy"
                      class="w-100">
                  </a>

                  <a href="#" class="btn card-badge">Repair</a>

                </figure>

                <div class="card-content">

                  <h3 class="h3 card-title">
                    <a href="#">What cars are most vulnerable</a>
                  </h3>

                  <div class="card-meta">

                    <div class="publish-date">
                      <ion-icon name="time-outline"></ion-icon>

                      <time datetime="2022-01-14">January 14, 2022</time>
                    </div>

                    <div class="comments">
                      <ion-icon name="chatbubble-ellipses-outline"></ion-icon>

                      <data value="114">114</data>
                    </div>

                  </div>

                </div>

              </div>
            </li>

            <li>
              <div class="blog-card">

                <figure class="card-banner">

                  <a href="#">
                    <img src="./vendor/images/blog-3.jpg" alt="Statistics showed which average age" loading="lazy"
                      class="w-100">
                  </a>

                  <a href="#" class="btn card-badge">Cars</a>

                </figure>

                <div class="card-content">

                  <h3 class="h3 card-title">
                    <a href="#">Statistics showed which average age</a>
                  </h3>

                  <div class="card-meta">

                    <div class="publish-date">
                      <ion-icon name="time-outline"></ion-icon>

                      <time datetime="2022-01-14">January 14, 2022</time>
                    </div>

                    <div class="comments">
                      <ion-icon name="chatbubble-ellipses-outline"></ion-icon>

                      <data value="114">114</data>
                    </div>

                  </div>

                </div>

              </div>
            </li>

            <li>
              <div class="blog-card">

                <figure class="card-banner">

                  <a href="#">
                    <img src="./vendor/images/blog-4.jpg" alt="What´s required when renting a car?" loading="lazy"
                      class="w-100">
                  </a>

                  <a href="#" class="btn card-badge">Cars</a>

                </figure>

                <div class="card-content">

                  <h3 class="h3 card-title">
                    <a href="#">What´s required when renting a car?</a>
                  </h3>

                  <div class="card-meta">

                    <div class="publish-date">
                      <ion-icon name="time-outline"></ion-icon>

                      <time datetime="2022-01-14">January 14, 2022</time>
                    </div>

                    <div class="comments">
                      <ion-icon name="chatbubble-ellipses-outline"></ion-icon>

                      <data value="114">114</data>
                    </div>

                  </div>

                </div>

              </div>
            </li>

            <li>
              <div class="blog-card">

                <figure class="card-banner">

                  <a href="#">
                    <img src="./vendor/images/blog-5.jpg" alt="New rules for handling our cars" loading="lazy"
                      class="w-100">
                  </a>

                  <a href="#" class="btn card-badge">Company</a>

                </figure>

                <div class="card-content">

                  <h3 class="h3 card-title">
                    <a href="#">New rules for handling our cars</a>
                  </h3>

                  <div class="card-meta">

                    <div class="publish-date">
                      <ion-icon name="time-outline"></ion-icon>

                      <time datetime="2022-01-14">January 14, 2022</time>
                    </div>

                    <div class="comments">
                      <ion-icon name="chatbubble-ellipses-outline"></ion-icon>

                      <data value="114">114</data>
                    </div>

                  </div>

                </div>

              </div>
            </li>

          </ul>

        </div>
      </section>

    </article>
  </main>





  <!-- 
    - #FOOTER
  -->
<?php  include('vendor/inc/footer.php');  ?>
  





  <!-- 
    - custom js link
  -->
  <script src="./vendor/js/script.js"></script>

  <!-- 
    - ionicon link
  -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>

</html>