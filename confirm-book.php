    <?php
    session_start();
    include('vendor/inc/config.php');

      // Check if the user is not logged in
      if (!isset($_SESSION['user_id'])) {
        // Check if the vehicle_id is set in the URL's query parameters
        if (isset($_GET['vehicle_id'])) {
            $vehicleId = $_GET['vehicle_id'];

            // Store the vehicle_id in the session
            $_SESSION['vehicle_id'] = $vehicleId;

            // Redirect the user to the login page
            header("Location: login.php?vehicle_id=$vehicleId");
            exit();
        } else {
            // If vehicle_id is not provided in the URL, handle it as needed
            // For example, you can redirect the user back to the previous page or display an error message.
        }
    }

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

        $_SESSION['last_fetched_vehicle_id'] = $vehicleId;
        
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
    <?php include('vendor/inc/head.php'); ?>
    <body>
        <?php include('vendor/inc/nav.php'); ?>

    <section class = "vehicle-tariff container">
        <div class="show-vehicle">
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
                        <button class="btn fav-btn" aria-label="Add to favourite list">View Details</button>
                        </div>
                </div>
            </div>
        </li>';
    }   

    ?>
        </div>
        
        <div class="show-tariff">
    <?php  
        // Query to select car data from your database
        $sql = "SELECT * FROM vehicle_tariff WHERE vehicle_id = '$vehicleId'";
        $result = $mysqli->query($sql);

        $id = 1;
        // Check if there are rows in the result
        if ($result->num_rows > 0) {
            // Open the table outside the loop
            echo '<table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Tariff_ID</th>
                            <th scope="col">Tariff_Name</th>
                            <th scope="col">Tariff_Type</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Min_Km</th>
                            <th scope="col">Driver_Charge</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>'; // Open the table body inside the loop

            while ($tariff = $result->fetch_assoc()) {
                // Get additional details using the tariff_id from the first query
                $tariffId = $tariff['tariff_id'];
                $sql1 = "SELECT * FROM tariff WHERE tariff_id = '$tariffId'";
                $result1 = $mysqli->query($sql1);

                if ($result1->num_rows > 0) {
                    // Assuming you have additional fields in the 'another_table'
                    $tariffDetails = $result1->fetch_assoc();

                    // Output the row with additional details
                    echo '<tr>
                            <th scope="row">' . $id . '</th>
                            <td>' . $tariffDetails["tariff_id"] . '</td>
                            <td>' . $tariffDetails["tariff_name"] . '</td>
                            <td>' . $tariffDetails["tariff_type"] . '</td>
                            <td>₹' . $tariffDetails["amount"] . '</td>
                            <td>' . $tariffDetails["min_km"] . '</td>
                            <td>' . $tariffDetails["driver_charge"] . '</td>
                            <td><a href="booking-form.php?tariff_id=' . $tariffId . '" class="choose-tariff-btn">Choose</a></td>
                        </tr>';
                    
                    // Increment the id for the next row
                    $id++;
                } else {
                    // Handle if no additional details are found
                    echo 'No additional details found for tariff_id: ' . $tariffId;
                }
            }
            // Close the table body and table
            echo '</tbody></table>';
            echo '<a href="booking-form.php?tariff_id=" class="choose-tariff-btn">Skip Tariff Selection</a>';
        } else {
            // Display a message if no rows are found
            echo 'No tariff details found.';
        }

        $mysqli->close();
    ?>
</div>


    </section>

    <?php include('vendor/inc/footer.php'); ?>


        <script src="./vendor/js/script.js"></script>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    </body>
    </html>
