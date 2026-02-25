<?php
session_start();
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {

        $error = "Password tidak sama!";

    } else {

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $check = mysqli_query($conn,
            "SELECT * FROM users WHERE email='$email'"
        );

        if (mysqli_num_rows($check) > 0) {

            $error = "Email sudah terdaftar!";

        } else {

            mysqli_query($conn,
                "INSERT INTO users (name,email,password)
                 VALUES ('$name','$email','$hashed')"
            );

            $success = "Registrasi berhasil! Silakan login.";

        }

    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - TaskFlow</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<style>
body {
  background: linear-gradient(135deg, #667eea, #764ba2);
  height: 100vh;
}

.register-card {
  border-radius: 16px;
  transition: 0.3s;
}

.register-card:hover {
  transform: translateY(-4px);
}

.logo {
  font-size: 30px;
  font-weight: bold;
  color: #667eea;
}

.toggle-password {
  cursor: pointer;
}
</style>


<div class="container h-100 d-flex justify-content-center align-items-center">

  <div class="col-md-4">

    <div class="card register-card shadow-lg border-0">

      <div class="card-body p-4">

        <!-- LOGO -->
        <div class="text-center mb-3">
          <div class="logo">TaskFlow</div>
          <small class="text-muted">Create your account</small>
        </div>

        <h4 class="text-center mb-3">Register</h4>


        <!-- ALERT ERROR -->
        <?php if (isset($error)) { ?>
          <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php } ?>


        <!-- ALERT SUCCESS -->
        <?php if (isset($success)) { ?>
          <div class="alert alert-success alert-dismissible fade show">
            <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php } ?>


        <!-- FORM -->
        <form method="POST" onsubmit="showLoading()">


          <!-- NAME -->
          <div class="mb-3">

            <label>Nama</label>

            <input
              type="text"
              name="name"
              class="form-control form-control-lg"
              required>

          </div>


          <!-- EMAIL -->
          <div class="mb-3">

            <label>Email</label>

            <input
              type="email"
              name="email"
              class="form-control form-control-lg"
              required>

          </div>


          <!-- PASSWORD -->
          <div class="mb-3">

            <label>Password</label>

            <div class="input-group">

              <input
                type="password"
                name="password"
                id="password"
                class="form-control form-control-lg"
                required>

              <span class="input-group-text toggle-password"
                    onclick="togglePassword('password')">
                👁
              </span>

            </div>

          </div>


          <!-- CONFIRM PASSWORD -->
          <div class="mb-4">

            <label>Confirm Password</label>

            <div class="input-group">

              <input
                type="password"
                name="confirm_password"
                id="confirm"
                class="form-control form-control-lg"
                required>

              <span class="input-group-text toggle-password"
                    onclick="togglePassword('confirm')">
                👁
              </span>

            </div>

          </div>


          <!-- BUTTON -->
          <div class="d-grid">

            <button
              id="registerBtn"
              type="submit"
              class="btn btn-success btn-lg">

              Register

            </button>

          </div>

        </form>


        <div class="text-center mt-3">

          <small>
            Sudah punya akun?
            <a href="login.php">Login</a>
          </small>

        </div>

      </div>

    </div>

  </div>

</div>


<script>

function togglePassword(id) {

  var input = document.getElementById(id);

  if (input.type === "password") {
    input.type = "text";
  } else {
    input.type = "password";
  }

}


function showLoading() {

  var btn = document.getElementById("registerBtn");

  btn.innerHTML = "Processing...";
  btn.disabled = true;

}

</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>