    <?php
    session_start();
    include('vendor/inc/config.php');

        // Check if the vehicle ID is provided in the URL
        $startLocation = $_SESSION["startLocation"];
        $pickupDate = $_SESSION["pickupDate"];
        $endLocation = $_SESSION["endLocation"];
        $dropOffDate = $_SESSION["dropOffDate"];

    if (isset($_GET["vehicle_id"])) {
        // Get the vehicle ID from the URL
        $vehicleId = $_GET["vehicle_id"];

        // Check for a successful database connection
        if ($mysqli->connect_error) {
            die("Database connection failed: " . $mysqli->connect_error);
        }

        // Query the database to retrieve the vehicle details
        $sql = "SELECT * FROM vehicles WHERE vehicle_id = '$vehicleId'";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            $carDetails = $result->fetch_assoc();
        } else {
            echo "Vehicle details not found.";
        }

    } else {
        // Handle the case when no vehicle ID is provided
        echo "Vehicle ID not provided.";

        // Close the database connection
        $mysqli->close();
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <?php  include('vendor/inc/head.php');  ?>

    <body>
        <?php include('vendor/inc/nav.php');  ?>

    <section class= "book-detail1">
    <div class = "book-details">
        <div class ="booking-ways">
            <a href= "#">Outstation Round Trip <?php  echo $dropOffDate; ?></a>
            <a href= "#">Outstation One Way</a>
            <a href= "#">Local Hourly Basis</a>
            <a href= "#">Cabs to Airport</a>
            <a href= "#">Cabs from Airport</a>
        </div>
        <div class="change-booking">
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" class="hero-form1">

            <div class="input-wrapper">
            <label for="input-1" class="input-label1">Pickup Location</label>

            <input type="text" name="start_loc" id="input-1" class="input-field" value="<?php echo $startLocation ?>"
                placeholder="Anywhere" readonly>
            </div>
            <div class="input-wrapper">
            <label for="input-1" class="input-label1">Drop Location</label>

            <input type="text" name="end_loc" id="input-4" class="input-field"
                placeholder="Anywhere" value="<?php echo $endLocation ?>" readonly>
            </div>

            <div class="input-wrapper">
            <label for="input-2" class="input-label1">Start Date</label>

            <input type="datetime-local" name="start_date" value= "<?php echo $pickupDate ?>" id="input-2" class="input-field" readonly>
            </div>

            <div class="input-wrapper">
            <label for="input-3" class="input-label1">End Date</label>

            <input type="datetime-local" name="End_date" id="input-3" class="input-field" value= "<?php echo $dropOffDate ?>" readonly>
            </div>
            </form>
        </div>
    </div>

    </section>
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
            $sql = "SELECT * FROM vehicles where vehicle_id = '$vehicleId'";

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
                                        <ion-icon name="flash-outline"></ion-icon>
                                        <span class="card-item-text">' . $car["model"] . '</span>
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
                                    <button class="btn" onclick="window.location.href=\'confirm-book.php?vehicle_id=' . $car["vehicle_id"] . '\'">Rent Now</button>
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


    <?php  include('vendor/inc/footer.php');  ?>

        
    <script src="./vendor/js/script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    </body>
    </html>