<!--
    view/login.php — the Customer Login page.
    Flow: user fills form → js/customer.js loginCustomer() validates →
    POSTs to actions/login_action.php → which calls controller → model →
    returns JSON → JS stores session (server-side) and redirects.
-->
<?php
require_once "../core/core.php";

// If the user is already logged in, there is no reason to show the
// login form — send them straight to the homepage.
if (is_logged_in()) {
    header("Location: ../index.php");
    exit;
}

$pageTitle = "Login";
$navRoot   = "../";
$cssRoot   = "../";
include "layout/header.php";
?>

    <div class="card">
        <h1>Customer Login</h1>

        <!--
            Each input uses both a name (read by PHP via $_POST in the
            action file) and an id (read by js/customer.js in the browser).
            The form has no action/method attributes because JS intercepts
            the button click and sends the request itself via fetch().
        -->
        <form id="loginForm">
            <div>
                <label for="login_email">Email</label>
                <input type="email" name="login_email" id="login_email" autocomplete="email">
            </div>
            <div>
                <label for="login_pass">Password</label>
                <input type="password" name="login_pass" id="login_pass" autocomplete="current-password">
            </div>
            <div>
                <button type="button" onclick="loginCustomer()">Login</button>
            </div>
        </form>

        <!-- Validation / server messages appear here via JS -->
        <p id="loginMessage" class="msg"></p>

        <p style="margin-top:1rem; font-size:.875rem;">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>

    <script src="../js/customer.js"></script>

<?php include "layout/footer.php"; ?>
