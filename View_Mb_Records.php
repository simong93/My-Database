<?php
require_once 'Sections/PHPHeader.php';

$type = isset($_GET['type']) ? $_GET['type'] : 'default';
$records = [];

// Fetch records from the database
$stmt = $pdo->prepare("SELECT ID, Url, Username, Password, State FROM mb WHERE Type = ?");
$stmt->execute([$type]);
$records = $stmt->fetchAll();

// Update state if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['state']) && isset($_POST['id'])) {
    $newState = $_POST['state'];
    $id = $_POST['id'];

    $updateStmt = $pdo->prepare("UPDATE mb SET State = ? WHERE ID = ?");
    $updateStmt->execute([$newState, $id]);

    // Refresh the records to reflect the update
    $stmt->execute([$type]);
    $records = $stmt->fetchAll();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Records - <?php echo htmlspecialchars($type); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="config/main.css"> <!-- Ensure this path is correct -->
</head>
<body>

<?php require_once 'Sections/Menu.php'; ?>

<div class="container mt-4 semi-transparent-bg">
    <h2>View <?php echo htmlspecialchars($type); ?> Records</h2>
    <div class="table-responsive">
        <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>URL</th>
                <th>Username</th>
                <th>Password</th> <!-- Added Password column -->
                <th>State</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($records as $record): ?>
            <tr>
                <td><?php echo htmlspecialchars($record['ID']); ?></td>
                <td><?php echo htmlspecialchars($record['Url']); ?></td>
                <td><?php echo htmlspecialchars($record['Username']); ?></td>
                <td><?php echo htmlspecialchars($record['Password']); ?></td> <!-- Displaying Password -->
                <td><?php echo htmlspecialchars($record['State']); ?></td>
                <td>
                    <form action="View_Mb_Records.php?type=<?php echo urlencode($type); ?>" method="post">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($record['ID']); ?>">
                        <select name="state" onchange="this.form.submit()">
                            <option value="Active" <?php echo $record['State'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                            <option value="Gubbed" <?php echo $record['State'] == 'Gubbed' ? 'selected' : ''; ?>>Gubbed</option>
                            <option value="Not Active" <?php echo $record['State'] == 'Not Active' ? 'selected' : ''; ?>>Not Active</option>
                        </select>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
