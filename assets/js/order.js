document.addEventListener('DOMContentLoaded', function () {
    // Get the track order button by its ID
    let trackOrderButton = document.getElementById('track-order-button');

    // Check if the button exists on the page
    if (trackOrderButton) {
        // Add a click event listener to the track order button
        trackOrderButton.addEventListener('click', function () {
            // Call a function to handle order tracking
            trackOrder();
        });
    }

    // Function to handle order tracking
    function trackOrder() {
        // Perform any necessary logic for tracking orders
        // ...

        // Example: Log a message to the console
        console.log('Order tracked successfully!');

        // You can also make AJAX requests to update the server, etc.
    }
});