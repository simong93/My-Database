<?php
// Starting session
session_start();

// Destroying session to log the user out
session_destroy();

require_once 'Sections/PHPHeader.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="config/main.css">
    <meta http-equiv="refresh" content="5;url=login.php" />

</head>

<body>

    <?php
    require_once 'Sections/Menu.php';
    ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Goodbye!</div>
                    <div class="card-body">
                        <h2>See You Soon!</h2>
                        <p>Thanks for stopping by. We hope to see you again soon!</p>
                        <a href="login.php" class="btn btn-primary">Log Back In</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
