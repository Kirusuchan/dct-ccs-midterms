<?php
$title = "Edit a Student"; // Set the title
session_start();
require_once '../functions.php';

// Guard to ensure only logged-in users can access this page
guard();

// Check if the student ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: register.php");
    exit;
}

$student_id = $_GET['id'];
$studentIndex = getSelectedStudentIndex($student_id);
$studentData = getSelectedStudentData($studentIndex);

if (!$studentData) {
    header("Location: register.php");
    exit;
}

$errors = [];

// Handle form submission for updating student data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated data from the form
    $updatedStudentData = [
        'student_id' => $student_id,  // Student ID cannot be changed
        'first_name' => trim($_POST['first_name']),
        'last_name' => trim($_POST['last_name']),
    ];

    // Only check for empty inputs
    if (empty($updatedStudentData['first_name'])) {
        $errors[] = "First Name cannot be empty.";
    }
    if (empty($updatedStudentData['last_name'])) {
        $errors[] = "Last Name cannot be empty.";
    }

    // If no errors, update the student data and redirect
    if (empty($errors)) {
        $_SESSION['students'][$studentIndex] = $updatedStudentData;  // Update session data
        $_SESSION['success_message'] = "Student information updated successfully!";
        header("Location: register.php");
        exit;
    }
}
?>

<?php require_once '../header.php'; ?>

<div class="container my-5">
    <h3>Edit Student</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="../dashboard.php">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="register.php">Register Student</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Student</li>
        </ol>
    </nav>

    <!-- Display error messages -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Errors:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Edit Student Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="edit.php?id=<?php echo urlencode($student_id); ?>" novalidate>
                <div class="mb-3">
                    <label for="student_id" class="form-label">Student ID</label>
                    <input type="text" class="form-control" id="student_id" name="student_id" value="<?php echo htmlspecialchars($studentData['student_id']); ?>" readonly>
                </div>
                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($studentData['first_name']); ?>">
                </div>
                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($studentData['last_name']); ?>">
                </div>
                <button type="submit" class="btn btn-primary">Update Student</button>
            </form>
        </div>
    </div>
</div>

<?php require_once '../footer.php'; ?>
