<?php
session_start();
error_reporting(0);
include('include/config.php');

if (strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
} else {

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $contactno = $_POST['contactno'];
        $message = $_POST['message'];

        // Image upload handling
        $targetDirectory = $_SERVER['DOCUMENT_ROOT'] . "/healhub/uploads/"; // Adjust this path as per your setup
        $targetFile = $targetDirectory . basename($_FILES["userfile"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        if (!empty($_FILES["userfile"]["tmp_name"])) {
            $check = getimagesize($_FILES["userfile"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check file size
        if ($_FILES["userfile"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // // Allow certain file formats
        // if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        //     && $imageFileType != "gif") {
        //     echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        //     $uploadOk = 0;
        // }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["userfile"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $targetFile)) {
                $query = mysqli_query($con, "INSERT INTO contact (fullname,email,contactno,message,image)
                                             VALUES ('$fullname','$email','$contactno','$message','$targetFile')");
                if ($query) {
                    echo "<script>alert('Query has been added successfully');</script>";
                } else {
                    echo "<script>alert('Something went wrong. Please try again.');</script>";
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Fetch unread queries
    $sql = mysqli_query($con, "SELECT * FROM contact WHERE IsRead IS NULL");
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Admin | Manage Unread Queries</title>
        <link rel="icon" href="assets/images/logo.png" type="image/x-icon" />
        <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
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
        <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
    </head>

    <body>
        <div id="app">
            <?php include('include/sidebar.php'); ?>
            <div class="app-content">
                <?php include('include/header.php'); ?>
                <div class="main-content">
                    <div class="wrap-content container" id="container">
                        <section id="page-title">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h1 class="mainTitle">Admin | Manage Unread Queries</h1>
                                </div>
                                <ol class="breadcrumb">
                                    <li><span>Admin</span></li>
                                    <li class="active"><span>Unread Queries</span></li>
                                </ol>
                            </div>
                        </section>
                        <div class="container-fluid container-fullw bg-white">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="over-title margin-bottom-15">Manage <span class="text-bold">Unread Queries</span></h5>
                                    <table class="table table-hover" id="sample-table-1">
                                        <thead>
                                            <tr>
                                                <th class="center">#</th>
                                                <th>Name</th>
                                                <th class="hidden-xs">Email</th>
                                                <th>Contact No. </th>
                                                <th>Message </th>
                                                <th>Image </th>
                                                <th>Query Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cnt = 1;
                                            while ($row = mysqli_fetch_array($sql)) {
                                                ?>
                                                <tr>
                                                    <td class="center"><?php echo $cnt; ?>.</td>
                                                    <td class="hidden-xs"><?php echo $row['fullname']; ?></td>
                                                    <td><?php echo $row['email']; ?></td>
                                                    <td><?php echo $row['contactno']; ?></td>
                                                    <td><?php echo $row['message']; ?></td>
                                                    <td>
                                                        <?php if (!empty($row['image'])) : ?>
                                                            <img src="<?php echo $row['image']; ?>" alt="Uploaded Image" style="max-width: 100px; max-height: 100px;">
                                                        <?php else : ?>
                                                            No Image
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo $row['PostingDate']; ?></td>
                                                    <td>
                                                        <div class="visible-md visible-lg hidden-sm hidden-xs">
                                                            <a href="query-details.php?id=<?php echo $row['id']; ?>" class="btn btn-transparent btn-lg" title="View Details"><i class="fa fa-file"></i></a>
                                                        </div>
                                                        <div class="visible-xs visible-sm hidden-md hidden-lg">
                                                            <div class="btn-group" dropdown is-open="status.isopen">
                                                                <button type="button" class="btn btn-primary btn-o btn-sm dropdown-toggle" dropdown-toggle>
                                                                    <i class="fa fa-cog"></i>&nbsp;<span class="caret"></span>
                                                                </button>
                                                                <ul class="dropdown-menu pull-right dropdown-light" role="menu">
                                                                    <li><a href="#">Edit</a></li>
                                                                    <li><a href="#">Share</a></li>
                                                                    <li><a href="#">Remove</a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                                $cnt++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('include/footer.php'); ?>
        <?php include('include/setting.php'); ?>
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="vendor/modernizr/modernizr.js"></script>
        <script src="vendor/jquery-cookie/jquery.cookie.js"></script>
        <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
        <script src="vendor/switchery/switchery.min.js"></script>
        <script src="assets/js/main.js"></script>
        <script src="assets/js/form-elements.js"></script>
        <script>
            jQuery(document).ready(function() {
                Main.init();
                FormElements.init();
            });
        </script>
    </body>

    </html>
<?php } ?>
