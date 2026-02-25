<?php
session_start();
include "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            // simpan session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            header("Location: dashboard.php");
            exit();

        } else {
            $error = "Password salah!";
        }

    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - TaskFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<style>
body {
  background: linear-gradient(135deg, #667eea, #764ba2);
  height: 100vh;
}

.login-card {
  border-radius: 16px;
  transition: 0.3s;
}

.login-card:hover {
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

    <div class="card login-card shadow-lg border-0">

      <div class="card-body p-4">

        <!-- LOGO -->
        <div class="text-center mb-3">
          <div class="logo">TaskFlow</div>
          <small class="text-muted">Task Management App</small>
        </div>


        <h4 class="text-center mb-3">Login</h4>


        <!-- ALERT ERROR -->
        <?php if (isset($error)) { ?>
          <div class="alert alert-danger alert-dismissible fade show">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php } ?>


        <!-- FORM -->
        <form method="POST" onsubmit="showLoading()">


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
                    onclick="togglePassword()">

                👁

              </span>

            </div>

          </div>


          <!-- REMEMBER ME -->
          <div class="mb-3 form-check">

            <input type="checkbox"
                   class="form-check-input"
                   name="remember">

            <label class="form-check-label">
              Remember Me
            </label>

          </div>


          <!-- BUTTON -->
          <div class="d-grid">

            <button id="loginBtn"
                    type="submit"
                    class="btn btn-primary btn-lg">

              Login

            </button>

          </div>

        </form>


        <div class="text-center mt-3">

          <small>
            Belum punya akun?
            <a href="register.php">Register</a>
          </small>

        </div>

      </div>

    </div>

  </div>

</div>


<!-- SCRIPT -->
<script>

function togglePassword() {

  var password = document.getElementById("password");

  if (password.type === "password") {
    password.type = "text";
  } else {
    password.type = "password";
  }

}


function showLoading() {

  var btn = document.getElementById("loginBtn");

  btn.innerHTML = "Loading...";
  btn.disabled = true;

}

</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>