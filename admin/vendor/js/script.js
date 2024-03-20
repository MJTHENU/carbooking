

$(document).ready(function () {
    // Listen for the change event on the vehicle_id select element
    $('#vehicle_id').change(function () {
        // Get the selected vehicle_id
        var selectedVehicleId = $(this).val();

        // Make an AJAX request to fetch the corresponding vehicle_name
        $.ajax({
            type: 'POST',
            url: 'get_vehicle_name.php', // Replace with the actual URL to fetch vehicle name
            data: { vehicle_id: selectedVehicleId },
            success: function (response) {
                // Update the vehicle_name input field with the fetched value
                $('#vehicle_name').val(response);
            },
            error: function () {
                // Handle errors if needed
                console.log('Error fetching vehicle name');
            }
        });
    });
});


$(document).ready(function () {
    // Listen for the change event on the vehicle_id select element
    $('#vehicle_id').change(function () {
        // Get the selected vehicle_id
        var selectedVehicleId = $(this).val();

        // Make an AJAX request to fetch the corresponding model
        $.ajax({
            type: 'POST',
            url: 'get_vehicle_model.php', // Replace with the actual URL to fetch the model
            data: { vehicle_id: selectedVehicleId },
            success: function (response) {
                // Update the model input field with the fetched value
                $('#model').val(response);
            },
            error: function () {
                // Handle errors if needed
                console.log('Error fetching vehicle model');
            }
        });
    });
});

$(document).ready(function () {
    // Listen for the change event on the vehicle_id select element
    $('#tariff_id').change(function () {
        // Get the selected vehicle_id
        var selectedTariffId = $(this).val();

        // Make an AJAX request to fetch the corresponding vehicle_name
        $.ajax({
            type: 'POST',
            url: 'get_tariff_name.php', // Replace with the actual URL to fetch vehicle name
            data: { tariff_id: selectedTariffId },
            success: function (response) {
                // Update the vehicle_name input field with the fetched value
                $('#tariff_name').val(response);
            },
            error: function () {
                // Handle errors if needed
                console.log('Error fetching tariff name');
            }
        });
    });
});

$(document).ready(function () {
    // Listen for the change event on the vehicle_id select element
    $('#vehicle_name').change(function () {
        // Get the selected vehicle_id
        var selectedVehicleName = $(this).val();

        // Make an AJAX request to fetch the corresponding vehicle_name
        $.ajax({
            type: 'POST',
            url: 'get_tariff_id.php', // Replace with the actual URL to fetch vehicle name
            data: { vehicle_name: selectedVehicleName },
            success: function (response) {
                // Update the tariff_id input field with the fetched value
                $('#tariff_id').val(response);
            },
            error: function () {
                // Handle errors if needed
                console.log('Error fetching tariff id');
            }
        });
    });
});