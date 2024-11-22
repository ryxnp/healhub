<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
include "include/config.php";

if (strlen($_SESSION["id"] == 0)) {
    header("location:logout.php");
} else {

    // Check if it's Add Product or Edit Product
    if (isset($_GET["edit"])) {
        $pid = $_GET["edit"];
        $query = mysqli_query($con, "SELECT * FROM products WHERE id='$pid'");
        $row = mysqli_fetch_array($query);
        $productName = $row["name"];
        $productDesc = $row["description"];
        $productPrice = $row["price"];
        $currentImage = $row["image"]; // Store the current image filename
        $pageTitle = "Edit Product";
        $action = "edit";
    } else {
        $productName = "";
        $productDesc = "";
        $productPrice = "";
        $currentImage = ""; // Set currentImage to empty for a new product
        $pageTitle = "Add Product";
        $action = "add";
    }
    // Add or Edit Product Function
    if (isset($_POST["action"])) {
        if ($_POST["action"] == "add") {
            $productName = $_POST["productName"];
            $productDesc = $_POST["productDesc"];
            $productPrice = $_POST["productPrice"];

            $uploadDir =
                $_SERVER["DOCUMENT_ROOT"] . "/healhub/hms/admin/uploads/";
            $target_file = $uploadDir . $_FILES["productImage"]["name"];
            $uploadOk = 1;
            $imageFileType = strtolower(
                pathinfo($target_file, PATHINFO_EXTENSION)
            );

            $check = getimagesize($_FILES["productImage"]["tmp_name"]);
            if ($check === false) {
                $_SESSION["msg"] = "File is not an image.";
                $uploadOk = 0;
            }

            if (file_exists($target_file)) {
                $_SESSION["msg"] = "Sorry, file already exists.";
                $uploadOk = 0;
            }

            if ($_FILES["productImage"]["size"] > 5000000) {
                $_SESSION["msg"] = "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
                $_SESSION["msg"] =
                    "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                $_SESSION["msg"] = "Sorry, your file was not uploaded.";
            } else {
                if (
                    move_uploaded_file(
                        $_FILES["productImage"]["tmp_name"],
                        $target_file
                    )
                ) {
                    $filename = $_FILES["productImage"]["name"];
                    $query = mysqli_query(
                        $con,
                        "INSERT INTO products (name, description, price, image) VALUES ('$productName', '$productDesc', '$productPrice', '$filename')"
                    );
                    if ($query) {
                        $_SESSION["msg"] = "Product added successfully!";
                    } else {
                        $_SESSION["msg"] = "Error adding product!";
                    }
                } else {
                    $_SESSION["msg"] =
                        "Sorry, there was an error uploading your file.";
                }
            }
        } elseif ($_POST["action"] == "edit") {
            $pid = $_POST["pid"];
            $productName = $_POST["productName"];
            $productDesc = $_POST["productDesc"];
            $productPrice = $_POST["productPrice"];

            // Check if a new image file is selected
            if (!empty($_FILES["productImage"]["tmp_name"])) {
                // Image Upload
                $target_dir = "uploads/";
                $target_file =
                    $target_dir . basename($_FILES["productImage"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(
                    pathinfo($target_file, PATHINFO_EXTENSION)
                );

                // Check if image file is a valid image
                $check = getimagesize($_FILES["productImage"]["tmp_name"]);
                if ($check === false) {
                    $_SESSION["msg"] = "File is not an image.";
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 1) {
                    if (
                        move_uploaded_file(
                            $_FILES["productImage"]["tmp_name"],
                            $target_file
                        )
                    ) {
                        $filename = $_FILES["productImage"]["name"];
                        $query = mysqli_query(
                            $con,
                            "UPDATE products SET name='$productName', description='$productDesc', price='$productPrice', image='$filename' WHERE id='$pid'"
                        );
                        if ($query) {
                            $_SESSION["msg"] = "Product updated successfully!";
                        } else {
                            $_SESSION["msg"] = "Error updating product!";
                        }
                    } else {
                        $_SESSION["msg"] =
                            "Sorry, there was an error uploading your file.";
                    }
                } else {
                    $_SESSION["msg"] =
                        "Error updating product! Invalid image file.";
                }
            } else {
                // Update product details without changing the image
                $query = mysqli_query(
                    $con,
                    "UPDATE products SET name='$productName', description='$productDesc', price='$productPrice' WHERE id='$pid'"
                );
                if ($query) {
                    $_SESSION["msg"] = "Product updated successfully!";
                } else {
                    $_SESSION["msg"] = "Error updating product!";
                }
            }

            header("location: products.php");
        }
    }
    // Delete Product Function
    if (isset($_GET["del"])) {
        $pid = $_GET["id"];
        $query = mysqli_query($con, "DELETE FROM products WHERE id ='$pid'");
        if ($query) {
            $_SESSION["msg"] = "Product deleted!";
        } else {
            $_SESSION["msg"] = "Error deleting product!";
        }
        header("location: products.php");
    }

    // Check if it's Add Product or Edit Product
    if (isset($_GET["edit"])) {
        $pid = $_GET["edit"];
        $query = mysqli_query($con, "SELECT * FROM products WHERE id='$pid'");
        $row = mysqli_fetch_array($query);
        $productName = $row["name"];
        $productDesc = $row["description"];
        $productPrice = $row["price"];
        $pageTitle = "Edit Product";
        $action = "edit";
    } else {
        $productName = "";
        $productDesc = "";
        $productPrice = "";
        $pageTitle = "Add Product";
        $action = "add";
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
    <?php include "include/header.php"; ?>
        <?php include "include/sidebar.php"; ?>
        <div class="app-content">
            <div class="main-content">
                <div class="wrap-content container" id="container">
                    <section id="page-title">
                        <div class="row">
                            <div class="col-sm-8">
                                <h1 class="mainTitle">Admin | <?php echo $pageTitle; ?></h1>
                            </div>
                            <ol class="breadcrumb">
                                <li>
                                    <span>Admin</span>
                                </li>
                                <li class="active">
                                    <span><?php echo $pageTitle; ?></span>
                                </li>
                            </ol>
                        </div>
                    </section>
                    <div class="container">
                        <h2><?php echo $pageTitle; ?></h2>
                        <p style="color: red;"><?php echo htmlentities(
                            $_SESSION["msg"]
                        ); ?>
                            <?php echo htmlentities(
                                $_SESSION["msg"] = ""
                            ); ?></p>
                            
                        <!-- Add or Edit Product Form -->
                        <form method="post" action="products.php" enctype="multipart/form-data">
                            <label for="productName">Product Name:</label>
                            <input type="text" name="productName" id="productName" value="<?php echo $productName; ?>" required>

                            <label for="productDesc">Product Description:</label>
                            <textarea name="productDesc" id="productDesc" required><?php echo $productDesc; ?></textarea>

                            <label for="productPrice">Product Price:</label>
                            <input type="text" name="productPrice" id="productPrice" value="<?php echo $productPrice; ?>" required>

                            <label for="productImage">Product Image:</label>
                            <?php if ($action == "edit"): ?>
                                <img src="uploads/<?php echo $currentImage; ?>" width="100" height="100" alt="Current Image">
                                <input type="file" name="productImage" id="hiddenProductImage" style="display: none;" onchange="displayFileName(this)">
                                <label class="file-label" for="hiddenProductImage">
                                    <span>Select New File</span>
                                </label>
                                <span id="fileNameLabel" style="margin-left: 10px;"></span>
                            <?php else: ?>
                                <input type="file" name="productImage" id="productImage" onchange="displayFileName(this)">
                                <label class="file-label" for="productImage">
                                    <span>Choose File</span>
                                </label>
                                <span id="fileNameLabel" style="margin-left: 10px;"></span>
                            <?php endif; ?>

                            <input type="hidden" name="action" value="<?php echo $action; ?>">
                            <?php if ($action == "edit"): ?>
                                <input type="hidden" name="pid" value="<?php echo $pid; ?>">
                                <input type="hidden" name="currentImage" value="<?php echo $currentImage; ?>">
                            <?php endif; ?>

                            <button type="submit"><?php echo $action == "add"
                                ? "Add Product"
                                : "Update Product"; ?></button>

                            <?php if ($action == "edit"): ?>
                                <!-- Cancel button redirects to products.php without making any changes -->
                                <a href="products.php" class="btn btn-secondary">Cancel</a>
                            <?php endif; ?>
                        </form>
                        
                        <!-- End Add or Edit Product Form -->

                        <table border="1">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = mysqli_query(
                                    $con,
                                    "SELECT * FROM products"
                                );
                                while ($row = mysqli_fetch_array($sql)) { ?>
                                    <tr>
                                        <td><?php echo $row["id"]; ?></td>
                                        <td><?php echo $row["name"]; ?></td>
                                        <td><?php echo $row[
                                            "description"
                                        ]; ?></td>
                                        <td><?php echo $row["price"]; ?></td>
                                        <td><img src="uploads/<?php echo $row[
                                            "image"
                                        ]; ?>" width="50" height="50"></td>

                                        <td>
                                            <a href="products.php?edit=<?php echo $row[
                                                "id"
                                            ]; ?>">Edit</a> |
                                            <a href="products.php?id=<?php echo $row[
                                                "id"
                                            ]; ?>&del=delete" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<!-- start: FOOTER -->
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
	<script>
		jQuery(document).ready(function () {
			Main.init();
			FormElements.init();
		});
	</script>
	<!-- end: JavaScript Event Handlers for this page -->
	<!-- end: CLIP-TWO JAVASCRIPTS -->
<script>
        function displayFileName(input) {
            const fileNameLabel = document.getElementById('fileNameLabel');
            if (input.files.length > 0) {
                fileNameLabel.innerText = input.files[0].name;
                fileNameLabel.style.display = 'inline';
            } else {
                fileNameLabel.innerText = '';
                fileNameLabel.style.display = 'none';
            }
        }
    </script>
</html>
<?php
} ?>
