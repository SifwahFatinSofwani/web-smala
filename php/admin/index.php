<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login – Portal Alumni SMAN 5 Samarinda</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../../css/admin.css">
</head>
<body class="login-page">

  <div class="blob blob-1"></div>
  <div class="blob blob-2"></div>

  <div class="login-wrapper">
    <div class="login-card">

      <!-- Brand -->
      <div class="login-brand" style="justify-content: center; text-align: center; gap: 0;">
        <div>
          <h1>Admin Panel</h1>
          <p>Portal Alumni SMAN 5 Samarinda</p>
        </div>
      </div>

      <!-- Error alert (PHP) -->
      <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-error">
        <i class="fa-solid fa-circle-exclamation"></i>
        Username atau password salah. Silakan coba lagi.
      </div>
      <?php endif; ?>

      <!-- Login Form -->
      <form action="auth.php" method="POST" class="login-form" id="loginForm">
        <div class="form-group">
          <label for="username">Username</label>
          <div class="input-wrap">
            <i class="fa-regular fa-user input-icon"></i>
            <input
              type="text"
              id="username"
              name="username"
              placeholder="Masukkan username"
              required
              autocomplete="username"
            >
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrap">
            <i class="fa-solid fa-lock input-icon"></i>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Masukkan password"
              required
              autocomplete="current-password"
            >
            <button type="button" class="toggle-pw" id="togglePw" aria-label="Tampilkan password">
              <i class="fa-regular fa-eye" id="eyeIcon"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn-login" id="btnLogin">
          <span>Masuk ke Dashboard</span>
          <i class="fa-solid fa-arrow-right"></i>
        </button>
      </form>

      <p class="login-back">
        <a href="../index.php">
          <i class="fa-solid fa-arrow-left"></i> Kembali ke Portal Alumni
        </a>
      </p>
    </div>
  </div>

  <script>
    // Toggle password
    document.getElementById('togglePw').addEventListener('click', function() {
      const pw   = document.getElementById('password');
      const icon = document.getElementById('eyeIcon');
      const show = pw.type === 'password';
      pw.type            = show ? 'text' : 'password';
      icon.className     = show ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
    });

    // Loading on submit
    document.getElementById('loginForm').addEventListener('submit', function() {
      const btn = document.getElementById('btnLogin');
      btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Memproses...';
      btn.disabled  = true;
    });
  </script>
</body>
</html>
