<?php
require_once 'Sections/PHPHeader.php';

$message = '';
$projectId = $_GET['projectId'] ?? null;

// Handle the form submission for adding a new script page
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pageName = $_POST['pageName'];
    $pageDescription = $_POST['pageDescription'];

    // Prepare and execute the insert statement for a new page
    $stmt = $pdo->prepare("INSERT INTO python_script_pages (ProjectID, PageName, PageDescription) VALUES (?, ?, ?)");
    $stmt->bindParam(1, $projectId);
    $stmt->bindParam(2, $pageName);
    $stmt->bindParam(3, $pageDescription);

    try {
        if ($stmt->execute()) {
            // Redirect to ViewProject.php with the projectId
            header("Location: ViewProject.php?id=" . $projectId);
            exit();
        }
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- [Head contents] -->
    <title>Add Script Page To-Do</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="config/main.css">
</head>
<body>
    <?php require_once 'Sections/Menu.php'; ?>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php if ($message): ?>
                    <div class="alert alert-info"><?php echo $message; ?></div>
                <?php endif; ?>

                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="card-title">Add New Script Page</h2>
                        <form action="AddProjectPage.php?projectId=<?php echo $projectId; ?>" method="post">
                            <div class="form-group">
                                <label for="pageName">Page Name</label>
                                <input type="text" class="form-control" name="pageName" placeholder="Enter page name" required>
                            </div>
                            <div class="form-group">
                                <label for="pageDescription">Page Description</label>
                                <textarea class="form-control" name="pageDescription" placeholder="Enter page description"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Page</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>