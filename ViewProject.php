<?php
require_once 'Sections/PHPHeader.php';

$message = '';

// Get project ID from URL
$projectId = $_GET['id'] ?? null;

if (!$projectId) {
    // Redirect or show error if project ID is not provided
    header('Location: ProjectsPage.php'); // Redirect to the Projects page
    exit();
}

// Fetch project details
$stmt = $pdo->prepare("SELECT * FROM projects WHERE ProjectID = ?");
$stmt->execute([$projectId]);
$project = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$project) {
    // Handle case where project is not found
    $message = 'Project not found.';
} else {
    // Fetch pages for the project if it's a Python script
    if ($project['Type'] == 'Python script') {
        $stmt = $pdo->prepare("SELECT * FROM python_script_pages WHERE ProjectID = ?");
        $stmt->execute([$projectId]);
        $pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Project</title>
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
                <?php else: ?>
                    <div class="card shadow">
                        <div class="card-body">
                            <h2 class="card-title"><?php echo htmlspecialchars($project['Title']); ?></h2>
                            <p><?php echo htmlspecialchars($project['Description']); ?></p>
                            <a href="AddProjectPage.php?projectId=<?php echo $projectId; ?>" class="btn btn-primary mb-3">Add Page</a>
                            <ul class="list-group">
                                <?php if (isset($pages)): ?>
                                    <?php foreach ($pages as $page): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?php echo htmlspecialchars($page['PageName']); ?>
                                            <a href="ViewProjectPage.php?pageId=<?php echo $page['PageID']; ?>" class="btn btn-info btn-sm">View More</a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>