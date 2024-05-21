<?php
require_once 'Sections/PHPHeader.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $date_time = date('Y-m-d H:i:s');

    // Check if a project with the same title already exists
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE Title = ?");
    $stmt->execute([$title]);
    if ($stmt->rowCount() > 0) {
        $message = "A project with this title already exists.";
    } else {
        // Prepare and bind parameters
        $stmt = $pdo->prepare("INSERT INTO projects (Title, Description, Type, Date_Time) VALUES (?, ?, ?, ?)");
        $stmt->bindParam(1, $title);
        $stmt->bindParam(2, $description);
        $stmt->bindParam(3, $type);
        $stmt->bindParam(4, $date_time);

try {
    if ($stmt->execute()) {
        $message = "Project added successfully!";
        $lastInsertId = $pdo->lastInsertId();

        // Handle additional details for Python scripts
        if ($type == 'Python script' && isset($_POST['script_pages']) && is_array($_POST['script_pages'])) {
            foreach ($_POST['script_pages'] as $key => $page) {
                if (!empty($page['name']) && !empty($page['description'])) {
                    $pageName = $page['name'];
                    $pageDescription = $page['description'];
                    $stmt = $pdo->prepare("INSERT INTO python_script_pages (ProjectID, PageName, PageDescription) VALUES (?, ?, ?)");
                    $stmt->bindParam(1, $lastInsertId);
                    $stmt->bindParam(2, $pageName);
                    $stmt->bindParam(3, $pageDescription);
                    $stmt->execute();
                }
            }
        }

        // Redirect to Projects page
        header('Location: ProjectsPage.php'); // Replace 'ProjectsPage.php' with the actual name of your Projects page
        exit();
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
    <title>Add Project</title> 
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="config/main.css"> 
    <script>
        // JavaScript to dynamically add script pages fields
        function addScriptPage() {
            var container = document.getElementById("scriptPagesContainer");
            var template = document.getElementById("scriptPageTemplate");
            var clone = template.content.cloneNode(true);
            container.appendChild(clone);
        }
    </script>
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
                <h2 class="card-title">Add Project</h2>
                <form action="Add_Project.php" method="post">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" name="title" placeholder="Enter title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" placeholder="Enter description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select class="form-control" name="type" required>
                            <option value="Database">Database</option>
                            <option value="Website">Website</option>
                            <option value="Python script">Python script</option>
                            <!-- [Add other project types as needed] -->
                        </select>
                    </div>

                    <!-- Python Script Specific Fields -->
                    <div id="scriptPagesContainer">
                        <!-- Container for dynamically added script pages -->
                    </div>
                    <button type="button" onclick="addScriptPage()" class="btn btn-secondary">Add Script Page</button>

                    <button type="submit" class="btn btn-primary">Add Project</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Template for Python script pages -->
    <template id="scriptPageTemplate">
        <div class="form-group">
            <label for="scriptPageName">Script Page Name</label>
            <input type="text" class="form-control" name="script_pages[][name]" placeholder="Enter page name">
        </div>
        <div class="form-group">
            <label for="scriptPageDescription">Script Page Description</label>
            <textarea class="form-control" name="script_pages[][description]" placeholder="Enter page description"></textarea>
        </div>
    </template>

    <!-- [existing body content] -->
</body>
</html>
