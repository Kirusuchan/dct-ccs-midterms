<?php
$title = "Register a Student"; // Set the title
session_start();
require_once '../functions.php';

// Guard to ensure only logged-in users can access this page
guard();

$errors = [];
$successMessage = "";

// Initialize students in session if not set
if (!isset($_SESSION['students'])) {
    $_SESSION['students'] = [];
}

// Handle form submission for adding a student
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentData = [
        'student_id' => trim($_POST['student_id']),
        'first_name' => trim($_POST['first_name']),
        'last_name' => trim($_POST['last_name']),
    ];

    // Validate required fields
    $errors = validateStudentData($studentData);

    // Check for duplicate student ID if no validation errors
    if (empty($errors)) {
        $duplicateCheck = checkDuplicateStudentData($studentData);
        if ($duplicateCheck) {
            $errors[] = $duplicateCheck;
        }
    }

    // If no errors, save the student
    if (empty($errors)) {
        $_SESSION['students'][] = $studentData;
        $successMessage = "Student added successfully!";
    }
}
?>

<?php require_once '../header.php'; ?>

<div class="container my-5">
    <h3>Register a New Student</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Register Student</li>
        </ol>
    </nav>

    <!-- Display success message if student added -->
    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($successMessage); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Display error messages -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>System Errors:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Add Student Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="register.php" method="POST">
                <div class="mb-3">
                    <label for="student_id" class="form-label">Student ID</label>
                    <input type="text" class="form-control" id="student_id" name="student_id" placeholder="Enter Student ID">
                </div>
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name">
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name">
                </div>
                <button type="submit" class="btn btn-primary">Add Student</button>
            </form>
        </div>
    </div>

    <!-- Student List Table -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Student List</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($_SESSION['students'])): ?>
                        <tr>
                            <td colspan="4" class="text-center">No student records found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($_SESSION['students'] as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($student['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['last_name']); ?></td>
                                <td>
                                    <a href="edit.php?id=<?php echo urlencode($student['student_id']); ?>" class="btn btn-sm btn-info">Edit</a>
                                    <a href="delete.php?id=<?php echo urlencode($student['student_id']); ?>" class="btn btn-sm btn-danger">Delete</a>
                                    <a href="attach-subject.php?student_id=<?php echo urlencode($student['student_id']); ?>" class="btn btn-sm btn-warning">Attach Subject</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../footer.php'; ?>
