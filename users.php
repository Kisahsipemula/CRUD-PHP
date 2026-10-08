<?php
// users.php — Admin only page
include 'auth.php';
require_admin();
include 'config.php';

// Handle delete user
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // Prevent deleting yourself
    if ($id != $_SESSION['user_id']) {
        $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
    }
    header("Location: users.php");
    exit;
}

$result = mysqli_query($conn, "SELECT id, username, role FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">User Management (Admin Only)</h4>
                <a href="user_create.php" class="btn btn-success btn-sm">+ Add New User</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $row['role'] === 'admin' ? 'danger' : 'secondary' ?>">
                                        <?= $row['role'] ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if ($row['id'] != $_SESSION['user_id']): ?>
                                    <a href="users.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?')">Delete</a>
                                    <?php else: ?>
                                    <span class="text-muted small">(Current User)</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>