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

try {
    // Fetch all users
    $users = $adminModel->getAllUsers();
} catch (Exception $e) {
    $_SESSION['error_message'] = htmlspecialchars($e->getMessage());
    header("Location: user-management.php");
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
        header("Location: user-management.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['error_message'] = htmlspecialchars($e->getMessage());
        header("Location: user-management.php");
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
        header("Location: user-management.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['error_message'] = htmlspecialchars($e->getMessage());
        header("Location: user-management.php");
        exit;
    }
}

// Handle Delete User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteUser'])) {
    $userId = $_POST['id'];

    try {
        $adminModel->deleteUser($userId);
        $_SESSION['success_message'] = "User deleted successfully!";
        header("Location: user-management.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['error_message'] = htmlspecialchars($e->getMessage());
        header("Location: user-management.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="../../../public/css/core.css">
    <link rel="stylesheet" href="../../../public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Admin User Management</title>
</head>

<body>
    <div class="left-side-bar">
        <div class="brand-logo">
            <a href="/">
                <img src="..." alt="" />
            </a>
            <div class="close-sidebar" data-toggle="left-sidebar-close">
                <i class="ion-close-round"></i>
            </div>
        </div>
        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu">
                    <li>
                        <a href="dashboard.php" class="dropdown-toggle no-arrow">
                            <span class="micon fa fa-chart-line"></span><span class="mtext">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="chat.php" class="dropdown-toggle no-arrow">
                            <span class="micon fa fa-comment-dots"></span><span class="mtext">Chat</span>
                        </a>
                    </li>
                    <li>
                        <a href="calender.php" class="dropdown-toggle no-arrow">
                            <span class="micon fa fa-calendar-days"></span><span class="mtext">Calendar</span>
                        </a>
                    </li>
                    <li>
                        <a href="events.php" class="dropdown-toggle no-arrow">
                            <span class="micon fa fa-brands fa-fort-awesome"></span><span class="mtext">Events</span>
                        </a>
                    </li>
                    <li>
                        <a href="user-management.php" class="dropdown-toggle no-arrow">
                            <span class="micon fa fa-solid fa-users"></span><span class="mtext">User Management</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div>
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

            <div class="bg-white p-4">
                <div class="d-flex justify-content-between align-items-center pb-5">
                    <h1>All Users</h1>
                    <button type="button" class="btn btn-success mr-3" data-bs-toggle="modal" data-bs-target="#userModal">
                        Add User
                    </button>
                </div>

                <table id="userTable" class="data-table table table-hover table-striped table-bordered text-center align-middle">
                    <thead class="bg-primary text-white">
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
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['User_ID']) ?></td>
                                <td><?= htmlspecialchars($user['FName']) ?></td>
                                <td><?= htmlspecialchars($user['LName']) ?></td>
                                <td><?= htmlspecialchars($user['Email']) ?></td>
                                <td><?= htmlspecialchars($user['Role']) ?></td>
                                <td><?= htmlspecialchars($user['BirthDate']) ?></td>
                                <td><?= htmlspecialchars($user['Phone']) ?></td>
                                <td><?= htmlspecialchars($user['Government']) ?></td>
                                <td><?= htmlspecialchars($user['Gender']) ?></td>
                                <td>
                                    <button type="button" class="border-0 text-white bg-primary p-2" data-bs-toggle="modal"
                                            data-bs-target="#userModal"
                                            data-id="<?= htmlspecialchars($user['User_ID']) ?>"
                                            data-fname="<?= htmlspecialchars($user['FName']) ?>"
                                            data-lname="<?= htmlspecialchars($user['LName']) ?>"
                                            data-email="<?= htmlspecialchars($user['Email']) ?>"
                                            data-phone="<?= htmlspecialchars($user['Phone']) ?>"
                                            data-birthdate="<?= htmlspecialchars($user['BirthDate']) ?>"
                                            data-government="<?= htmlspecialchars($user['Government']) ?>"
                                            data-gender="<?= htmlspecialchars($user['Gender']) ?>"
                                            data-role="<?= htmlspecialchars($user['Role']) ?>">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($user['User_ID']) ?>">
                                        <button type="submit" name="deleteUser" class="border-0 bg-danger text-white p-2">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="userModalLabel">Edit User</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <input type="hidden" name="id" id="inputId" class="form-control">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="inputFName" class="form-label">First Name</label>
                                    <input type="text" name="fname" id="inputFName" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="inputLName" class="form-label">Last Name</label>
                                    <input type="text" name="lname" id="inputLName" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="inputEmail" class="form-label">Email</label>
                                    <input type="email" name="email" id="inputEmail" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="inputPhone" class="form-label">Phone</label>
                                    <input type="text" name="phone" id="inputPhone" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="inputBirthDate" class="form-label">Birth Date</label>
                                    <input type="date" name="birthdate" id="inputBirthDate" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="inputGovernment" class="form-label">Government</label>
                                    <input type="text" name="government" id="inputGovernment" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="inputGender" class="form-label">Gender</label>
                                    <select name="gender" id="inputGender" class="form-control" required>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="inputRole" class="form-label">Role</label>
                                    <select name="role" id="inputRole" class="form-control" required>
                                        <option value="Admin">Admin</option>
                                        <option value="Customer">Customer</option>
                                        <option value="Organizer">Organizer</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="inputPassword" class="form-label">Password</label>
                                <input type="password" name="password" id="inputPassword" class="form-control">
                            </div>
                            <button type="submit" name="updateUser" class="btn btn-success w-100">Update User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#userTable').DataTable({
                responsive: true,
                autoWidth: false,
                paging: true,
                searching: true,
                ordering: true,
                lengthChange: true
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const userModal = document.getElementById('userModal');

            userModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;

                const userId = button.getAttribute('data-id');
                const fname = button.getAttribute('data-fname');
                const lname = button.getAttribute('data-lname');
                const email = button.getAttribute('data-email');
                const phone = button.getAttribute('data-phone');
                const birthdate = button.getAttribute('data-birthdate');
                const government = button.getAttribute('data-government');
                const gender = button.getAttribute('data-gender');
                const role = button.getAttribute('data-role');

                userModal.querySelector('#inputId').value = userId || '';
                userModal.querySelector('#inputFName').value = fname || '';
                userModal.querySelector('#inputLName').value = lname || '';
                userModal.querySelector('#inputEmail').value = email || '';
                userModal.querySelector('#inputPhone').value = phone || '';
                userModal.querySelector('#inputBirthDate').value = birthdate || '';
                userModal.querySelector('#inputGovernment').value = government || '';
                userModal.querySelector('#inputGender').value = gender || 'Male';
                userModal.querySelector('#inputRole').value = role || 'Customer';
                userModal.querySelector('#inputPassword').value = '';

                // Set form action based on the button clicked
                const submitButton = userModal.querySelector('button[type="submit"]');
                if (userId) {
                    submitButton.name = 'updateUser';
                    submitButton.textContent = 'Update User';
                } else {
                    submitButton.name = 'addUser';
                    submitButton.textContent = 'Add User';
                }
            });
        });
    </script>
</body>

</html>
