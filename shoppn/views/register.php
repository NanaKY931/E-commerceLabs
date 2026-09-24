<?php
require_once __DIR__ . '/../core/core.php';
// Already logged in? Send to home
if (is_logged_in()) { redirect('../index.php'); }

// Grab and immediately clear any session error
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<?php include __DIR__ . '/layout/header.php'; ?>

<main class="auth-page">
  <div class="auth-card">
    <h1>Create an Account</h1>
    <p class="auth-sub">Fill in the details below to register.</p>

    <?php if ($error): ?>
      <div class="form-error server-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form id="registerForm" action="../actions/register_action.php" method="POST" novalidate>

      <div class="form-group">
        <label for="reg-name">Full Name *</label>
        <input id="reg-name"    type="text"     name="name"    maxlength="100" required>
        <span class="field-error" id="err-name"></span>
      </div>

      <div class="form-group">
        <label for="reg-email">Email Address *</label>
        <input id="reg-email"   type="email"    name="email"   maxlength="50"  required>
        <span class="field-error" id="err-email"></span>
      </div>

      <div class="form-group">
        <label for="reg-pass">Password *</label>
        <input id="reg-pass"    type="password" name="pass"    maxlength="100" required>
        <span class="field-error" id="err-pass"></span>
      </div>

      <div class="form-group">
        <label for="reg-country">Country *</label>
        <select id="reg-country" name="country" required>
          <option value="">-- Select Country --</option>
          <option value="Ghana">Ghana</option>
          <option value="Nigeria">Nigeria</option>
          <option value="Kenya">Kenya</option>
          <option value="South Africa">South Africa</option>
          <option value="Other">Other</option>
        </select>
        <span class="field-error" id="err-country"></span>
      </div>

      <div class="form-group">
        <label for="reg-city">City *</label>
        <input id="reg-city"    type="text"     name="city"    maxlength="100" required>
        <span class="field-error" id="err-city"></span>
      </div>

      <div class="form-group">
        <label for="reg-contact">Contact Number *</label>
        <input id="reg-contact" type="tel"      name="contact" maxlength="100" required>
        <span class="field-error" id="err-contact"></span>
      </div>

      <button type="submit" id="regSubmitBtn" class="btn-submit">
        Create Account
      </button>
    </form>

    <p class="auth-switch">Already have an account? <a href="/shoppn/views/login.php">Login</a></p>
  </div>
</main>

<?php include __DIR__ . '/layout/footer.php'; ?>
