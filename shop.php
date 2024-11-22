<?php
session_start();
include('hms/admin/include/config.php');
// Initialize user ID
$userId = isset($_SESSION['id']) ? $_SESSION['id'] : null;

// Check if the user is logged in
if ($userId) {
    // User is logged in
    // Check if there is a stored cart in the session
    if (!isset($_SESSION['cart'])) {
        // If not, check if there is a stored cart in the database
        $cart_query = mysqli_query($con, "SELECT cart_data FROM users WHERE id='$userId'");
        $user = mysqli_fetch_assoc($cart_query);

        if ($user && isset($user['cart_data'])) {
            // If a cart is found in the database, unserialize and update the session
            $_SESSION['cart'] = unserialize($user['cart_data']);
        } else {
            // If no cart is found, initialize an empty cart in the session
            $_SESSION['cart'] = array();
        }
    }

    // Add to Cart Function
    if (isset($_POST['add_to_cart'])) {
        $product_id = $_POST['product_id'];
        $quantity = isset($_POST['quantity']) ? max(1, intval($_POST['quantity'])) : 1; // Ensure quantity is at least 1

        // Check if the product is already in the cart
        if (!isset($_SESSION['cart'][$product_id])) {
            // If not, add it to the cart with the selected quantity
            $_SESSION['cart'][$product_id] = $quantity;
        } else {
            // If yes, update the quantity
            $_SESSION['cart'][$product_id] += $quantity;
        }

        // Update the database with the current cart data
        $cart_data = serialize($_SESSION['cart']);
        $update_query = mysqli_query($con, "UPDATE users SET cart_data='$cart_data' WHERE id='$userId'");

        // Redirect to the same page after processing the form
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit();
    }

    // Rest of your existing code for handling other cart actions...

} else {
    // User is not logged in
    // Continue with your existing code for handling cart actions without storing it in the database
}


if (isset($_GET['update_cart_quantity'])) {
    $productId = $_GET['update_cart_quantity'];
    $newQuantity = max(1, intval($_GET['quantity'])); // Ensure quantity is at least 1

    // Update the cart session
    $_SESSION['cart'][$productId] = $newQuantity;

    // Calculate the new subtotal
    $product_query = mysqli_query($con, "SELECT * FROM products WHERE id='$productId'");
    $product = mysqli_fetch_assoc($product_query);
    $newSubtotal = $newQuantity * $product['price'];

    // Send JSON response with new subtotal
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'new_quantity' => $newQuantity, 'new_subtotal' => $newSubtotal]);
    exit();
}

// Handle remove cart item request
if (isset($_POST['remove_cart_item'])) {
    $productId = $_POST['remove_cart_item'];

    // Check if the product is in the cart
    if (isset($_SESSION['cart'][$productId])) {
        // Remove the product from the cart
        unset($_SESSION['cart'][$productId]);

        // Check if the cart is empty
        if (empty($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        // Send a JSON response indicating success
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'cart' => $_SESSION['cart']]);
        exit();
    } else {
        // Send a JSON response indicating that the product was not found in the cart
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Product not found in the cart']);
        exit();
    }
}
if (isset($_GET['move_to_orders'])) {
    // Call a function to handle moving cart data to orders
    moveCartToOrders($con, $userId);
    exit(); // Terminate the script after handling the request
}

// Function to move cart data to orders
function moveCartToOrders($con, $userId) {
    // Fetch the cart data from the session
    $cartData = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

    // Iterate through the cart data and insert into the "orders" table
    foreach ($cartData as $productId => $quantity) {
        $productQuery = mysqli_query($con, "SELECT * FROM products WHERE id='$productId'");
        $product = mysqli_fetch_assoc($productQuery);

        if ($product) {
            $totalPrice = $quantity * $product['price'];

            // Insert order details into the "orders" table
            $insertOrderQuery = mysqli_query($con, "INSERT INTO orders (user_id, product_id, quantity, total_price) VALUES ('$userId', '$productId', '$quantity', '$totalPrice')");
            
            if (!$insertOrderQuery) {
                // Handle the error as needed
                $response = [
                    'status' => 'error',
                    'message' => 'Error inserting order details.',
                ];
                echo json_encode($response);
                return;
            }
        }
    }

    // Clear the cart session
    $_SESSION['cart'] = array();

    // Set the cart_data in the "users" table to an empty serialized array
    $updateCartDataQuery = mysqli_query($con, "UPDATE users SET cart_data='" . serialize(array()) . "' WHERE id='$userId'");

    if (!$updateCartDataQuery) {
        // Handle the error as needed
        $response = [
            'status' => 'error',
            'message' => 'Error updating cart data.',
        ];
        echo json_encode($response);
        return;
    }

    // Send a JSON response indicating success
    $response = [
        'status' => 'success',
        'message' => 'Cart data moved to orders successfully.',
        'userId' => $userId,
    ];

    // Send the JSON response
    echo json_encode($response);
}

if (isset($_GET['clear_cart'])) {
    // Clear the cart session
    $_SESSION['cart'] = array();

    // Clear the cart on the server
    clearCartOnServer($con, $userId);
    exit(); // Terminate the script after sending the JSON response
}


// Fetch and display products
$sql = mysqli_query($con, "SELECT * FROM products");
// Call the function with user ID provided

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Shop | Products</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealHub</title>

    <link rel="icon" href="assets/images/logo.png" type="image/x-icon" />

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- swiper css link  -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

    <!-- custom css file link  -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/shop.css">
    <link rel="stylesheet" href="assets/css/new.css">
    <!-- Add this link for the modified styles -->
    <link rel="stylesheet" href="assets/css/cart.css">
</head>

<body>

    <header class="header">
        <img src="assets/images/logo.png" class="logo" alt="logo" width="110" height="60">
        <nav class="navbar">
            <a href="/healhub/index.php#home">home</a>
            <a href="/healhub/index.php#about">about</a>
            <a href="/healhub/index.php#services">services</a>
            <a href="/healhub/index.php#team">team</a>
            <a href="/healhub/index.php#plan">plan</a>
            <a href="/healhub/shop.php">Shop</a>
        </nav>

        <?php
        if (isset($_SESSION['id'])) {
            // If user is logged in, show user profile options
            echo '
            <div class="current-user">
            <img src="hms/patient/assets/images/images.png" class="user-image" alt="user-image">
                <div class="username">';

            $query = mysqli_query($con, "select fullName from users where id='" . $_SESSION['id'] . "'");
            while ($row = mysqli_fetch_array($query)) {
                echo $row['fullName'];
            }

            echo '
                    <i class="ti-angle-down"></i>
                </div>
                <ul class="dropdown-menu dropdown-dark">
                    <li>
                        <a href="hms/patient/edit-profile.php">
                            My Profile
                        </a>
                    </li>
                    <li>
                        <a href="hms/patient/change-password.php">
                            Change Password
                        </a>
                    </li>
                    <li>
                        <a href="hms/patient/logout.php">
                            Log Out
                        </a>
                    </li>
                </ul>
            </div>';

            // Display the cart only if the user is logged in
            echo '
            <button id="cart-toggle" class="cart-toggle">
                <i class="fas fa-shopping-cart"></i> Cart
            </button>
            <div id="cart-container" class="cart-container">';
            
    // Common back button
    echo '<a href="/healhub/shop.php" class="back-button">Back</a>';
    // Display cart items and total
    if (!empty($_SESSION['cart'])) {
        $total = 0;
        echo '<table class="cart-table">';
        echo '<tr><th>Product</th><th>Quantity</th><th>Subtotal</th><th>Action</th></tr>';
        
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            // Fetch product details from the database
            $product_query = mysqli_query($con, "SELECT * FROM products WHERE id='$product_id'");
    
            // Check for errors in the query
            if (!$product_query) {
                echo '<tr>';
                echo '<td colspan="3">Error fetching product details: ' . mysqli_error($con) . '</td>';
                echo '</tr>';
            } else {
                // Fetch the product details
                $product = mysqli_fetch_assoc($product_query);
    
                // Check if product details were fetched successfully
                if ($product) {
                    // Calculate total price for each item
                    $subtotal = $quantity * $product['price'];
    
                    // Display cart item in a table row
                    echo '<tr class="cart-item" data-product-id="' . $product_id . '">';
                    echo '<td class="cart-item-name"><img src="/healhub/hms/admin/uploads/' . $product['image'] . '" alt="' . $product['name'] . '" class="cart-item-image"><br>' . $product['name'] . '</td>';
                    echo '<td class="cart-item-quantity">';
                    echo '<button class="quantity-button decrease-button" data-product-id="' . $product_id . '">-</button>';
                    echo '<span class="quantity-text" data-product-id="' . $product_id . '">' . $quantity . '</span>';
                    echo '<button class="quantity-button increase-button" data-product-id="' . $product_id . '">+</button>';
                    echo '</td>';
                    echo '<td class="cart-item-subtotal" data-product-id="' . $product_id . '" data-product-price="' . $product['price'] . '">₱' . number_format($subtotal, 2, '.', '') . '</td>';
                    echo '<td class="cart-item-remove"><button class="remove-button" data-product-id="' . $product_id . '">Remove</button></td>';
                    echo '</td>';
                    echo '</tr>';
    
                    // Add the current subtotal to the total
                    $total += $subtotal;
                }
            }
        }
    
        echo '</table>';
    
             // Display total
        echo '<div class="cart-total">';
        echo '<span>Total</span>';
        echo '<span>₱' . number_format($total, 2, '.', '') . '</span>';
        
        echo '</div>';

        echo '<div id="paypal-button-container"></div>';
        // Add the "View Cart" button
    } else {
        // Display a message when the cart is empty
        echo '<p>Your cart is empty.</p>';
    }

    echo '</div>'; // Close cart-container
    
        } else {
            // If user is not logged in, show login button
            echo '<a href="/healhub/hms/patient/user-login.php" class="btn"> Login </a>';
        }
        ?>
        <div id="menu-btn" class="fas fa-bars"></div>
    </header>

    <div id="app">
        <div class="main-content">
            <div class="wrap-content container" id="container">
                <div class="container">
                    <div class="product-list">
                    <?php while ($row = mysqli_fetch_array($sql)) : ?>
    <div class="product-item">
        <?php
        $imagePath = '/healhub/hms/admin/uploads/' . $row['image'];
        ?>
        <div class="product-image-container">
            <img src="<?php echo $imagePath; ?>" alt="<?php echo $row['name']; ?>" class="product-image">
        </div>
        <h3><?php echo $row['name']; ?></h3>
        <p><?php echo $row['description']; ?></p>
        <p>Price: ₱<?php echo number_format($row['price'], 2); ?></p>
        <form method="post" action="">
            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
            <label for="quantity<?php echo $row['id']; ?>">Quantity:</label>
            <input type="number" id="quantity<?php echo $row['id']; ?>" name="quantity" value="1" min="1" style="text-align: center;">
            <button type="submit" name="add_to_cart">Add to Cart</button>
        </form>
    </div>
<?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include the PayPal JavaScript SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=AVz5Ttco6E8JjSjXrbCDqbKxUV4Gzdwg2yVAh2TG0oCzqAUR_ur1K57lY816P-G9dO1fe99ttL9DvpUC&currency=PHP"></script>

<!-- Include your existing scripts, such as cart.js -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var userId = '<?php echo isset($_SESSION['id']) ? $_SESSION['id'] : 'null'; ?>';
</script>
<script src="assets/js/cart.js"></script>


</body>

</html>
