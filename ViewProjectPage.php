<?php
require_once 'Sections/PHPHeader.php';

$pageId = $_GET['pageId'] ?? null;
$message = '';

// Fetch script page details
$stmt = $pdo->prepare("SELECT * FROM python_script_pages WHERE PageID = ?");
$stmt->execute([$pageId]);
$page = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch to-dos for the page
$stmt = $pdo->prepare("SELECT TodoID, TodoName, Description, Status FROM script_page_todos WHERE PageID = ?");
$stmt->execute([$pageId]);
$todos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle adding a new to-do
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addTodo'])) {
    $newName = $_POST['todoName'];
    $newDescription = $_POST['todoDescription'];
    $status = 'Waiting'; // Default status for new to-do

    // Check for duplicate TodoName
    $duplicateCheckStmt = $pdo->prepare("SELECT * FROM script_page_todos WHERE TodoName = ? AND PageID = ?");
    $duplicateCheckStmt->execute([$newName, $pageId]);
    if ($duplicateCheckStmt->rowCount() > 0) {
        $message = "A to-do with this name already exists on this page.";
    } else {
        // Insert new to-do
        $insertStmt = $pdo->prepare("INSERT INTO script_page_todos (PageID, TodoName, Description, Status) VALUES (?, ?, ?, ?)");
        $insertStmt->execute([$pageId, $newName, $newDescription, $status]);
        $message = "New to-do added successfully!";
    }
}

// Handle to-do updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateTodo'])) {
    $todoId = $_POST['todoId'];
    $updatedName = $_POST['todoName'];
    $updatedDescription = $_POST['description'];
    $updatedStatus = $_POST['status'];

    // Update to-do
    $updateStmt = $pdo->prepare("UPDATE script_page_todos SET TodoName = ?, Description = ?, Status = ? WHERE TodoID = ?");
    $updateStmt->execute([$updatedName, $updatedDescription, $updatedStatus, $todoId]);
    $message = "To-do updated successfully!";
}

// Handle to-do deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteTodo'])) {
    $todoId = $_POST['todoId'];

    // Delete to-do
    $deleteStmt = $pdo->prepare("DELETE FROM script_page_todos WHERE TodoID = ?");
    $deleteStmt->execute([$todoId]);
    $message = "To-do deleted successfully!";

    header("Location: ViewProjectPage.php?pageId=" . $pageId);
    exit();
}

// Redirect after POST to prevent resubmission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    header("Location: ViewProjectPage.php?pageId=" . $pageId);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Script Page</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="config/main.css">

    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this to-do?');
        }
    </script>

</head>
<body>
    <?php require_once 'Sections/Menu.php'; ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2><?php echo htmlspecialchars($page['PageName']); ?></h2>
            <p><?php echo htmlspecialchars($page['PageDescription']); ?></p>

            <?php if ($message): ?>
                <div class="alert alert-info"><?php echo $message; ?></div>
            <?php endif; ?>

            <!-- Form for adding a new to-do -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h3 class="card-title">Add New To-Do</h3>
                    <form action="ViewProjectPage.php?pageId=<?php echo $pageId; ?>" method="post">
                        <div class="form-group">
                            <label for="todoName">To-Do Name</label>
                            <input type="text" class="form-control" name="todoName" placeholder="Enter to-do name" required>
                        </div>
                        <div class="form-group">
                            <label for="todoDescription">Description</label>
                            <textarea class="form-control" name="todoDescription" placeholder="Enter description"></textarea>
                        </div>
                        <button type="submit" name="addTodo" class="btn btn-primary">Add To-Do</button>
                    </form>
                </div>
            </div>

            <!-- Display and edit existing to-dos -->
<?php foreach ($todos as $todo): ?>
    <div class="card mb-2">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Update Form -->
                <form action="ViewProjectPage.php?pageId=<?php echo $pageId; ?>" method="post" class="flex-grow-1 mr-2">
                    <div class="form-row align-items-center">
                        <div class="col">
                            <input type="text" class="form-control mb-2" name="todoName" value="<?php echo htmlspecialchars($todo['TodoName']); ?>" required>
                        </div>
                        <div class="col">
                            <select name="status" class="form-control mb-2">
                                <option value="Waiting" <?php echo $todo['Status'] == 'Waiting' ? 'selected' : ''; ?>>Waiting</option>
                                <option value="Started" <?php echo $todo['Status'] == 'Started' ? 'selected' : ''; ?>>Started</option>
                                <option value="Completed" <?php echo $todo['Status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" name="updateTodo" class="btn btn-primary mb-2">Update</button>
                        </div>
                    </div>
                    <textarea class="form-control" name="description" placeholder="Enter description"><?php echo htmlspecialchars($todo['Description']); ?></textarea>
                    <input type="hidden" name="todoId" value="<?php echo $todo['TodoID']; ?>">
                </form>
                <!-- Delete Button -->
                <form action="ViewProjectPage.php?pageId=<?php echo $pageId; ?>" method="post" onsubmit="return confirmDelete();" class="ml-2">
                    <input type="hidden" name="todoId" value="<?php echo $todo['TodoID']; ?>">
                    <button type="submit" name="deleteTodo" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
        </div>
    </div>
</div>

</body>
</html>