<?php
require_once 'Sections/PHPHeader.php';

// Fetch all projects from the database
$stmt = $pdo->prepare("SELECT * FROM projects");
$stmt->execute();
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects Page</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="config/main.css">
</head>
<body>
    <?php require_once 'Sections/Menu.php'; ?>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <a href="Add_Project.php" class="btn btn-primary mb-3">Create New Project</a>
                <div class="list-group">
                    <?php foreach ($projects as $project): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo htmlspecialchars($project['Title']); ?>
                            <!-- Link to view more details about the project -->
                            <a href="ViewProject.php?id=<?php echo $project['ProjectID']; ?>" class="btn btn-info btn-sm">View More</a>
                        </div>
                    <?php endforeach; ?>
                </div> 
            </div>
        </div>
    </div>

</body>
</html>