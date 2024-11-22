document.addEventListener('DOMContentLoaded', function () {
    let cartToggle = document.getElementById('cart-toggle');
    let cartContainer = document.getElementById('cart-container');

    cartToggle.addEventListener('click', function () {
        // Toggle the visibility of the cart container
        if (cartContainer.style.right === '0px') {
            cartContainer.style.right = '-100%';
            cartToggle.style.display = 'block'; // Show the cart button
        } else {
            cartContainer.style.right = '0px';
            cartToggle.style.display = 'none'; // Hide the cart button
        }
    });

    // Add event listeners to the decrease buttons
    let decreaseButtons = document.querySelectorAll('.decrease-button');
    decreaseButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            updateQuantity(this, 'decrease');
        });
    });

    // Add event listeners to the increase buttons
    let increaseButtons = document.querySelectorAll('.increase-button');
    increaseButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            updateQuantity(this, 'increase');
        });
    });

    // Add event listeners for remove buttons
    let removeButtons = document.querySelectorAll('.remove-button');
    removeButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            removeCartItem(this);
        });
    });

    function updateQuantity(button, action) {
        // Get the product ID
        let productId = button.getAttribute('data-product-id');
    
        // Get the quantity element for the current product
        let quantityElement = document.querySelector('.quantity-text[data-product-id="' + productId + '"]');
    
        if (!quantityElement) {
            console.error('Quantity element not found for product ID:', productId);
            return;
        }
    
        // Update the quantity in the browser
        let currentQuantity = parseInt(quantityElement.textContent);
        if (action === 'increase') {
            quantityElement.textContent = currentQuantity + 1;
        } else if (action === 'decrease' && currentQuantity > 1) {
            quantityElement.textContent = currentQuantity - 1;
        }

        // Update the subtotal in the UI
        let subtotalElement = document.querySelector('.cart-item-subtotal[data-product-id="' + productId + '"]');
        let productPrice = parseFloat(subtotalElement.getAttribute('data-product-price'));
        let newSubtotal = productPrice * parseInt(quantityElement.textContent);
        subtotalElement.textContent = '₱' + newSubtotal.toFixed(2); // Add currency symbol
    
        // Update the quantity on the server using AJAX
        updateCartQuantity(productId, quantityElement.textContent);
    
        // Update the total without refreshing the UI
        updateTotal();
    }
    
    function updateTotal() {
        // Calculate the new total based on the updated quantities
        let total = 0;
        let subtotalElements = document.querySelectorAll('.cart-item-subtotal');
        subtotalElements.forEach(function (subtotalElement) {
            total += parseFloat(subtotalElement.textContent.replace('₱', '')); // Remove currency symbol before parsing
        });
    
        // Update the total in the UI
        let totalElement = document.querySelector('.cart-total span:last-child');
        if (totalElement) {
            totalElement.textContent = '₱' + total.toFixed(2); // Add currency symbol
        }
    }
    function updateCartQuantity(productId, quantity) {
        // Perform the update using AJAX
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // Log the response to the console
                    console.log('Update Cart Response:', xhr.responseText);
    
                    // Parse the JSON response
                    let response = JSON.parse(xhr.responseText);
    
                    if (response.status === 'success') {
                        // Log the quantity
                        console.log('Quantity updated successfully. New quantity:', response.new_quantity);
    
                        if (response.new_subtotal !== undefined) {
                            // Update the subtotal in the UI
                            let subtotalElement = document.querySelector('.cart-item-subtotal[data-product-id="' + productId + '"]');
                            // Use parseFloat to handle decimal values
                            // Update the subtotal with the peso sign and formatted with commas
                            subtotalElement.textContent = '₱' + parseFloat(response.new_subtotal).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, '');
    
                            console.log('Quantity updated successfully. New subtotal:', response.new_subtotal);
                        } else {
                            console.error('Error updating product quantity. New subtotal is undefined.');
                        }
                    } else {
                        console.error('Error updating product quantity. Status:', xhr.status);
                    }
                }
            }
        };
        xhr.open("GET", "/healhub/shop.php?update_cart_quantity=" + productId + "&quantity=" + quantity, true);
        xhr.send();
    }
    
    

    function removeCartItem(button) {
        // Get the product ID from the remove button
        let productId = button.getAttribute('data-product-id');
    
        // Get the cart item element
        let cartItem = document.querySelector('.cart-item[data-product-id="' + productId + '"]');
    
        // Check if the cart item element exists
        if (cartItem) {
            // Create a new FormData object
            let formData = new FormData();
            formData.append('remove_cart_item', productId);
    
            // Create a new XMLHttpRequest object
            let xhr = new XMLHttpRequest();
    
            // Configure it: POST-method, URL, asynchronous
            xhr.open('POST', '/healhub/shop.php', true);
    
            // Send the request with the FormData as the body
            xhr.send(formData);
    
            // This function will be called after the response is received
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4) {
                    // ... (existing code)
    
                    if (xhr.status == 200) {
                        // Parse the JSON response
                        let response = JSON.parse(xhr.responseText);
    
                        // Check if the response indicates success
                        if (response.status === 'success') {
                            // Remove the UI element
                            cartItem.remove();
    
                            // Update the total
                            updateTotal();
    
                            console.log('Remove Cart Item Response:', xhr.responseText);
                        } else {
                            // Log the error message
                            console.log('Error removing product from the cart. Message:', response.message);
                        }
                    } else {
                        // Log an error if the status is not 200
                        console.error('Error removing product from the cart. Status:', xhr.status);
                    }
                }
            };
        
        } else {
            // Log an error if the cart item element is not found
            console.error('Error finding cart item in the UI. Product ID:', productId);
        }
    }
    paypal.Buttons({
        createOrder: function (data, actions) {
            // Calculate the total here
            let total = calculateTotal(); // You need to implement the calculateTotal function
    
            // Set up the transaction
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: total.toFixed(2), // Assuming you have a variable 'total' representing the cart total
                        currency_code: 'PHP'
                    }
                }]
            });
        },
       onApprove: function (data, actions) {
    // Capture the funds from the transaction
    return actions.order.capture().then(function (details) {
        // Call your server to save the transaction
        // This is where you can update your database with the order details
        console.log(details);

        // Add code to clear the cart after successful purchase
        clearCart();

        // Reload the page to show the updated cart
        location.reload();
    });
}
    }).render('#paypal-button-container');
    
    // Function to clear the cart
    function clearCart() {
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    const parsedResponse = JSON.parse(xhr.responseText);

                    if (parsedResponse.status === 'success') {
                        console.log('Cart data moved to orders successfully.');
                        updateTotal();
                    } else {
                        console.error('Error moving cart data to orders. Status:', xhr.status);
                    }
                } catch (error) {
                    console.error('Error parsing JSON response:', error);
                }
            }
        }
    };
    xhr.open("GET", "/healhub/shop.php?move_to_orders=true", true);
    xhr.send();
}

  
    
    
    function calculateTotal() {
        // Implement the logic to calculate the total based on the cart items
        let total = 0;
        let subtotalElements = document.querySelectorAll('.cart-item-subtotal');
        subtotalElements.forEach(function (subtotalElement) {
            // Remove the currency symbol '₱' and commas before parsing
            let subtotalValue = parseFloat(subtotalElement.textContent.replace(/₱|,/g, ''));
            if (!isNaN(subtotalValue)) {
                total += subtotalValue;
            }
        });
        return total;
    }
});    
