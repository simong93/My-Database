<?php
// Config/Config.php
require_once 'Config/Config.php';

// Database Connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$showAlert = false;
$email_exists = false;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) {
        $email_exists = true;
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into Users table
        $stmt = $conn->prepare("INSERT INTO Users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            $showAlert = true;
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Config/Main.css">
</head>
<body style="background-image: url('https://t3.ftcdn.net/jpg/06/05/39/80/360_F_605398028_61PN7u2bpFWQq1ppDcTpfw9919zWEzSt.jpg'); background-size: cover;">
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="col-4">
                <div class="text-center mb-4">
                    <img src="https://www.offroadsegway.co.uk/wp-content/uploads/2023/04/WhatsApp-Image-2023-09-09-at-9.35.38-PM-1024x1024.webp" alt="Logo" width="100">
                </div>
                
                <?php if ($email_exists): ?>
                <div class="alert alert-danger" role="alert">
                    The email address is already registered. Please try another one or contact support.
                </div>
                <?php endif; ?>

                <?php if ($showAlert): ?>
                <div class="alert alert-success" role="alert">
                    Thank you for registering! Please contact IT for account approval.
                </div>
                <script>
                    setTimeout(function(){
                        window.location.href = 'login.php';
                    }, 5000); // Redirect after 5 seconds
                </script>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form action="registration.php" method="post">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <!-- reCAPTCHA widget will be here -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>
