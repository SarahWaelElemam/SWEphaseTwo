<?php
// Include necessary files
require_once("../../db/Dbh.php");
require_once("../../model/Admindb.php");

// Start session for message handling
session_start();

// Create a new database handler
$dbh = new Dbh();
$conn = $dbh->getConn();

// Create an instance of the Admindb model
$adminModel = new Admindb($conn);

$users = [];
$editUser = null;

try {
    // Fetch all users
    $users = $adminModel->getAllUsers();

    // Check if "Edit" button is clicked
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editUser'])) {
        $editUserId = $_POST['id'];
        $editUser = $adminModel->getUserById($editUserId);
    }
} catch (Exception $e) {
    $_SESSION['error_message'] = htmlspecialchars($e->getMessage());
    header("Location: dashboard.php");
    exit;
}

// Handle Update User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateUser'])) {
    $userId = $_POST['id'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
    $role = $_POST['role'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $birthdate = $_POST['birthdate'];
    $phone = $_POST['phone'];
    $government = $_POST['government'];
    $gender = $_POST['gender'];

    try {
        $adminModel->updateUser($userId, $email, $password, $role, $fname, $lname, $birthdate, $phone, $government, $gender);
        $_SESSION['success_message'] = "User updated successfully!";
        header("Location: dashboard.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['error_message'] = htmlspecialchars($e->getMessage());
        header("Location: dashboard.php");
        exit;
    }
}

// Handle Add User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addUser'])) {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $birthdate = $_POST['birthdate'];
    $phone = $_POST['phone'];
    $government = $_POST['government'];
    $gender = $_POST['gender'];

    try {
        $adminModel->addUser($email, $password, $role, $fname, $lname, $birthdate, $phone, $government, $gender);
        $_SESSION['success_message'] = "User added successfully!";
        header("Location: dashboard.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['error_message'] = htmlspecialchars($e->getMessage());
        header("Location: dashboard.php");
        exit;
    }
}

// Handle Delete User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteUser'])) {
    $userId = $_POST['id'];

    try {
        $adminModel->deleteUser($userId);
        $_SESSION['success_message'] = "User deleted successfully!";
        header("Location: dashboard.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['error_message'] = htmlspecialchars($e->getMessage());
        header("Location: dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/dashboard.css">
    <link href="../../../public/css/sidebar.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin Dashboard</title>
</head>
<body>
    <div class="sidebar">
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="chat.php">Chat</a></li>
            <li><a href="#">Calendar</a></li>
            <li><a href="#">Events</a></li>
            <li><a href="dashboard.php" class="active">User Management</a></li>
        </ul>
    </div>
    <div style="margin-left:300px;">
        <h1>Admin Dashboard</h1>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> <?= $_SESSION['success_message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> <?= $_SESSION['error_message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Add/Edit User Form -->
        <div class="admin-actions">
            <h3><?= $editUser ? 'Edit User' : 'Add User' ?></h3>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $editUser['User_ID'] ?? '' ?>">
                <input type="text" name="fname" placeholder="First Name" value="<?= htmlspecialchars($editUser['FName'] ?? '') ?>" required>
                <input type="text" name="lname" placeholder="Last Name" value="<?= htmlspecialchars($editUser['LName'] ?? '') ?>" required>
                <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($editUser['Email'] ?? '') ?>" required>
                <input type="password" name="password" placeholder="Password" <?= $editUser ? '' : 'required' ?>>
                <input type="date" name="birthdate" placeholder="Birth Date" value="<?= htmlspecialchars($editUser['BirthDate'] ?? '') ?>" required>
                <input type="text" name="phone" placeholder="Phone" value="<?= htmlspecialchars($editUser['Phone'] ?? '') ?>" required>
                <input type="text" name="government" placeholder="Government" value="<?= htmlspecialchars($editUser['Government'] ?? '') ?>" required>
                <select name="gender" required>
                    <option value="Male" <?= (isset($editUser['Gender']) && $editUser['Gender'] === 'Male') ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= (isset($editUser['Gender']) && $editUser['Gender'] === 'Female') ? 'selected' : '' ?>>Female</option>
                </select>
                <select name="role" required>
                    <option value="Admin" <?= (isset($editUser['Role']) && $editUser['Role'] === 'Admin') ? 'selected' : '' ?>>Admin</option>
                    <option value="Customer" <?= (isset($editUser['Role']) && $editUser['Role'] === 'Customer') ? 'selected' : '' ?>>Customer</option>
                    <option value="Organizer" <?= (isset($editUser['Role']) && $editUser['Role'] === 'Organizer') ? 'selected' : '' ?>>Organizer</option>
                </select>
                <button type="submit" name="<?= $editUser ? 'updateUser' : 'addUser' ?>" class="primary-btn"><?= $editUser ? 'Update User' : 'Add User' ?></button>
            </form>
        </div>

        <!-- Display Users -->
        <h2>All Users</h2>
        <table class="common-table-style" style="width:100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Birth Date</th>
                    <th>Phone</th>
                    <th>Government</th>
                    <th>Gender</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['User_ID'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($user['FName'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($user['LName'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($user['Email'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($user['Role'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($user['BirthDate'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($user['Phone'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($user['Government'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($user['Gender'] ?? 'N/A') ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['User_ID']) ?>">
                                    <button type="submit" name="editUser" class="edit-icon">Edit</button>
                                </form>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['User_ID']) ?>">
                                    <button type="submit" name="deleteUser" class="delete-icon">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
