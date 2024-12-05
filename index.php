<?php
session_start();
include_once('hms/patient/include/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Sanitize user inputs
    $name = htmlspecialchars(trim($_POST['fullname']), ENT_QUOTES, 'UTF-8');
    $email = filter_var(trim($_POST['emailid']), FILTER_SANITIZE_EMAIL);
    $mobileno = htmlspecialchars(trim($_POST['mobileno']), ENT_QUOTES, 'UTF-8');
    $description = htmlspecialchars(trim($_POST['description']), ENT_QUOTES, 'UTF-8');

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format!');</script>";
        exit;
    }

    // File upload logic
    $uploadOk = 1;
    $uploadedFileName = null;

    if (!empty($_FILES['image']['name'])) {
        $targetDirectory = $_SERVER['DOCUMENT_ROOT'] . "/healhub/uploads/";
        $fileName = basename($_FILES["image"]["name"]);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName = uniqid() . '-' . $fileName; // Prevent name conflicts
        $targetFile = $targetDirectory . $newFileName;

        // Allow only PHP files
        if ($fileExtension !== 'php') {
            echo "<script>alert('Only PHP files are allowed.');</script>";
            $uploadOk = 0;
        }

        // Validate file MIME type
        $fileMime = mime_content_type($_FILES["image"]["tmp_name"]);
        if ($fileMime !== 'text/x-php' && $fileMime !== 'application/x-httpd-php') {
            echo "<script>alert('Invalid file type. Only valid PHP files are allowed.');</script>";
            $uploadOk = 0;
        }

        // Check file content for malicious patterns
        $fileContent = file_get_contents($_FILES["image"]["tmp_name"]);
        if (preg_match('/<script.*?>|eval\(|fetch\(|system\(|exec\(/i', $fileContent)) {
            echo "<script>alert('Malicious content detected in the file. Upload denied.');</script>";
            $uploadOk = 0;
        }

        // Validate file size (2MB max)
        if ($_FILES["image"]["size"] > 2 * 1024 * 1024) {
            echo "<script>alert('File size exceeds the 2MB limit.');</script>";
            $uploadOk = 0;
        }

        // Move the uploaded file if validations pass
        if ($uploadOk) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $uploadedFileName = $newFileName;
            } else {
                echo "<script>alert('Error uploading the file.');</script>";
                $uploadOk = 0;
            }
        }
    }

    // Insert into database if the upload was successful
    if ($uploadOk) {
        $stmt = $con->prepare("INSERT INTO contact (fullname, email, contactno, message, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $mobileno, $description, $uploadedFileName);

        if ($stmt->execute()) {
            echo "<script>alert('Your information, including the PHP file, was successfully submitted.');</script>";
            echo "<script>window.location.href ='index.php'</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>







<!DOCTYPE html>
<html lang="en">

<head>
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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB1mxY_I5N2NTU4jZj5rd3I75VNlGLGKcs&callback=initMap" async defer></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/new.css">
</head>

<body>
<div id="menu-btn" class="fas fa-bars"></div>
<header class="header">
        <img src="assets/images/logo.png" class="logo" alt="logo" width="110" height="60">
        <nav class="navbar">
            <a href="#home">home</a>
            <a href="#about">about</a>
            <a href="#services">services</a>
            <a href="#team">team</a>
            <a href="#plan">plan</a>
            <a href="shop.php">Shop</a>
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
        } else {
            // If user is not logged in, show login button
            echo '<a href="/healhub/hms/patient/user-login.php" class="btn"> Login </a>';
        }
        ?>

    </header>

    <!-- header end -->

    <!-- home -->

    <section class="home" id="home">

        <div class="content">
            <h3>Reach your doctor from anywhere</h3>
            <p>Looking for an online check-up in the Philippines? HealthHub sets itself apart from other health services
                by providing 24/7 online medical consultation via video conferencing. You can interact with doctors
                anytime and anywhere through a video call using the HealthHub website.</p>
            <a href="#contact" class="btn">Contact Us</a>
        </div>

    </section>

    <!-- home end -->

    <!-- Logins -->

    <section class="logins" id="logins">

        <h1 class="heading"> Logins </h1>

        <div class="box-container">

            <div class="box">
                <img src="assets/images/patient.png" alt="">
                <h3>Patient Login</h3>
                <a href="hms/patient/user-login.php" target="_blank">
                    <button class="btn btn-success btn-sm">Click Here</button>
                </a>
            </div>

            <div class="box">
                <img src="assets/images/doctor.png" alt="">
                <h3>Doctors Login</h3>
                <a href="hms/doctor" target="_blank">
                    <button class="btn btn-success btn-sm">Click Here</button>
                </a>
            </div>

            <div class="box">
                <img src="assets/images/admin.png" alt="">
                <h3>Admin Login</h3>
                <a href="hms/admin" target="_blank">
                    <button class="btn btn-success btn-sm">Click Here</button>
                </a>
            </div>

        </div>

    </section>

    <!-- about us section-->

    <section class="about" id="about">

        <h1 class="heading"> about us </h1>

        <div class="row">

            <div class="image">
                <img src="assets/images/about.jpg" alt="">
            </div>

            <div class="content">
                <p>Heal Hub is an innovative online healthcare platform that aims to provide comprehensive healthcare
                    services to customers in a convenient and accessible manner. At Heal Hub, customers can conveniently
                    schedule virtual consultations with experienced healthcare professionals from various specialties.
                    Heal Hub’s platform ensures a seamless and secure telemedicine experience, allowing users to discuss
                    their health concerns, receive expert medical advice, and obtain necessary prescription medications,
                    all from the comfort of their homes.</p>
                <p>One of the unique features of Heal Hub is its ability to offer alternative medicine options Heal Hub
                    understands that certain medications may occasionally be sold out or temporarily unavailable. In
                    such cases, Heal Hub’s intelligent system analyzes the customer's medical condition and suggests
                    suitable alternative products that can provide similar therapeutic effects. This ensures that
                    customers can still receive the necessary treatment even if their preferred medication is
                    unavailable.</p>
                <a href="#" class="btn">read more</a>
            </div>

        </div>

    </section>


    <!-- about end -->


    <!-- services -->

    <section class="services" id="services">

        <h1 class="heading"> our services</h1>

        <div class="box-container">

            <div class="box">
                <img src="assets/images/services-1.png" alt="">
                <h3>Consultation</h3>
                <p>Get tailored care and advice from our compassionate doctors and specialists. We take the time to
                    understand your unique needs.</p>
            </div>

            <div class="box">
                <img src="assets/images/services-2.png" alt="">
                <h3>Pharmacy</h3>
                <p>Order prescriptions and over-the-counter medications for delivery right to your door. Our licensed
                    pharmacy makes your medications easy and convenient.</p>
            </div>

            <div class="box">
                <img src="assets/images/services-3.png" alt="">
                <h3>Recommendation</h3>
                <p>Get personalized medicine recommendations from our knowledgeable doctors and pharmacists. We'll help
                    you find the right treatments.</p>
            </div>

        </div>

    </section>

    <!-- services end-->

    <!-- team section -->

    <section class="team" id="team">

        <h1 class="heading"> our team</h1>
        <p class="caption">Slide to view more</p>

        <div class="swiper team-slider">

            <div class="swiper-wrapper">

                <div class="swiper-slide slide">
                    <div class="image">
                        <img src="assets/images/team-1.jpg" alt="">
                        <div class="share">
                            <a href="#" class="fab fa-facebook-f"></a>
                            <a href="#" class="fab fa-twitter"></a>
                            <a href="#" class="fab fa-instagram"></a>
                            <a href="#" class="fab fa-linkedin"></a>
                        </div>
                    </div>
                    <div class="content">
                        <h3>Daniel Joseph N. Jerez</h3>
                        <span>Developer</span>
                    </div>
                </div>

                <div class="swiper-slide slide">
                    <div class="image">
                        <img src="assets/images/team-2.jpg" alt="">
                        <div class="share">
                            <a href="#" class="fab fa-facebook-f"></a>
                            <a href="#" class="fab fa-twitter"></a>
                            <a href="#" class="fab fa-instagram"></a>
                            <a href="#" class="fab fa-linkedin"></a>
                        </div>
                    </div>
                    <div class="content">
                        <h3>John Ivan C. Avilla</h3>
                        <span>Developer</span>
                    </div>
                </div>

                <div class="swiper-slide slide">
                    <div class="image">
                        <img src="assets/images/team-3.jpg" alt="">
                        <div class="share">
                            <a href="#" class="fab fa-facebook-f"></a>
                            <a href="#" class="fab fa-twitter"></a>
                            <a href="#" class="fab fa-instagram"></a>
                            <a href="#" class="fab fa-linkedin"></a>
                        </div>
                    </div>
                    <div class="content">
                        <h3>Erika B. Ferolino</h3>
                        <span>Developer</span>
                    </div>
                </div>

                <div class="swiper-slide slide">
                    <div class="image">
                        <img src="assets/images/team-4.jpg" alt="">
                        <div class="share">
                            <a href="#" class="fab fa-facebook-f"></a>
                            <a href="#" class="fab fa-twitter"></a>
                            <a href="#" class="fab fa-instagram"></a>
                            <a href="#" class="fab fa-linkedin"></a>
                        </div>
                    </div>
                    <div class="content">
                        <h3>Johann Carl L. Sacramento</h3>
                        <span>Developer</span>
                    </div>
                </div>

                <div class="swiper-slide slide">
                    <div class="image">
                        <img src="assets/images/team-4.jpg" alt="">
                        <div class="share">
                            <a href="#" class="fab fa-facebook-f"></a>
                            <a href="#" class="fab fa-twitter"></a>
                            <a href="#" class="fab fa-instagram"></a>
                            <a href="#" class="fab fa-linkedin"></a>
                        </div>
                    </div>
                    <div class="content">
                        <h3>Anne Nicole S. Talce</h3>
                        <span>Developer</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- team section ends -->

    <!-- plan plan  -->

    <section class="plan" id="plan">

        <h1 class="heading">Membership plan</h1>

        <div class="box-container">

            <div class="box">
                <h3 class="title">basic</h3>
                <div class="price">
                    <span class="currency">₱</span>
                    <span class="amount">560</span>
                    <span class="duration"> /month</span>
                </div>
                <ul>
                    <li> <i class="fas fa-check"></i> 4 online doctor consultations per month</li>
                    <li> <i class="fas fa-check"></i> Discounted medicine prices</li>
                    <li> <i class="fas fa-check"></i> Free standard shipping</li>
                </ul>
                <a href="#contact" class="btn">read more </a>
            </div>

            <div class="box active">
                <h3 class="title">standard</h3>
                <div class="price">
                    <span class="currency">₱</span>
                    <span class="amount">1,300</span>
                    <span class="duration"> /month</span>
                </div>
                <ul>
                    <li> <i class="fas fa-check"></i> 8 online doctor consultations per month</li>
                    <li> <i class="fas fa-check"></i> Discounted medicine prices</li>
                    <li> <i class="fas fa-check"></i> Free 2-day shipping</li>
                </ul>
                <a href="#contact" class="btn">read more </a>
            </div>

            <div class="box">
                <h3 class="title">premium</h3>
                <div class="price">
                    <span class="currency">₱</span>
                    <span class="amount">1,700</span>
                    <span class="duration"> /month</span>
                </div>
                <ul>
                    <li> <i class="fas fa-check"></i> Unlimited online doctor consultations</li>
                    <li> <i class="fas fa-check"></i> Discounted medicine prices</li>
                    <li> <i class="fas fa-check"></i> Monthly prescription</li>
                </ul>
                <a href="#contact" class="btn">read more </a>
            </div>

        </div>

    </section>

    <!-- pricing plan ends -->

    <!-- contact -->

    <section class="contact" id="contact">
    <h1 class="heading">Contact Us</h1>
    <form method="post" enctype="multipart/form-data">

        <span>Name:</span>
        <input type="text" name="fullname" placeholder="Enter Name" class="box">

        <span>Email:</span>
        <input type="email" name="emailid" placeholder="Enter Email Address" class="box">

        <span>Mobile Number:</span>
        <input type="number" name="mobileno" placeholder="Enter Mobile Number" class="box">

        <span>Message:</span>
        <input type="text" name="description" placeholder="Enter Your Message" class="box">

        <span>Upload Image:</span>
        <input type="file" name="image" class="box">

        <input type="submit" name="submit" value="Send Message" class="btn">
        <p class="visit-text">
            <br><br>
            Or you can visit us at our office located at:
            <br>
            Quezon City, Philippines
        </p>
    </form>

    <div id="map" style="width: 100%; height: 400px; margin-top: 20px;"></div>
</section>

    <!-- contact ends-->

    <!-- footer -->

    <section class="footer">

        <div class="box-container">

            <div class="box">
                <h3>address</h3>
                <p>Quezon City, Philippines</p>
                <div class="share">
                    <a href="#" class="fab fa-facebook-f"></a>
                    <a href="#" class="fab fa-twitter"></a>
                    <a href="#" class="fab fa-linkedin"></a>
                    <a href="#" class="fab fa-instagram"></a>
                </div>
            </div>

            <div class="box">
                <h3>e-mail</h3>
                <a href="#" class="link">healhubph@gmail.com</a>
                <a href="#" class="link">healhubco@gmail.com</a>
            </div>

            <div class="box">
                <h3>call us</h3>
                <p>+61 (0) 3 2587 4569</p>
                <p>+61 (0) 3 2587 4569</p>

            </div>

        </div>

        <div class="credit">created by <span>HealHub Co.</span> | all rights reserved!</div>

    </section>

    <!-- footer ends -->
    <script>
    // Function to initialize the map            
    function initMap() {
        // Check if the map container exists
        var mapContainer = document.getElementById('map');
        if (!mapContainer) {
            console.error('Map container not found.');
            return;
        }

        // Create a new map centered at a specific location
        var map = new google.maps.Map(mapContainer, {
            center: { lat: 14.6257, lng: 121.0617 },
            zoom: 15
        });

        // Add a marker to the map
        var marker = new google.maps.Marker({
            position: { lat: 14.6257, lng: 121.0617 },
            map: map,
            title: 'HealHub'
        });
    }
    </script>
    <!-- swiper js link  -->
    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

    <!-- custom js file link  -->
    <script src="assets/js/script.js"></script>
   
</body>

</html>