<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ambil id task
if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$task_id = $_GET['id'];

// ambil data task dari database
$query = mysqli_query($conn,
    "SELECT * FROM tasks WHERE id='$task_id' AND user_id='$user_id'"
);

$task = mysqli_fetch_assoc($query);

if (!$task) {
    header("Location: dashboard.php");
    exit();
}

// UPDATE TASK
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $deadline = $_POST['deadline'];

    mysqli_query($conn,
        "UPDATE tasks SET
        title='$title',
        description='$description',
        status='$status',
        deadline='$deadline'
        WHERE id='$task_id' AND user_id='$user_id'"
    );

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Task - TaskFlow</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body {
  background: linear-gradient(135deg, #667eea, #764ba2);
  min-height: 100vh;
}

.edit-card {
  border-radius: 16px;
  transition: 0.3s;
}

.edit-card:hover {
  transform: translateY(-3px);
}

.logo {
  font-size: 28px;
  font-weight: bold;
  color: #667eea;
}

</style>

</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-primary shadow-sm">
  <div class="container-fluid">

    <span class="navbar-brand fw-bold">
      TaskFlow
    </span>

    <div>

      <a href="dashboard.php"
         class="btn btn-light btn-sm me-2">
         Dashboard
      </a>

      <a href="logout.php"
         class="btn btn-danger btn-sm">
         Logout
      </a>

    </div>

  </div>
</nav>


<!-- CONTENT -->
<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card edit-card shadow-lg border-0">

<div class="card-body p-4">

<div class="text-center mb-3">
<div class="logo">Edit Task</div>
<small class="text-muted">
Update task kamu
</small>
</div>


<form method="POST">

<!-- TITLE -->
<div class="mb-3">

<label class="form-label">Judul Task</label>

<input
type="text"
name="title"
class="form-control form-control-lg"
value="<?php echo $task['title']; ?>"
required>

</div>


<!-- DESCRIPTION -->
<div class="mb-3">

<label class="form-label">Deskripsi</label>

<textarea
name="description"
class="form-control"
rows="3"><?php echo $task['description']; ?></textarea>

</div>


<!-- STATUS -->
<div class="mb-3">

<label class="form-label">Status</label>

<select name="status"
class="form-select">

<option value="todo"
<?php if($task['status']=='todo') echo 'selected'; ?>>
To Do
</option>

<option value="doing"
<?php if($task['status']=='doing') echo 'selected'; ?>>
Doing
</option>

<option value="done"
<?php if($task['status']=='done') echo 'selected'; ?>>
Done
</option>

</select>

</div>


<!-- DEADLINE -->
<div class="mb-4">

<label class="form-label">Deadline</label>

<input
type="date"
name="deadline"
class="form-control"
value="<?php echo $task['deadline']; ?>">

</div>


<!-- BUTTON -->
<div class="d-grid gap-2">

<button
type="submit"
class="btn btn-primary btn-lg">

Update Task

</button>

<a href="dashboard.php"
class="btn btn-secondary">

Cancel

</a>

</div>

</form>


</div>
</div>

</div>
</div>
</div>

</body>
</html>