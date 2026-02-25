<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $deadline = $_POST['deadline'];

    $query = "INSERT INTO tasks (user_id, title, description, status, deadline)
              VALUES ('$user_id', '$title', '$description', '$status', '$deadline')";

    if (mysqli_query($conn, $query)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Task - TaskFlow</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-primary shadow-sm">
  <div class="container-fluid">
    <span class="navbar-brand fw-bold">TaskFlow</span>

    <div>
      <a href="dashboard.php" class="btn btn-light btn-sm me-2">
        Dashboard
      </a>

      <a href="logout.php" class="btn btn-danger btn-sm">
        Logout
      </a>
    </div>
  </div>
</nav>


<!-- CONTENT -->
<div class="container mt-5">

  <div class="row justify-content-center">

    <div class="col-md-6">

      <div class="card shadow-lg border-0 rounded-4">

        <div class="card-body p-4">

          <h3 class="mb-4 text-center fw-bold">
            Tambah Task Baru
          </h3>

          <form method="POST">

            <!-- TITLE -->
            <div class="mb-3">
              <label class="form-label">Judul Task</label>

              <input
                type="text"
                name="title"
                class="form-control form-control-lg"
                placeholder="Contoh: Kerjakan laporan"
                required>
            </div>


            <!-- DESCRIPTION -->
            <div class="mb-3">

              <label class="form-label">Deskripsi</label>

              <textarea
                name="description"
                class="form-control"
                rows="3"
                placeholder="Deskripsi task..."></textarea>

            </div>


            <!-- STATUS -->
            <div class="mb-3">

              <label class="form-label">Status</label>

              <select
                name="status"
                class="form-select">

                <option value="todo">To Do</option>
                <option value="doing">Doing</option>
                <option value="done">Done</option>

              </select>

            </div>


            <!-- DEADLINE -->
            <div class="mb-4">

              <label class="form-label">Deadline</label>

              <input
                type="date"
                name="deadline"
                class="form-control">

            </div>


            <!-- BUTTON -->
            <div class="d-grid">

              <button
                type="submit"
                class="btn btn-primary btn-lg rounded-3 shadow-sm">

                Simpan Task

              </button>

            </div>


          </form>

        </div>

      </div>

    </div>

  </div>

</div>

</body>
</html>