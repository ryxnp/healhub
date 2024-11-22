<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('include/config.php');

// Fetch orders from the database
$sql = "SELECT * FROM orders";
$result = $con->query($sql);  // Update to use $con instead of $conn

// Check if it's an AJAX request to update order status or remove order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['order_id'])) {
        // AJAX request to update order status
        $orderId = $_POST['order_id'];

        // Fetch order status from the database based on the order ID
        $stmt = $con->prepare("SELECT status FROM orders WHERE id = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $stmt->bind_result($orderStatus);
        
        if ($stmt->fetch()) {
            echo $orderStatus;
        } else {
            echo 'Status not found';
        }

        $stmt->close();
        exit; // Stop further execution
}
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['del'])) {
    $orderId = $_GET['id'];

    // Delete order from the database
    $deleteQuery = "DELETE FROM orders WHERE id ='$orderId'";
    $result = $con->query($deleteQuery);

    if ($result) {
        $_SESSION['msg'] = "Order deleted!";
    } else {
        $_SESSION['msg'] = "Error deleting order!";
    }

    header('location: orders.php');
    exit; // Stop further execution
}
// Fetch all orders from the result set
$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shop Admin | Manage Products</title>
    <link rel="icon" href="assets/images/logo.png" type="image/x-icon" />

	<link
		href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic"
		rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
	<link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
	<link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
	<link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
	<link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
	<link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
	<link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
	<link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" href="assets/css/styles.css">
	<link rel="stylesheet" href="assets/css/plugins.css">
    <link rel="stylesheet" href="assets/css/products.css">
	<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
</head>

<body>
    <div id="app">
    <?php include('include/header.php'); ?>
        <?php include('include/sidebar.php'); ?>
        <div class="app-content">
            <div class="main-content">
                <div class="wrap-content container" id="container">
                    <section id="page-title">
                        <div class="row">
                            <div class="col-sm-8">
                                <h1 class="mainTitle">Admin | Order Tracking <?php echo $pageTitle; ?></h1>
                            </div>
                            <ol class="breadcrumb">
                                <li>
                                    <span>Admin </span>
                                </li>
                                <li class="active">
                                    <span><?php echo 'Order Tracking'; ?></span>
                                </li>
                            </ol>
                        </div>
                    </section>
                    <div class="container">
                        <h2><?php echo $pageTitle; ?></h2>
                        <p style="color: red;"><?php echo htmlentities($_SESSION['msg']); ?>
                            <?php echo htmlentities($_SESSION['msg'] = ""); ?></p>                  
                        <table border="1">
                            <thead>
                                <tr>
                                    <th>User Id</th>
                                    <th>Product ID</th>
                                    <th>Quantity</th>
                                    <th>Order Date</th>
                                    <th>Total Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order) : ?>
                                    <tr>
                                        <td><?php echo $order['user_id']; ?></td>
                                        <td><?php echo $order['product_id']; ?></td>
                                        <td><?php echo $order['quantity']; ?></td>
                                        <td><?php echo $order['order_date']; ?></td>
                                        <td><?php echo $order['total_price']; ?></td>
                                        <td>
                                            <!-- Removed the form and button for tracking order -->
                                            <a href="orders.php?id=<?php echo $order['id']; ?>&del=delete" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
    <?php include "include/footer.php"; ?>
	<!-- end: FOOTER -->

	<!-- start: SETTINGS -->
	<?php include "include/setting.php"; ?>

	<!-- end: SETTINGS -->
	</div>
	<!-- start: MAIN JAVASCRIPTS -->
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/modernizr/modernizr.js"></script>
	<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
	<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
	<script src="vendor/switchery/switchery.min.js"></script>
	<!-- end: MAIN JAVASCRIPTS -->
	<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
	<script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
	<script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
	<script src="vendor/autosize/autosize.min.js"></script>
	<script src="vendor/selectFx/classie.js"></script>
	<script src="vendor/selectFx/selectFx.js"></script>
	<script src="vendor/select2/select2.min.js"></script>
	<script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
	<script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
	<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
	<!-- start: CLIP-TWO JAVASCRIPTS -->
	<script src="assets/js/main.js"></script>
	<!-- start: JavaScript Event Handlers for this page -->
	<script src="assets/js/form-elements.js"></script>
    <!-- Include your JavaScript code here -->

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Loop through each order status cell
            $('.order-status').each(function() {
                // Get the order ID from the data attribute
                var orderId = $(this).data('order-id');

                // Send AJAX request to update order status
                $.ajax({
                    url: 'orders.php', // Same page for handling AJAX request
                    type: 'POST',
                    data: { order_id: orderId },
                    success: function(response) {
                        // Update the status cell with the new status
                        $(this).text(response);
                    }.bind(this), // Maintain the context of the current cell
                    error: function(error) {
                        console.error('Error updating order status:', error);
                    }
                });
            });
        });
    </script>


      


</html>

<?php  ?>
