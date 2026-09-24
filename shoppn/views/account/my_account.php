<?php
require_once __DIR__ . '/../../core/core.php';
require_login(); // Access control — redirects to login if not authenticated
?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<main class="auth-page">
  <div class="auth-card">
    <h1>My Account</h1>
    <p class="auth-sub">Welcome, <?= htmlspecialchars($_SESSION['customer_name'] ?? '') ?>!</p>

    <ul class="account-details">
      <li><strong>Email:</strong> <?= htmlspecialchars($_SESSION['customer_email'] ?? '') ?></li>
      <li><strong>Role:</strong> <?= ($_SESSION['user_role'] == 1) ? 'Admin' : 'Customer' ?></li>
    </ul>

    <a href="/shoppn/logout.php" class="btn-submit" style="display:inline-block;margin-top:1.5rem;">Logout</a>
  </div>
</main>

<?php include __DIR__ . '/../layout/footer.php'; ?>
