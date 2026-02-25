<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
// STATISTIK
$total_query = mysqli_query($conn,
    "SELECT COUNT(*) as total FROM tasks WHERE user_id='$user_id'");
$total = mysqli_fetch_assoc($total_query)['total'];

$todo_query = mysqli_query($conn,
    "SELECT COUNT(*) as total FROM tasks WHERE user_id='$user_id' AND status='todo'");
$todo = mysqli_fetch_assoc($todo_query)['total'];

$doing_query = mysqli_query($conn,
    "SELECT COUNT(*) as total FROM tasks WHERE user_id='$user_id' AND status='doing'");
$doing = mysqli_fetch_assoc($doing_query)['total'];

$done_query = mysqli_query($conn,
    "SELECT COUNT(*) as total FROM tasks WHERE user_id='$user_id' AND status='done'");
$done = mysqli_fetch_assoc($done_query)['total'];


// HITUNG PROGRESS (letakkan di sini)
$progress = 0;

if ($total > 0) {
    $progress = round(($done / $total) * 100);
}

$search = "";
$status_filter = "";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

if (isset($_GET['status'])) {
    $status_filter = $_GET['status'];
}

$query = "SELECT * FROM tasks WHERE user_id='$user_id'";

if (!empty($search)) {
    $query .= " AND title LIKE '%$search%'";
}

if (!empty($status_filter)) {
    $query .= " AND status='$status_filter'";
}

$query .= " ORDER BY id DESC";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - TaskFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>

.task-card {
  border-radius: 15px;
  transition: 0.3s;
}

.task-card:hover {
  transform: translateY(-5px);
}

</style>
</head>
<body>

<!-- TOP NAVBAR -->
<nav class="navbar navbar-dark bg-primary shadow-sm">
  <div class="container-fluid">
    <span class="navbar-brand fw-bold">TaskFlow</span>
    <span class="text-white">
      Hi, <?php echo $_SESSION['user_name']; ?>
      <a href="logout.php" class="btn btn-light btn-sm ms-3">Logout</a>
    </span>
  </div>
</nav>


<div class="container-fluid">
  <div class="row">

    <!-- SIDEBAR -->
    <div class="col-md-2 bg-dark text-white min-vh-100 p-3">

      <h5 class="mb-4">Menu</h5>

      <ul class="nav flex-column">

        <li class="nav-item mb-2">
          <a href="dashboard.php" class="nav-link text-white">
            📊 Dashboard
          </a>
        </li>

        <li class="nav-item mb-2">
          <a href="add_task.php" class="nav-link text-white">
            ➕ Tambah Task
          </a>
        </li>

      </ul>

    </div>


    <!-- MAIN CONTENT -->
    <div class="col-md-10 p-4 bg-light">

      <h3 class="mb-4">Dashboard</h3>
      <div class="row mb-4">

  <!-- TOTAL -->
  <div class="col-md-3">
    <div class="card shadow border-0">
      <div class="card-body text-center">
        <h6 class="text-muted">Total Task</h6>
        <h2 class="fw-bold"><?php echo $total; ?></h2>
      </div>
    </div>
  </div>

  <!-- TODO -->
  <div class="col-md-3">
    <div class="card shadow border-0">
      <div class="card-body text-center">
        <h6 class="text-muted">To Do</h6>
        <h2 class="fw-bold text-secondary">
          <?php echo $todo; ?>
        </h2>
      </div>
    </div>
  </div>

  <!-- DOING -->
  <div class="col-md-3">
    <div class="card shadow border-0">
      <div class="card-body text-center">
        <h6 class="text-muted">Doing</h6>
        <h2 class="fw-bold text-warning">
          <?php echo $doing; ?>
        </h2>
      </div>
    </div>
  </div>

  <!-- DONE -->
  <div class="col-md-3">
    <div class="card shadow border-0">
      <div class="card-body text-center">
        <h6 class="text-muted">Done</h6>
        <h2 class="fw-bold text-success">
          <?php echo $done; ?>
        </h2>
      </div>
    </div>
  </div>

</div>
<div class="card shadow border-0 mb-4">
  <div class="card-body">

    <div class="d-flex justify-content-between mb-2">
      <span class="fw-bold">Progress Task</span>
      <span><?php echo $progress; ?>%</span>
    </div>

    <div class="progress" style="height: 20px; border-radius:10px;">

      <div class="progress-bar bg-success"
           style="width: <?php echo $progress; ?>%">
      </div>

    </div>

  </div>
</div>
      <div class="card mb-3 shadow-sm">
  <div class="card-body">

    <form method="GET" class="row g-2">

      <div class="col-md-5">
        <input type="text"
               name="search"
               class="form-control"
               placeholder="🔍 Cari task..."
               value="<?php echo $search; ?>">
      </div>

      <div class="col-md-4">
        <select name="status" class="form-select">

          <option value="">Semua Status</option>

          <option value="todo"
          <?php if($status_filter=='todo') echo 'selected'; ?>>
          To Do
          </option>

          <option value="doing"
          <?php if($status_filter=='doing') echo 'selected'; ?>>
          Doing
          </option>

          <option value="done"
          <?php if($status_filter=='done') echo 'selected'; ?>>
          Done
          </option>

        </select>
      </div>

      <div class="col-md-3 d-grid">
        <button class="btn btn-primary">
          Filter
        </button>
      </div>

    </form>

  </div>
</div>

      <div class="card shadow">

        <div class="card-body">

<?php if (mysqli_num_rows($result) > 0) { ?>

<div class="row">

<?php while ($task = mysqli_fetch_assoc($result)) { ?>

  <div class="col-md-4 mb-4">

    <div class="card shadow border-0 h-100 task-card">

      <div class="card-body d-flex flex-column">

        <!-- STATUS -->
        <div class="mb-2">

          <?php
          if ($task['status'] == 'todo') {
              echo '<span class="badge bg-secondary">To Do</span>';
          }
          elseif ($task['status'] == 'doing') {
              echo '<span class="badge bg-warning text-dark">Doing</span>';
          }
          else {
              echo '<span class="badge bg-success">Done</span>';
          }
          ?>

        </div>

        <h5 class="fw-bold">
          <?php echo $task['title']; ?>
        </h5>

        <p class="text-muted small flex-grow-1">
          <?php echo $task['description']; ?>
        </p>

        <p class="small">
          📅 Deadline:
          <strong>
          <?php echo $task['deadline']; ?>
          </strong>
        </p>

        <div class="d-flex justify-content-between mt-3">

          <a href="edit_task.php?id=<?php echo $task['id']; ?>"
             class="btn btn-sm btn-warning">
             Edit
          </a>

          <a href="delete_task.php?id=<?php echo $task['id']; ?>"
             class="btn btn-sm btn-danger"
             onclick="return confirm('Apakah kamu yakin ingin menghapus task ini?')">
             Delete
          </a>

        </div>

      </div>

    </div>

  </div>

<?php } ?>

</div>

<?php } else { ?>

<div class="card shadow border-0 text-center p-5">

<h4 class="text-muted">Belum ada task</h4>

<p class="text-muted">
Klik tombol Tambah Task untuk membuat task baru
</p>

<a href="add_task.php" class="btn btn-primary">
Tambah Task
</a>

</div>

<?php } ?>

        </div>

      </div>

    </div>

  </div>
</div>

</body>
</html>