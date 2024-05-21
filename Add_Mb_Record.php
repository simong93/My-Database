<?php
require_once 'Sections/PHPHeader.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $url = $_POST['url'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $type = $_POST['type']; // This will be passed as a hidden field 
    $date_time = date('Y-m-d H:i:s');

    // Check if the URL already exists
    $stmt = $pdo->prepare("SELECT * FROM mb WHERE Url = ?");
    $stmt->execute([$url]);
    if ($stmt->rowCount() > 0) {
        $message = "The URL already exists in the database.";
    } else {
        // URL does not exist, proceed with insertion
        $stmt = $pdo->prepare("INSERT INTO mb (Url, Username, Password, Date_Time, State, Type) VALUES (?, ?, ?, ?, 'Active', ?)");
        $stmt->bindParam(1, $url);
        $stmt->bindParam(2, $username);
        $stmt->bindParam(3, $password);
        $stmt->bindParam(4, $date_time);
        $stmt->bindParam(5, $type);

        try {
            if ($stmt->execute()) {
                $message = "MBAccount added successfully!";
            }
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add MBAccount</title> 
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="config/main.css">
</head>
<body>
    <?php
    require_once 'Sections/Menu.php';
    ?>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php
                if ($message) {
                    echo "<div class='alert alert-info'>$message</div>";
                }
                ?>
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title">Add MBAccount</h2>
                        <form action="Add_Mb_Record.php" method="post">
                            <input type="hidden" name="type" value="<?php echo isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'default'; ?>">
                            <div class="form-group">
                                <label for="url">URL</label>
                                <input type="text" class="form-control" name="url" placeholder="Enter URL" required>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" name="username" placeholder="Enter username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="text" class="form-control" name="password" placeholder="Enter password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
</body>
</html>
