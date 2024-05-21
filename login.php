<?php
require_once 'Config/Config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database Connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loginError = false;
$inactiveError = false;
$emailError = false; // Initialize the error flag for email
$passwordError = false; // Initialize the error flag for password

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // reCAPTCHA verification
    if (isset($_POST['g-recaptcha-response'])) {
        $recaptchaResponse = $_POST['g-recaptcha-response'];
        $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . RECAPTCHA_SECRET . "&response=" . $recaptchaResponse);
        $responseKeys = json_decode($response, true);

        if (intval($responseKeys["success"]) !== 1) {
            $loginError = true; 
        } 
    }
    
    if (!$loginError) { 
        $email = $_POST["email"];
        $password = $_POST["password"];

        $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                if ($user['activated'] == 1) {
                    session_start();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['userID'] = $user['id'];
                    $_SESSION['username'] = $user['name']; // Store the username in a session variable
                    header("Location: index.php");
                    exit;
                } else {
                    $inactiveError = true;
                }
            } else {
                $passwordError = true; // Add this line
            }
        } else {
            $emailError = true; // Add this line
        }
    }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Config/Main.css">
</head>
<body style="background-image: url('https://t3.ftcdn.net/jpg/06/05/39/80/360_F_605398028_61PN7u2bpFWQq1ppDcTpfw9919zWEzSt.jpg'); background-size: cover;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <img src="https://www.offroadsegway.co.uk/wp-content/uploads/2023/04/WhatsApp-Image-2023-09-09-at-9.35.38-PM-1024x1024.webp" class="mx-auto d-block mt-5 mb-4" width="150" height="150">
            <div class="card" style="background-color: rgba(255, 255, 255, 0.8);">
                <div class="card-body">
                    <form action="login.php" method="post">
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="Email/Username" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" onclick="location.href='registration.php';">Register</button>
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        <div class="g-recaptcha mb-2" data-sitekey="<?php echo RECAPTCHA_SITEKEY; ?>"></div> 

                        <?php 
                            if ($loginError) {
                                echo "<div class='alert alert-danger'>Login failed due to reCAPTCHA verification.</div>";
                            }
                            if ($emailError) {
                                echo "<div class='alert alert-danger'>Email not found.</div>";
                            }
                            if ($passwordError) {
                                echo "<div class='alert alert-danger'>Incorrect password.</div>";
                            }
                            if ($inactiveError) {
                                echo "<div class='alert alert-warning'>Your account is not activated. Please contact IT for activation.</div>";
                            }
                        ?> 
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>