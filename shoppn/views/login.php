<?php
require_once __DIR__ . '/../core/core.php';
if (is_logged_in()) { redirect('../index.php'); }

$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<?php include __DIR__ . '/layout/header.php'; ?>

<main class="auth-page">
  <div class="auth-card">
    <h1>Login</h1>
    <p class="auth-sub">Welcome back. Enter your credentials to continue.</p>

    <?php if ($error): ?>
      <div class="form-error server-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form id="loginForm" action="../actions/login_action.php" method="POST" novalidate>

      <div class="form-group">
        <label for="login-email">Email Address *</label>
        <input id="login-email" type="email" name="email" maxlength="50" required>
        <span class="field-error" id="err-login-email"></span>
      </div>

      <div class="form-group">
        <label for="login-pass">Password *</label>
        <input id="login-pass" type="password" name="pass" required>
        <span class="field-error" id="err-login-pass"></span>
      </div>

      <button type="submit" id="loginSubmitBtn" class="btn-submit">
        Login
      </button>
    </form>

    <p class="auth-switch">Don't have an account? <a href="/shoppn/views/register.php">Register</a></p>
  </div>
</main>

<?php include __DIR__ . '/layout/footer.php'; ?>
